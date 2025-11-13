<?php

/**
 * @fileoverview WhatsApp Controller
 * @description Handles WhatsApp conversation panel and messaging
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\WhatsappTicketRepository;
use App\Repositories\WhatsappMessageRepository;
use App\Repositories\WhatsappContactRepository;
use App\Services\WhatsappIntegrationService;
use App\Models\WhatsappTicket;
use App\Models\WhatsappMessage;
use App\Models\WhatsappContact;
use Illuminate\Http\Request;

class Whatsapp extends Controller
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
        parent::__construct();

        $this->middleware('auth');

        $this->ticketrepo = $ticketrepo;
        $this->messagerepo = $messagerepo;
        $this->contactrepo = $contactrepo;
        $this->integrationService = $integrationService;
    }

    /**
     * Get conversation for a task ID
     * Called when WhatsApp button is clicked in task list
     */
    public function getConversation($taskId)
    {
        // Find ticket by task ID
        $ticket = WhatsappTicket::where('whatsappticket_taskid', $taskId)
            ->with(['contact', 'connection', 'assignedAgent', 'ticketType', 'client'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'error' => 'WhatsApp ticket not found for this task',
            ], 404);
        }

        // Get messages
        $messages = $this->messagerepo->getByTicket($ticket->whatsappticket_id);

        // Mark as read
        $this->messagerepo->markTicketAsRead($ticket->whatsappticket_id);

        // Render views
        $messagesHtml = view('pages.whatsapp.components.messages', [
            'messages' => $messages,
            'ticket' => $ticket,
        ])->render();

        $headerHtml = view('pages.whatsapp.components.conversation-header', [
            'ticket' => $ticket,
            'contact' => $ticket->contact,
        ])->render();

        return response()->json([
            'success' => true,
            'ticket' => $ticket,
            'messages_html' => $messagesHtml,
            'header_html' => $headerHtml,
            'within_24h_window' => $ticket->isWithin24HourWindow(),
        ]);
    }

    /**
     * Send a message (WhatsApp or Email)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|integer',
            'content' => 'required|string',
            'channel' => 'required|in:whatsapp,email',
        ]);

        $ticket = WhatsappTicket::find($request->ticket_id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'error' => 'Ticket not found',
            ], 404);
        }

        // Prepare message data for integration service
        $messageData = [
            'body' => $request->content,
            'sender_type' => 'agent',
            'sender_id' => auth()->id(),
            'sender_name' => auth()->user()->first_name ?? 'Agent',
            'channel' => $request->channel,
            'is_internal_note' => $request->is_internal_note ?? false,
        ];

        // Use integration service for job dispatching and event firing
        $shouldDispatchJob = ($request->channel === 'whatsapp' && !$request->is_internal_note);
        $result = $this->integrationService->handleOutgoingMessage(
            $messageData,
            $ticket->whatsappticket_id,
            $shouldDispatchJob
        );

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        $message = $result['message'];

        // Mark first response if this is the first agent reply
        if (!$ticket->whatsappticket_first_response_at) {
            $this->integrationService->markFirstResponse($ticket->whatsappticket_id);
        }

        // Render message HTML
        $messageHtml = view('pages.whatsapp.components.message-item', [
            'message' => $message,
            'ticket' => $ticket,
        ])->render();

        return response()->json([
            'success' => true,
            'message' => $message,
            'message_html' => $messageHtml,
            'info' => $shouldDispatchJob ? 'Message queued for sending' : 'Message stored'
        ]);
    }

    /**
     * Assign ticket to user
     */
    public function assignTicket(Request $request, $ticketId)
    {
        $request->validate([
            'user_id' => 'required|integer',
        ]);

        try {
            // Use integration service to fire events
            $ticket = $this->integrationService->assignTicket($ticketId, $request->user_id);

            return response()->json([
                'success' => true,
                'message' => 'Ticket assigned successfully',
                'ticket' => $ticket
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to assign ticket', [
                'ticket_id' => $ticketId,
                'user_id' => $request->user_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to assign ticket: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $ticketId)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        try {
            // Use integration service to fire events
            $ticket = $this->integrationService->updateTicketStatus($ticketId, $request->status);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'ticket' => $ticket
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to update ticket status', [
                'ticket_id' => $ticketId,
                'status' => $request->status,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to update status: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Open composer for a client (from client profile)
     */
    public function openComposerForClient($clientId)
    {
        // Find contact linked to this client
        $contact = WhatsappContact::where('whatsappcontact_clientid', $clientId)->first();

        if (!$contact) {
            return response()->json([
                'success' => false,
                'error' => 'No WhatsApp contact found for this client',
            ], 404);
        }

        // Find or create ticket
        $ticket = $this->ticketrepo->findOrCreateForContact(
            $contact->whatsappcontact_id,
            $contact->whatsappcontact_connectionid
        );

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create conversation',
            ], 500);
        }

        // Redirect to conversation
        return response()->json([
            'success' => true,
            'task_id' => $ticket->whatsappticket_taskid,
        ]);
    }

    /**
     * Poll for new messages (real-time updates)
     */
    public function pollMessages(Request $request, $taskId)
    {
        $request->validate([
            'last_message_id' => 'required|integer',
        ]);

        // Find ticket
        $ticket = WhatsappTicket::where('whatsappticket_taskid', $taskId)->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'error' => 'Ticket not found',
            ], 404);
        }

        // Get new messages
        $newMessages = $this->messagerepo->getNewMessages(
            $ticket->whatsappticket_id,
            $request->last_message_id
        );

        if ($newMessages->isEmpty()) {
            return response()->json([
                'success' => true,
                'has_new_messages' => false,
                'count' => 0,
            ]);
        }

        // Render messages
        $messagesHtml = '';
        foreach ($newMessages as $message) {
            $messagesHtml .= view('pages.whatsapp.components.message-item', [
                'message' => $message,
                'ticket' => $ticket,
            ])->render();
        }

        return response()->json([
            'success' => true,
            'has_new_messages' => true,
            'count' => $newMessages->count(),
            'messages_html' => $messagesHtml,
            'last_message_id' => $newMessages->last()->whatsappmessage_id,
        ]);
    }

    /**
     * Upload file for message
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:16384', // 16MB max
            'ticket_id' => 'required|integer',
        ]);

        $ticket = WhatsappTicket::find($request->ticket_id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'error' => 'Ticket not found',
            ], 404);
        }

        // Handle file upload
        $uploadResult = $this->messagerepo->handleFileUpload($request->file('file'));

        if (!$uploadResult['success']) {
            return response()->json($uploadResult, 400);
        }

        // Prepare message data with file attachment
        $messageData = [
            'body' => 'Sent a file: ' . $uploadResult['media_filename'],
            'sender_type' => 'agent',
            'sender_id' => auth()->id(),
            'sender_name' => auth()->user()->first_name ?? 'Agent',
            'channel' => 'whatsapp',
            'attachments' => [
                [
                    'type' => $uploadResult['type'],
                    'url' => $uploadResult['media_url'],
                    'filename' => $uploadResult['media_filename'],
                    'mime' => $uploadResult['media_mime'],
                    'size' => $uploadResult['media_size']
                ]
            ]
        ];

        // Use integration service for async sending
        $result = $this->integrationService->handleOutgoingMessage(
            $messageData,
            $ticket->whatsappticket_id,
            true // Dispatch job for async sending
        );

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        $message = $result['message'];

        // Render message HTML
        $messageHtml = view('pages.whatsapp.components.message-item', [
            'message' => $message,
            'ticket' => $ticket,
        ])->render();

        return response()->json([
            'success' => true,
            'message' => $message,
            'message_html' => $messageHtml,
            'info' => 'File upload queued for sending'
        ]);
    }

    /**
     * Link WhatsApp contact to client
     */
    public function linkContact(Request $request, $contactId)
    {
        $request->validate([
            'client_id' => 'required|integer',
        ]);

        $result = $this->contactrepo->linkToClient($contactId, $request->client_id);

        return response()->json($result);
    }

    /**
     * Find or create contact
     */
    public function findOrCreateContact(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'connection_id' => 'required|integer',
            'name' => 'nullable|string',
        ]);

        $contact = $this->contactrepo->findOrCreate(
            $request->phone,
            $request->connection_id,
            $request->name
        );

        if ($contact) {
            return response()->json([
                'success' => true,
                'contact' => $contact,
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Failed to create contact',
        ], 500);
    }
}
