<?php

namespace App\Http\Controllers;

use App\Models\WhatsappBroadcast;
use App\Models\WhatsappBroadcastRecipient;
use App\Models\WhatsappConnection;
use App\Models\WhatsappTemplate;
use App\Models\WhatsappContact;
use App\Models\Client;
use App\Http\Responses\WhatsappBroadcasts\CreateResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WhatsappBroadcastController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display broadcasts page
     */
    public function index()
    {
        $broadcasts = WhatsappBroadcast::with(['connection', 'template', 'creator'])
            ->orderBy('whatsappbroadcast_created', 'desc')
            ->paginate(20);

        // Get active connections
        $connections = WhatsappConnection::where('whatsappconnection_is_active', 1)
            ->orderBy('whatsappconnection_created', 'desc')
            ->get();

        // Get active templates using the scope method
        $templates = WhatsappTemplate::active()->get();

        $page = $this->pageSettings('whatsapp_broadcasts');

        return view('pages.whatsapp.components.broadcast.broadcasts', compact('broadcasts', 'connections', 'templates', 'page'));
    }

    /**
     * Get broadcasts for DataTables AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappBroadcast::with(['connection', 'template', 'creator']);

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where('whatsappbroadcast_name', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('filter_status') && $request->filter_status) {
            $query->where('whatsappbroadcast_status', $request->filter_status);
        }

        // Filter by connection
        if ($request->has('filter_connection') && $request->filter_connection) {
            $query->where('whatsappbroadcast_connection_id', $request->filter_connection);
        }

        $totalRecords = $query->count();

        // Pagination
        $broadcasts = $query->skip($request->start ?? 0)
            ->take($request->length ?? 20)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappBroadcast::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $broadcasts
        ]);
    }

    /**
     * Store a new broadcast
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'broadcast_name' => 'required|string|max:255',
            'broadcast_message' => 'required|string',
            'recipient_type' => 'required|in:all_contacts,clients,specific_clients,tags,custom',
            'recipient_data' => 'nullable|array',
            'broadcast_connection_id' => 'required|exists:whatsapp_connections,whatsappconnection_id',
            'template_id' => 'nullable|exists:whatsapp_templates,whatsapptemplatemain_id',
            'broadcast_attachments' => 'nullable|array',
            'schedule_datetime' => 'nullable|date|after:now'
        ]);

        // Map form fields to database columns
        $broadcastData = [
            'whatsappbroadcast_uniqueid' => \Illuminate\Support\Str::uuid(),
            'whatsappbroadcast_name' => $validated['broadcast_name'],
            'whatsappbroadcast_message' => $validated['broadcast_message'],
            'whatsappbroadcast_recipient_type' => $validated['recipient_type'],
            'whatsappbroadcast_recipient_data' => json_encode($validated['recipient_data'] ?? []),
            'whatsappbroadcast_connection_id' => $validated['broadcast_connection_id'],
            'whatsappbroadcast_template_id' => $validated['template_id'] ?? null,
            'whatsappbroadcast_attachments' => json_encode($validated['broadcast_attachments'] ?? []),
            'whatsappbroadcast_scheduled_at' => $validated['schedule_datetime'] ?? null,
            'whatsappbroadcast_created_by' => Auth::id(),
            'whatsappbroadcast_status' => isset($validated['schedule_datetime']) ? 'scheduled' : 'draft',
        ];

        // Calculate total recipients
        $recipients = $this->getRecipients($validated['recipient_type'], $validated['recipient_data'] ?? []);
        $broadcastData['whatsappbroadcast_total_recipients'] = count($recipients);

        DB::beginTransaction();

        try {
            $broadcast = WhatsappBroadcast::create($broadcastData);

            // Create recipient records
            foreach ($recipients as $recipient) {
                WhatsappBroadcastRecipient::create([
                    'whatsappbroadcastrecipient_uniqueid' => \Illuminate\Support\Str::uuid(),
                    'whatsappbroadcastrecipient_broadcast_id' => $broadcast->whatsappbroadcast_id,
                    'whatsappbroadcastrecipient_phone_number' => $recipient['phone'],
                    'whatsappbroadcastrecipient_contact_name' => $recipient['name'],
                    'whatsappbroadcastrecipient_status' => 'pending'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Broadcast created successfully',
                'broadcast' => $broadcast
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create broadcast: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get necessary data for modal
        $connections = WhatsappConnection::where('whatsappconnection_is_active', 1)
            ->orderBy('whatsappconnection_created', 'desc')
            ->get();
        $templates = WhatsappTemplate::active()->get();
        $clients = Client::select('client_id', 'client_company_name')->get();
        $tags = DB::table('tags')->select('tag_title')->distinct()->get();

        $url = route('whatsapp.broadcasts.store');

        // Response payload
        $payload = [
            'url' => $url,
            'connections' => $connections,
            'templates' => $templates,
            'clients' => $clients,
            'tags' => $tags,
        ];

        // Return modal using Response class
        return new CreateResponse($payload);
    }

    /**
     * Display the specified broadcast
     */
    public function show($id)
    {
        $broadcast = WhatsappBroadcast::with(['connection', 'template', 'creator', 'recipients'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'broadcast' => $broadcast
        ]);
    }

    /**
     * Show edit form for broadcast
     */
    public function edit($id)
    {
        $broadcast = WhatsappBroadcast::findOrFail($id);
        $url = route('whatsapp.broadcasts.update', $id);

        return view('pages.whatsapp.components.modals.broadcast', compact('broadcast', 'url'));
    }

    /**
     * Update the specified broadcast
     */
    public function update(Request $request, $id)
    {
        $broadcast = WhatsappBroadcast::findOrFail($id);

        // Only draft broadcasts can be edited
        if ($broadcast->whatsappbroadcast_status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft broadcasts can be edited'
            ], 400);
        }

        $validated = $request->validate([
            'broadcast_name' => 'required|string|max:255',
            'broadcast_message' => 'required|string',
            'recipient_type' => 'required|in:all_contacts,clients,specific_clients,tags,custom',
            'recipient_data' => 'nullable|array',
            'broadcast_connection_id' => 'required|exists:whatsapp_connections,whatsappconnection_id',
            'template_id' => 'nullable|exists:whatsapp_templates,whatsapptemplatemain_id',
            'broadcast_attachments' => 'nullable|array',
            'schedule_datetime' => 'nullable|date|after:now'
        ]);

        // Map form fields to database columns
        $broadcastData = [
            'whatsappbroadcast_name' => $validated['broadcast_name'],
            'whatsappbroadcast_message' => $validated['broadcast_message'],
            'whatsappbroadcast_recipient_type' => $validated['recipient_type'],
            'whatsappbroadcast_recipient_data' => json_encode($validated['recipient_data'] ?? []),
            'whatsappbroadcast_connection_id' => $validated['broadcast_connection_id'],
            'whatsappbroadcast_template_id' => $validated['template_id'] ?? null,
            'whatsappbroadcast_attachments' => json_encode($validated['broadcast_attachments'] ?? []),
            'whatsappbroadcast_scheduled_at' => $validated['schedule_datetime'] ?? null,
            'whatsappbroadcast_status' => isset($validated['schedule_datetime']) ? 'scheduled' : 'draft',
        ];

        // Recalculate total recipients
        $recipients = $this->getRecipients($validated['recipient_type'], $validated['recipient_data'] ?? []);
        $broadcastData['whatsappbroadcast_total_recipients'] = count($recipients);

        DB::beginTransaction();

        try {
            $broadcast->update($broadcastData);

            // Delete old recipients and create new ones
            $broadcast->recipients()->delete();

            foreach ($recipients as $recipient) {
                WhatsappBroadcastRecipient::create([
                    'whatsappbroadcastrecipient_uniqueid' => \Illuminate\Support\Str::uuid(),
                    'whatsappbroadcastrecipient_broadcast_id' => $broadcast->whatsappbroadcast_id,
                    'whatsappbroadcastrecipient_phone_number' => $recipient['phone'],
                    'whatsappbroadcastrecipient_contact_name' => $recipient['name'],
                    'whatsappbroadcastrecipient_status' => 'pending'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Broadcast updated successfully',
                'broadcast' => $broadcast
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update broadcast: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified broadcast
     */
    public function destroy($id)
    {
        $broadcast = WhatsappBroadcast::findOrFail($id);

        // Can't delete if sending
        if ($broadcast->whatsappbroadcast_status === 'sending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete broadcast while sending'
            ], 400);
        }

        $broadcast->delete();

        return response()->json([
            'success' => true,
            'message' => 'Broadcast deleted successfully'
        ]);
    }

    /**
     * Send broadcast immediately
     */
    public function send($id)
    {
        $broadcast = WhatsappBroadcast::findOrFail($id);

        if (!in_array($broadcast->whatsappbroadcast_status, ['draft', 'scheduled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Broadcast cannot be sent'
            ], 400);
        }

        // Update status to sending
        $broadcast->whatsappbroadcast_status = 'sending';
        $broadcast->whatsappbroadcast_started_at = now();
        $broadcast->save();

        // Dispatch job for async processing
        dispatch(new \App\Jobs\SendWhatsappBroadcastJob($broadcast));

        \Log::info('Broadcast job dispatched', [
            'broadcast_id' => $broadcast->whatsappbroadcast_id,
            'total_recipients' => $broadcast->whatsappbroadcast_total_recipients
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Broadcast sending started. Messages will be sent in background.',
            'broadcast' => $broadcast
        ]);
    }

    /**
     * Cancel broadcast
     */
    public function cancel($id)
    {
        $broadcast = WhatsappBroadcast::findOrFail($id);

        if ($broadcast->whatsappbroadcast_status !== 'sending') {
            return response()->json([
                'success' => false,
                'message' => 'Broadcast is not currently sending'
            ], 400);
        }

        $broadcast->whatsappbroadcast_status = 'cancelled';
        $broadcast->save();

        return response()->json([
            'success' => true,
            'message' => 'Broadcast cancelled'
        ]);
    }

    /**
     * Get broadcast statistics
     */
    public function statistics($id)
    {
        $broadcast = WhatsappBroadcast::findOrFail($id);

        return response()->json([
            'success' => true,
            'statistics' => [
                'total_recipients' => $broadcast->whatsappbroadcast_total_recipients,
                'sent_count' => $broadcast->whatsappbroadcast_sent_count,
                'delivered_count' => $broadcast->whatsappbroadcast_delivered_count,
                'read_count' => $broadcast->whatsappbroadcast_read_count,
                'failed_count' => $broadcast->whatsappbroadcast_failed_count,
                'delivery_rate' => $broadcast->whatsappbroadcast_delivery_rate,
                'read_rate' => $broadcast->whatsappbroadcast_read_rate,
                'failure_rate' => $broadcast->whatsappbroadcast_failure_rate
            ]
        ]);
    }

    /**
     * Get recipients for a broadcast type
     */
    private function getRecipients($type, $data)
    {
        $recipients = [];

        switch ($type) {
            case 'all_contacts':
                $contacts = WhatsappContact::where('whatsappcontact_blocked', 0)->get();
                foreach ($contacts as $contact) {
                    $recipients[] = [
                        'phone' => $contact->whatsappcontact_phone,
                        'name' => $contact->whatsappcontact_name
                    ];
                }
                break;

            case 'clients':
                $clients = Client::all();
                foreach ($clients as $client) {
                    if ($client->client_phone) {
                        $recipients[] = [
                            'phone' => $client->client_phone,
                            'name' => $client->client_company_name ?? $client->client_first_name
                        ];
                    }
                }
                break;

            case 'specific_clients':
                if (isset($data['client_ids'])) {
                    $clients = Client::whereIn('client_id', $data['client_ids'])->get();
                    foreach ($clients as $client) {
                        if ($client->client_phone) {
                            $recipients[] = [
                                'phone' => $client->client_phone,
                                'name' => $client->client_company_name ?? $client->client_first_name
                            ];
                        }
                    }
                }
                break;

            case 'custom':
                if (isset($data['contacts'])) {
                    foreach ($data['contacts'] as $contact) {
                        $recipients[] = [
                            'phone' => $contact['phone'],
                            'name' => $contact['name'] ?? 'Unknown'
                        ];
                    }
                }
                break;
        }

        return $recipients;
    }

    /**
     * Page settings helper
     */
    private function pageSettings($page_type)
    {
        return [
            'page' => $page_type,
            'crumbs' => [
                __('lang.whatsapp'),
                __('lang.broadcasts')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_broadcasts'),
            'meta_title' => __('lang.whatsapp_broadcasts'),
        ];
    }
}
