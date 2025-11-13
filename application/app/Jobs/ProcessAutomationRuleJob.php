<?php

namespace App\Jobs;

use App\Models\WhatsappAutomationRule;
use App\Models\WhatsappTicket;
use App\Models\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAutomationRuleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rule;
    protected $data;
    protected $context;

    public $timeout = 120;
    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(WhatsappAutomationRule $rule, array $data, array $context = [])
    {
        $this->rule = $rule;
        $this->data = $data;
        $this->context = $context;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Check if rule conditions match
            if (!$this->rule->matchesConditions($this->data)) {
                Log::debug('Automation rule conditions not met', [
                    'rule_id' => $this->rule->id,
                    'data' => $this->data
                ]);
                return;
            }

            // Execute actions
            foreach ($this->rule->actions as $action) {
                $this->executeAction($action);
            }

            // Increment triggered count
            $this->rule->incrementTriggered();

            Log::info('Automation rule executed', [
                'rule_id' => $this->rule->id,
                'actions_count' => count($this->rule->actions)
            ]);

        } catch (\Exception $e) {
            Log::error('Automation rule execution failed', [
                'rule_id' => $this->rule->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Execute a single action
     */
    protected function executeAction($action)
    {
        $actionType = $action['type'] ?? null;

        switch ($actionType) {
            case 'send_message':
                $this->sendMessage($action);
                break;

            case 'assign_ticket':
                $this->assignTicket($action);
                break;

            case 'change_status':
                $this->changeTicketStatus($action);
                break;

            case 'add_tag':
                $this->addTag($action);
                break;

            case 'send_notification':
                $this->sendNotification($action);
                break;

            case 'create_task':
                $this->createTask($action);
                break;

            case 'trigger_webhook':
                $this->triggerWebhook($action);
                break;

            default:
                Log::warning('Unknown automation action type', [
                    'type' => $actionType,
                    'rule_id' => $this->rule->id
                ]);
        }
    }

    /**
     * Send automated message
     */
    protected function sendMessage($action)
    {
        $ticketId = $this->data['ticket_id'] ?? $this->context['ticket_id'] ?? null;

        if (!$ticketId) {
            Log::warning('Cannot send message: no ticket_id');
            return;
        }

        $ticket = WhatsappTicket::find($ticketId);
        if (!$ticket) {
            Log::warning('Cannot send message: ticket not found', ['ticket_id' => $ticketId]);
            return;
        }

        $message = WhatsappMessage::create([
            'ticket_id' => $ticketId,
            'sender_type' => 'agent',
            'sender_id' => null, // Automated message
            'sender_name' => 'Automated Bot',
            'channel' => 'whatsapp',
            'body' => $action['message'] ?? 'Automated message',
            'status' => 'pending',
            'metadata' => [
                'automated' => true,
                'rule_id' => $this->rule->id
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
     * Assign ticket to user/team
     */
    protected function assignTicket($action)
    {
        $ticketId = $this->data['ticket_id'] ?? $this->context['ticket_id'] ?? null;

        if (!$ticketId) {
            return;
        }

        $ticket = WhatsappTicket::find($ticketId);
        if (!$ticket) {
            return;
        }

        $assignToId = $action['assign_to'] ?? null;
        if ($assignToId) {
            $ticket->agent_id = $assignToId;
            $ticket->save();
        }
    }

    /**
     * Change ticket status
     */
    protected function changeTicketStatus($action)
    {
        $ticketId = $this->data['ticket_id'] ?? $this->context['ticket_id'] ?? null;

        if (!$ticketId) {
            return;
        }

        $ticket = WhatsappTicket::find($ticketId);
        if (!$ticket) {
            return;
        }

        $newStatus = $action['status'] ?? null;
        if ($newStatus && in_array($newStatus, ['open', 'in_progress', 'closed'])) {
            $ticket->status = $newStatus;
            $ticket->save();
        }
    }

    /**
     * Add tag to contact
     */
    protected function addTag($action)
    {
        // Implementation for adding tags
        Log::debug('Add tag action', $action);
    }

    /**
     * Send notification
     */
    protected function sendNotification($action)
    {
        // Implementation for sending notifications to agents
        Log::debug('Send notification action', $action);
    }

    /**
     * Create task
     */
    protected function createTask($action)
    {
        // Implementation for creating tasks
        Log::debug('Create task action', $action);
    }

    /**
     * Trigger webhook
     */
    protected function triggerWebhook($action)
    {
        $webhookUrl = $action['webhook_url'] ?? null;

        if (!$webhookUrl) {
            return;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $client->post($webhookUrl, [
                'json' => array_merge($this->data, [
                    'rule_id' => $this->rule->id,
                    'triggered_at' => now()->toIso8601String()
                ]),
                'timeout' => 10
            ]);
        } catch (\Exception $e) {
            Log::error('Webhook trigger failed', [
                'url' => $webhookUrl,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Automation rule job failed', [
            'rule_id' => $this->rule->id,
            'error' => $exception->getMessage()
        ]);
    }
}
