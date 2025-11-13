<?php

namespace App\Jobs;

use App\Models\WhatsappBroadcast;
use App\Models\WhatsappBroadcastRecipient;
use App\Models\WhatsappConnection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SendWhatsappBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $broadcast;
    public $timeout = 600; // 10 minutes
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(WhatsappBroadcast $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Mark broadcast as sending
            $this->broadcast->start();

            $connection = WhatsappConnection::findOrFail($this->broadcast->connection_id);

            if (!$connection->isReady()) {
                throw new \Exception('WhatsApp connection is not ready');
            }

            $recipients = $this->broadcast->recipients()->where('status', 'pending')->get();

            $client = new Client();
            $apiUrl = $connection->connection_data['api_url'] ?? config('whatsapp.api_url');
            $apiKey = $connection->connection_data['api_key'] ?? config('whatsapp.api_key');

            foreach ($recipients as $recipient) {
                try {
                    // Send message via WhatsApp API
                    $response = $client->post($apiUrl . '/message/sendText/' . $connection->phone_number, [
                        'headers' => [
                            'apikey' => $apiKey,
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'number' => $recipient->phone_number,
                            'text' => $this->broadcast->message,
                        ],
                        'timeout' => 30
                    ]);

                    $responseData = json_decode($response->getBody(), true);

                    // Mark as sent
                    $recipient->markAsSent($responseData['key']['id'] ?? null);

                    // Update broadcast counts
                    $this->broadcast->increment('sent_count');

                    // Rate limiting - wait between messages
                    usleep(500000); // 0.5 seconds delay

                } catch (\Exception $e) {
                    Log::error('Broadcast send error for recipient: ' . $recipient->id, [
                        'error' => $e->getMessage(),
                        'recipient' => $recipient->phone_number
                    ]);

                    $recipient->markAsFailed($e->getMessage());
                    $this->broadcast->increment('failed_count');
                }
            }

            // Mark broadcast as completed
            $this->broadcast->complete();

            Log::info('Broadcast completed', [
                'broadcast_id' => $this->broadcast->id,
                'sent_count' => $this->broadcast->sent_count,
                'failed_count' => $this->broadcast->failed_count
            ]);

        } catch (\Exception $e) {
            Log::error('Broadcast job failed', [
                'broadcast_id' => $this->broadcast->id,
                'error' => $e->getMessage()
            ]);

            $this->broadcast->markAsFailed();
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Broadcast job completely failed', [
            'broadcast_id' => $this->broadcast->id,
            'error' => $exception->getMessage()
        ]);

        $this->broadcast->markAsFailed();
    }
}
