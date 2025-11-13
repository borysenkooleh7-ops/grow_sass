<?php

namespace App\Jobs;

use App\Models\WhatsappMessage;
use App\Models\WhatsappConnection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SendWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $recipientPhone;
    protected $connectionId;

    public $timeout = 60;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(WhatsappMessage $message, $recipientPhone, $connectionId)
    {
        $this->message = $message;
        $this->recipientPhone = $recipientPhone;
        $this->connectionId = $connectionId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $connection = WhatsappConnection::findOrFail($this->connectionId);

            // Check if connection is active
            if (!$connection->whatsappconnection_is_active) {
                throw new \Exception('WhatsApp connection is not active');
            }

            $client = new Client([
                'timeout' => 30,
                'verify' => false
            ]);

            // Get connection type and credentials
            $connectionType = $connection->whatsappconnection_type;
            $messageContent = $this->message->whatsappmessage_content ?? '';
            $mediaUrl = $this->message->whatsappmessage_media_url;

            // Route to appropriate API handler
            switch ($connectionType) {
                case 'wati':
                    $this->sendViaWati($client, $connection, $messageContent, $mediaUrl);
                    break;

                case 'evolution':
                case 'baileys':
                    $this->sendViaEvolution($client, $connection, $messageContent, $mediaUrl);
                    break;

                case 'twilio':
                    $this->sendViaTwilio($client, $connection, $messageContent, $mediaUrl);
                    break;

                case 'meta':
                case 'cloud':
                    $this->sendViaCloudAPI($client, $connection, $messageContent, $mediaUrl);
                    break;

                default:
                    throw new \Exception('Unsupported connection type: ' . $connectionType);
            }

            Log::info('WhatsApp message sent', [
                'message_id' => $this->message->whatsappmessage_id,
                'recipient' => $this->recipientPhone,
                'type' => $connectionType
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp message send failed', [
                'message_id' => $this->message->whatsappmessage_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->message->whatsappmessage_status = 'failed';
            $this->message->whatsappmessage_error = $e->getMessage();
            $this->message->save();

            throw $e;
        }
    }

    /**
     * Send message via WATI API
     */
    protected function sendViaWati($client, $connection, $messageContent, $mediaUrl)
    {
        $apiEndpoint = config('whatsapp.wati.api_endpoint', 'https://app-server.wati.io');
        $accessToken = $connection->whatsappconnection_api_key;

        if (empty($accessToken)) {
            throw new \Exception('WATI access token not configured');
        }

        // Format phone number (remove + if present, WATI expects without +)
        $phone = str_replace(['+', ' ', '-'], '', $this->recipientPhone);

        // Send text message
        if (empty($mediaUrl)) {
            $response = $client->post($apiEndpoint . '/api/v1/sendSessionMessage/' . $phone, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messageText' => $messageContent
                ]
            ]);
        } else {
            // Send media message
            $messageType = $this->message->whatsappmessage_type;
            $endpoint = '/api/v1/sendSessionFile/' . $phone;

            $payload = [
                'caption' => $messageContent,
                'mediaUrl' => $mediaUrl
            ];

            $response = $client->post($apiEndpoint . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload
            ]);
        }

        $responseData = json_decode($response->getBody(), true);

        // Update message status
        $this->message->whatsappmessage_status = 'sent';
        $this->message->whatsappmessage_external_id = $responseData['messageId'] ?? ('wati_' . time());
        $this->message->whatsappmessage_delivered_at = now();
        $this->message->save();

        Log::info('WATI message sent successfully', [
            'message_id' => $this->message->whatsappmessage_id,
            'external_id' => $this->message->whatsappmessage_external_id
        ]);
    }

    /**
     * Send message via Evolution API
     */
    protected function sendViaEvolution($client, $connection, $messageContent, $mediaUrl)
    {
        // Get settings from connection or config
        $settings = json_decode($connection->whatsappconnection_settings ?? '{}', true);
        $apiUrl = $settings['api_url'] ?? config('whatsapp.evolution_api_url');
        $apiKey = $connection->whatsappconnection_api_key ?? config('whatsapp.evolution_api_key');
        $instanceId = $connection->whatsappconnection_instance_id;

        if (empty($instanceId)) {
            throw new \Exception('Evolution instance ID not configured');
        }

        // Prepare payload
        if (empty($mediaUrl)) {
            // Text message
            $endpoint = '/message/sendText/' . $instanceId;
            $payload = [
                'number' => $this->recipientPhone,
                'textMessage' => [
                    'text' => $messageContent
                ]
            ];
        } else {
            // Media message
            $endpoint = '/message/sendMedia/' . $instanceId;
            $payload = [
                'number' => $this->recipientPhone,
                'mediaMessage' => [
                    'mediaUrl' => $mediaUrl,
                    'caption' => $messageContent
                ]
            ];
        }

        $response = $client->post($apiUrl . $endpoint, [
            'headers' => [
                'apikey' => $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload
        ]);

        $responseData = json_decode($response->getBody(), true);

        // Update message status
        $this->message->whatsappmessage_status = 'sent';
        $this->message->whatsappmessage_external_id = $responseData['key']['id'] ?? ('evolution_' . time());
        $this->message->save();
    }

    /**
     * Send message via Twilio
     */
    protected function sendViaTwilio($client, $connection, $messageContent, $mediaUrl)
    {
        $accountSid = config('whatsapp.twilio.account_sid');
        $authToken = $connection->whatsappconnection_api_secret ?? config('whatsapp.twilio.auth_token');
        $from = $connection->whatsappconnection_from_number ?? config('whatsapp.twilio.whatsapp_number');

        $payload = [
            'From' => $from,
            'To' => 'whatsapp:' . $this->recipientPhone,
            'Body' => $messageContent
        ];

        if (!empty($mediaUrl)) {
            $payload['MediaUrl'] = [$mediaUrl];
        }

        $response = $client->post('https://api.twilio.com/2010-04-01/Accounts/' . $accountSid . '/Messages.json', [
            'auth' => [$accountSid, $authToken],
            'form_params' => $payload
        ]);

        $responseData = json_decode($response->getBody(), true);

        $this->message->whatsappmessage_status = 'sent';
        $this->message->whatsappmessage_external_id = $responseData['sid'] ?? ('twilio_' . time());
        $this->message->save();
    }

    /**
     * Send message via Meta Cloud API
     */
    protected function sendViaCloudAPI($client, $connection, $messageContent, $mediaUrl)
    {
        $accessToken = $connection->whatsappconnection_api_key ?? config('whatsapp.cloud_api.access_token');
        $phoneNumberId = $connection->whatsappconnection_phone_number_id ?? config('whatsapp.cloud_api.phone_number_id');
        $apiVersion = config('whatsapp.cloud_api.api_version', 'v18.0');

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => str_replace('+', '', $this->recipientPhone),
            'type' => empty($mediaUrl) ? 'text' : 'image'
        ];

        if (empty($mediaUrl)) {
            $payload['text'] = ['body' => $messageContent];
        } else {
            $payload['image'] = [
                'link' => $mediaUrl,
                'caption' => $messageContent
            ];
        }

        $response = $client->post('https://graph.facebook.com/' . $apiVersion . '/' . $phoneNumberId . '/messages', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload
        ]);

        $responseData = json_decode($response->getBody(), true);

        $this->message->whatsappmessage_status = 'sent';
        $this->message->whatsappmessage_external_id = $responseData['messages'][0]['id'] ?? ('meta_' . time());
        $this->message->save();
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('WhatsApp message job completely failed', [
            'message_id' => $this->message->whatsappmessage_id,
            'error' => $exception->getMessage()
        ]);

        $this->message->whatsappmessage_status = 'failed';
        $this->message->whatsappmessage_error = $exception->getMessage();
        $this->message->save();
    }
}
