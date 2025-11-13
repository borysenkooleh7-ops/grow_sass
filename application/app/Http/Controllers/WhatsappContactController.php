<?php

namespace App\Http\Controllers;

use App\Models\WhatsappContact;
use App\Models\WhatsappContactNote;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappContactController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display contacts page
     */
    public function index()
    {
        $contacts = WhatsappContact::with('client')
            ->notBlocked()
            ->orderBy('last_contact_at', 'desc')
            ->paginate(20);

        $page = $this->pageSettings('whatsapp_contacts');

        return view('pages.whatsapp.components.contacts.contacts', compact('contacts', 'page'));
    }

    /**
     * Get contacts for DataTables AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappContact::with('client');

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->search($search);
        }

        // Filter blocked
        if ($request->has('filter_blocked')) {
            if ($request->filter_blocked == '1') {
                $query->blocked();
            } else {
                $query->notBlocked();
            }
        }

        $totalRecords = $query->count();

        // Pagination
        $contacts = $query->skip($request->start ?? 0)
            ->take($request->length ?? 20)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappContact::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $contacts
        ]);
    }

    /**
     * Store a new contact
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:50|unique:whatsapp_contacts,phone_number',
            'email' => 'nullable|email|max:255',
            'avatar_url' => 'nullable|string|max:500',
            'client_id' => 'nullable|exists:clients,client_id',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'custom_fields' => 'nullable|array'
        ]);

        $contact = WhatsappContact::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'contact' => $contact
        ]);
    }

    /**
     * Display the specified contact
     */
    public function show($id)
    {
        $contact = WhatsappContact::with(['client', 'tickets', 'notes.creator'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'contact' => $contact
        ]);
    }

    /**
     * Update the specified contact
     */
    public function update(Request $request, $id)
    {
        $contact = WhatsappContact::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:50|unique:whatsapp_contacts,phone_number,' . $id,
            'email' => 'nullable|email|max:255',
            'avatar_url' => 'nullable|string|max:500',
            'client_id' => 'nullable|exists:clients,client_id',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'custom_fields' => 'nullable|array'
        ]);

        $contact->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully',
            'contact' => $contact
        ]);
    }

    /**
     * Remove the specified contact
     */
    public function destroy($id)
    {
        $contact = WhatsappContact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully'
        ]);
    }

    /**
     * Block contact
     */
    public function block(Request $request, $id)
    {
        $contact = WhatsappContact::findOrFail($id);

        $validated = $request->validate([
            'block_reason' => 'nullable|string|max:100',
            'block_notes' => 'nullable|string'
        ]);

        $contact->block(
            $validated['block_reason'] ?? null,
            $validated['block_notes'] ?? null,
            Auth::id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Contact blocked successfully'
        ]);
    }

    /**
     * Unblock contact
     */
    public function unblock($id)
    {
        $contact = WhatsappContact::findOrFail($id);
        $contact->unblock();

        return response()->json([
            'success' => true,
            'message' => 'Contact unblocked successfully'
        ]);
    }

    /**
     * Get blocked contacts
     */
    public function blocked()
    {
        $contacts = WhatsappContact::with(['blocker'])
            ->blocked()
            ->orderBy('whatsappcontact_blocked_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'contacts' => $contacts
        ]);
    }

    /**
     * Link contact to client
     */
    public function linkToClient(Request $request, $id)
    {
        $contact = WhatsappContact::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,client_id'
        ]);

        $contact->client_id = $validated['client_id'];
        $contact->save();

        return response()->json([
            'success' => true,
            'message' => 'Contact linked to client successfully',
            'contact' => $contact->load('client')
        ]);
    }

    /**
     * Unlink contact from client
     */
    public function unlinkFromClient($id)
    {
        $contact = WhatsappContact::findOrFail($id);
        $contact->client_id = null;
        $contact->save();

        return response()->json([
            'success' => true,
            'message' => 'Contact unlinked from client'
        ]);
    }

    /**
     * Add note to contact
     */
    public function addNote(Request $request, $id)
    {
        $contact = WhatsappContact::findOrFail($id);

        $validated = $request->validate([
            'note' => 'required|string'
        ]);

        $note = WhatsappContactNote::create([
            'contact_id' => $id,
            'note' => $validated['note'],
            'created_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully',
            'note' => $note->load('creator')
        ]);
    }

    /**
     * Get notes for contact
     */
    public function getNotes($id)
    {
        $notes = WhatsappContactNote::with('creator')
            ->where('contact_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'notes' => $notes
        ]);
    }

    /**
     * Delete note
     */
    public function deleteNote($contactId, $noteId)
    {
        $note = WhatsappContactNote::where('contact_id', $contactId)
            ->findOrFail($noteId);

        // Check ownership
        if ($note->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own notes'
            ], 403);
        }

        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully'
        ]);
    }

    /**
     * Export contacts
     */
    public function export(Request $request)
    {
        $query = WhatsappContact::with('client');

        // Apply filters
        if ($request->has('filter_blocked')) {
            if ($request->filter_blocked == '1') {
                $query->blocked();
            } else {
                $query->notBlocked();
            }
        }

        $contacts = $query->get();

        // Create CSV
        $filename = 'whatsapp_contacts_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/temp/' . $filename);

        // Ensure directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $file = fopen($filepath, 'w');

        // Headers
        fputcsv($file, ['Name', 'Phone', 'Email', 'Client', 'Language', 'Timezone', 'Last Contact', 'Is Blocked']);

        foreach ($contacts as $contact) {
            fputcsv($file, [
                $contact->name,
                $contact->phone_number,
                $contact->email,
                $contact->client ? $contact->client->client_company_name : 'N/A',
                $contact->language,
                $contact->timezone,
                $contact->last_contact_at ? $contact->last_contact_at->format('Y-m-d H:i:s') : 'N/A',
                $contact->is_blocked ? 'Yes' : 'No'
            ]);
        }

        fclose($file);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Import contacts
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $handle = fopen($file->path(), 'r');

        $imported = 0;
        $errors = [];

        // Skip header row
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            try {
                WhatsappContact::create([
                    'name' => $data[0],
                    'phone_number' => $data[1],
                    'email' => $data[2] ?? null,
                    'language' => $data[3] ?? null,
                    'timezone' => $data[4] ?? null
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$imported}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return response()->json([
            'success' => true,
            'message' => "{$imported} contacts imported successfully",
            'errors' => $errors
        ]);
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
                __('lang.contacts')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_contacts'),
            'meta_title' => __('lang.whatsapp_contacts'),
        ];
    }
}
