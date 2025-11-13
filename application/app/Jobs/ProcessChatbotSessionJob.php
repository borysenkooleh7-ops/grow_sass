<?php

namespace App\Jobs;

use App\Models\WhatsappChatbotSession;
use App\Models\WhatsappChatbotStep;
use App\Models\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessChatbotSessionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $session;
    protected $userResponse;

    public $timeout = 60;
    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(WhatsappChatbotSession $session, $userResponse = null)
    {
        $this->session = $session;
        $this->userResponse = $userResponse;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            if (!$this->session->isActive()) {
                Log::debug('Chatbot session is not active', [
                    'session_id' => $this->session->id
                ]);
                return;
            }

            // Get current step
            $currentStep = $this->session->currentStep;

            if (!$currentStep) {
                // Start from first step
                $currentStep = $this->session->flow->getFirstStep();

                if (!$currentStep) {
                    Log::warning('No steps found in chatbot flow', [
                        'flow_id' => $this->session->flow_id
                    ]);
                    $this->session->abandon();
                    return;
                }

                $this->session->moveToStep($currentStep->id);
            }

            // Process current step
            $this->processStep($currentStep);

            // If user provided a response, determine next step
            if ($this->userResponse !== null) {
                $nextStepId = $currentStep->processResponse($this->userResponse, $this->session);

                if ($nextStepId) {
                    $this->session->moveToStep($nextStepId);

                    // Process next step automatically
                    $nextStep = WhatsappChatbotStep::find($nextStepId);
                    if ($nextStep) {
                        $this->processStep($nextStep);
                    }
                } else {
                    // No next step, complete session
                    $this->session->complete();
                }
            }

            Log::info('Chatbot session processed', [
                'session_id' => $this->session->id,
                'current_step' => $currentStep->id
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot session processing failed', [
                'session_id' => $this->session->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Process a specific step
     */
    protected function processStep($step)
    {
        switch ($step->type) {
            case 'message':
                $this->sendMessage($step->content);

                // Auto-advance to next step
                if ($step->next_step_id) {
                    $this->session->moveToStep($step->next_step_id);
                } else {
                    $this->session->complete();
                }
                break;

            case 'question':
                $this->sendQuestion($step);
                // Wait for user response
                break;

            case 'condition':
                $this->evaluateCondition($step);
                break;

            case 'action':
                $this->executeAction($step);

                // Auto-advance to next step
                if ($step->next_step_id) {
                    $this->session->moveToStep($step->next_step_id);
                } else {
                    $this->session->complete();
                }
                break;
        }
    }

    /**
     * Send a message
     */
    protected function sendMessage($content)
    {
        $ticket = $this->session->ticket;

        $message = WhatsappMessage::create([
            'ticket_id' => $ticket->id,
            'sender_type' => 'agent',
            'sender_id' => null,
            'sender_name' => 'Chatbot',
            'channel' => 'whatsapp',
            'body' => $content,
            'status' => 'pending',
            'metadata' => [
                'chatbot' => true,
                'session_id' => $this->session->id,
                'flow_id' => $this->session->flow_id
            ]
        ]);

        // Queue message sending
        dispatch(new SendWhatsappMessageJob(
            $message,
            $ticket->contact_phone,
            $ticket->connection_id ?? null
        ));
    }

    /**
     * Send a question with options
     */
    protected function sendQuestion($step)
    {
        $content = $step->content;

        // Add options to message
        if ($step->options && is_array($step->options)) {
            $content .= "\n\n";
            foreach ($step->options as $index => $option) {
                $content .= ($index + 1) . ". " . ($option['label'] ?? $option['value']) . "\n";
            }
        }

        $this->sendMessage($content);
    }

    /**
     * Evaluate a condition
     */
    protected function evaluateCondition($step)
    {
        // TODO: Implement condition evaluation logic
        // For now, just move to next step
        if ($step->next_step_id) {
            $this->session->moveToStep($step->next_step_id);
        } else {
            $this->session->complete();
        }
    }

    /**
     * Execute an action
     */
    protected function executeAction($step)
    {
        $options = $step->options;
        $actionType = $options['action_type'] ?? null;

        switch ($actionType) {
            case 'hand_off_to_agent':
                $this->session->handOff();
                break;

            case 'create_ticket':
                // Ticket already exists from session
                break;

            case 'store_data':
                $key = $options['key'] ?? null;
                $value = $options['value'] ?? null;
                if ($key && $value) {
                    $this->session->setContext($key, $value);
                }
                break;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Chatbot session job failed', [
            'session_id' => $this->session->id,
            'error' => $exception->getMessage()
        ]);

        $this->session->abandon();
    }
}
