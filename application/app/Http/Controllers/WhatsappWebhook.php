<?php

/**
 * @fileoverview WhatsApp Webhook Controller
 * @description Handles incoming webhooks from WhatsApp API
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\WhatsappTicketRepository;
use App\Repositories\WhatsappMessageRepository;
use App\Repositories\WhatsappContactRepository;
use App\Services\WhatsappIntegrationService;
use App\Models\WhatsappConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappWebhook extends Controller
{
    protected $ticketrepo;
    protected $messagerepo;
    protected $contactrepo;
    protected $integrationService;

    public function __construct(
        WhatsappTicketRepository $ticketrepo,
        WhatsappMessageRepository $messagerepo,
        WhatsappContactRepository $contactrepo,
        WhatsappIntegrationService $integrationService
    ) {
        // Note: No auth middleware for webhooks
        parent::__construct();

        $this->ticketrepo = $ticketrepo;
        $this->messagerepo = $messagerepo;
        $this->contactrepo = $contactrepo;
        $this->integrationService = $integrationService;
    }

    /**
     * Main webhook handler
     * Receives all webhook events from WhatsApp API
     */
    public function handle(Request $request)
    {
        try {
            // Log incoming webhook
            Log::info('WhatsApp Webhook Received', [
                'payload' => $request->all(),
            ]);

            // Validate webhook signature (security)
            if (!$this->validateWebhookSignature($request)) {
                Log::warning('WhatsApp Webhook: Invalid signature');
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid signature',
                ], 401);
            }

            // Get event type (Evolution API format)
            $eventType = $request->input('event');

            // Also support generic event_type format
            if (!$eventType) {
                $eventType = $request->input('event_type');
            }

            // Route to appropriate handler
            switch ($eventType) {
                case 'messages.upsert':
                case 'message.incoming':
                    return $this->handleIncomingMessage($request);

                case 'messages.update':
                case 'message.status':
                    return $this->handleMessageStatus($request);

                case 'connection.update':
                    return $this->handleConnectionUpdate($request);

                default:
                    Log::info('WhatsApp Webhook: Unknown event type', [
                        'event_type' => $eventType,
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Event type not handled',
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Webhook Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * Handle incoming message from customer
     */
    private function handleIncomingMessage(Request $request)
    {
        $data = $request->input('data', []);

        // Evolution API sends data in a nested structure
        $messageData = $data['message'] ?? $data;
        $instanceData = $request->input('instance') ?? $request->input('data.instance');

        // Extract required fields (Evolution API format)
        $instanceId = $instanceData ?? $request->input('instance');
        $from = $messageData['key']['remoteJid'] ?? $data['from'] ?? null;
        $messageContent = $messageData['message']['conversation'] ??
                         $messageData['message']['extendedTextMessage']['text'] ??
                         $data['message'] ?? '';
        $messageType = isset($messageData['message']['imageMessage']) ? 'image' :
                      (isset($messageData['message']['videoMessage']) ? 'video' :
                      (isset($messageData['message']['audioMessage']) ? 'audio' :
                      (isset($messageData['message']['documentMessage']) ? 'document' : 'text')));
        $externalId = $messageData['key']['id'] ?? $data['message_id'] ?? null;
        $senderName = $messageData['pushName'] ?? $data['sender_name'] ?? null;
        $mediaUrl = $data['media_url'] ?? null;

        if (!$instanceId || !$from || !$externalId) {
            return response()->json([
                'success' => false,
                'error' => 'Missing required fields',
            ], 400);
        }

        // Find connection by instance ID
        $connection = WhatsappConnection::where('whatsappconnection_instance_id', $instanceId)->first();

        if (!$connection) {
            Log::warning('WhatsApp Webhook: Connection not found', [
                'instance_id' => $instanceId,
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Connection not found',
            ], 404);
        }

        // Find or create contact using integration service
        $contact = $this->integrationService->getOrCreateContact(
            $from,
            $senderName,
            $connection->whatsappconnection_id
        );

        // Prepare ticket data
        $ticketData = [
            'contactid' => $contact->whatsappcontact_id,
            'connectionid' => $connection->whatsappconnection_id,
            'subject' => 'WhatsApp from ' . ($senderName ?? $from),
            'status' => 'on_hold'
        ];

        // Prepare message data
        $messageData = [
            'type' => $messageType,
            'content' => $messageContent,
            'body' => $messageContent,
            'external_id' => $externalId,
            'message_id' => $externalId,
            'media_url' => $mediaUrl,
            'media_filename' => $mediaUrl ? basename($mediaUrl) : null,
            'media_mime' => $data['mime_type'] ?? null,
            'media_size' => $data['file_size'] ?? null
        ];

        // Use integration service to handle incoming message and fire events
        $result = $this->integrationService->handleIncomingMessage($messageData, $ticketData);

        if (!$result['success']) {
            return response()->json($result, 500);
        }

        $ticket = $result['ticket'];
        $message = $result['message'];

        // Send auto-reply if configured (this will also dispatch job and fire events)
        $this->sendAutoReply($connection, $ticket, $contact);

        return response()->json([
            'success' => true,
            'message' => 'Message processed successfully',
            'ticket_id' => $ticket->whatsappticket_id,
            'message_id' => $message->whatsappmessage_id
        ]);
    }

    /**
     * Handle message status updates (sent, delivered, read)
     */
    private function handleMessageStatus(Request $request)
    {
        $data = $request->input('data', []);

        $externalId = $data['message_id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$externalId || !$status) {
            return response()->json([
                'success' => false,
                'error' => 'Missing required fields',
            ], 400);
        }

        // Map webhook status to our status
        $statusMap = [
            'sent' => 'sent',
            'delivered' => 'delivered',
            'read' => 'read',
            'failed' => 'failed',
        ];

        $mappedStatus = $statusMap[$status] ?? null;

        if (!$mappedStatus) {
            return response()->json([
                'success' => true,
                'message' => 'Status not mapped',
            ]);
        }

        // Update message status
        $result = $this->messagerepo->updateStatus($externalId, $mappedStatus);

        return response()->json([
            'success' => $result,
            'message' => 'Status updated',
        ]);
    }

    /**
     * Handle connection status updates
     */
    private function handleConnectionUpdate(Request $request)
    {
        $data = $request->input('data', []);

        $instanceId = $data['instance_id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$instanceId || !$status) {
            return response()->json([
                'success' => false,
                'error' => 'Missing required fields',
            ], 400);
        }

        // Find connection
        $connection = WhatsappConnection::where('whatsappconnection_instance_id', $instanceId)->first();

        if (!$connection) {
            return response()->json([
                'success' => false,
                'error' => 'Connection not found',
            ], 404);
        }

        // Update connection status
        $connection->whatsappconnection_status = $status;

        if ($status === 'connected') {
            $connection->whatsappconnection_last_connected = now();
        }

        $connection->save();

        return response()->json([
            'success' => true,
            'message' => 'Connection status updated',
        ]);
    }

    /**
     * Validate webhook signature
     * This ensures the webhook is actually from your WhatsApp API provider
     */
    private function validateWebhookSignature(Request $request)
    {
        // Get signature from header
        $signature = $request->header('X-Webhook-Signature');

        if (!$signature) {
            // For development, you might want to skip validation
            // In production, always validate!
            return config('app.env') === 'local';
        }

        // Get instance ID or connection identifier
        $instanceId = $request->input('data.instance_id');

        if (!$instanceId) {
            return false;
        }

        // Find connection to get webhook secret
        $connection = WhatsappConnection::where('whatsappconnection_instance_id', $instanceId)->first();

        if (!$connection || empty($connection->whatsappconnection_webhook_secret)) {
            return false;
        }

        // Calculate expected signature
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $connection->whatsappconnection_webhook_secret);

        // Compare signatures
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Send auto-reply message if configured
     */
    private function sendAutoReply($connection, $ticket, $contact)
    {
        if (!$connection->lineConfig) {
            return;
        }

        // Check if this is the first message from this contact
        $messageCount = \App\Models\WhatsappMessage::where('whatsappmessage_ticketid', $ticket->whatsappticket_id)
            ->count();

        if ($messageCount > 1) {
            // Not first message, don't send auto-reply
            return;
        }

        // Get appropriate message (welcome or away)
        $autoReplyMessage = $connection->lineConfig->getAppropriateMessage();

        if (empty($autoReplyMessage)) {
            return;
        }

        // Use integration service to send auto-reply with job dispatch
        $messageData = [
            'content' => $autoReplyMessage,
            'body' => $autoReplyMessage,
            'channel' => 'whatsapp',
            'type' => 'text'
        ];

        $result = $this->integrationService->handleOutgoingMessage(
            $messageData,
            $ticket->whatsappticket_id,
            true // Dispatch job for async sending
        );

        if ($result['success']) {
            Log::info('WhatsApp Auto-Reply Queued', [
                'ticket_id' => $ticket->whatsappticket_id,
                'message_id' => $result['message']->whatsappmessage_id
            ]);
        }
    }
}
