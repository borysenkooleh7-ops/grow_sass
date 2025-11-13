<?php

namespace App\Http\Controllers;

use App\Models\WhatsappTemplate;
use App\Http\Responses\WhatsappTemplates\CreateResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappTemplateController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of templates
     */
    public function index()
    {
        $templates = WhatsappTemplate::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $page = $this->pageSettings('whatsapp_templates');

        return view('pages.whatsapp.components.templates.templates', compact('templates', 'page'));
    }

    /**
     * Show create form for new template
     */
    public function create()
    {
        // Initialize empty template object with database column names
        $template = (object) [
            'whatsapptemplatemain_title' => '',
            'whatsapptemplatemain_category' => '',
            'whatsapptemplatemain_message' => '',
            'whatsapptemplatemain_language' => 'en',
            'whatsapptemplatemain_buttons' => [],
            'whatsapptemplatemain_is_active' => true
        ];

        // URL for form submission
        $url = route('whatsapp.templates.store');

        // Response payload
        $payload = [
            'template' => $template,
            'url' => $url,
        ];

        // Return modal using Response class
        return new CreateResponse($payload);
    }

    /**
     * Get templates for DataTables AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappTemplate::with('creator');

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('whatsapptemplatemain_title', 'like', "%{$search}%")
                  ->orWhere('whatsapptemplatemain_message', 'like', "%{$search}%")
                  ->orWhere('whatsapptemplatemain_category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('filter_category') && $request->filter_category) {
            $query->where('whatsapptemplatemain_category', $request->filter_category);
        }

        // Filter by status
        if ($request->has('filter_status') && $request->filter_status !== '') {
            $query->where('whatsapptemplatemain_is_active', $request->filter_status);
        }

        $totalRecords = $query->count();

        // Pagination
        $templates = $query->skip($request->start ?? 0)
            ->take($request->length ?? 20)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappTemplate::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $templates
        ]);
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:general,marketing,notification,support,transactional',
            'message' => 'required|string',
            'language' => 'nullable|string|max:10',
            'buttons' => 'nullable|array',
            'variables' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $template = WhatsappTemplate::create([
            'whatsapptemplatemain_uniqueid' => str()->uuid(),
            'whatsapptemplatemain_title' => $validated['title'],
            'whatsapptemplatemain_category' => $validated['category'],
            'whatsapptemplatemain_message' => $validated['message'],
            'whatsapptemplatemain_language' => $validated['language'] ?? 'en',
            'whatsapptemplatemain_buttons' => $validated['buttons'] ?? null,
            'whatsapptemplatemain_variables' => $validated['variables'] ?? null,
            'whatsapptemplatemain_is_active' => $request->has('is_active') ? 1 : 0,
            'whatsapptemplatemain_created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template created successfully',
            'template' => $template
        ]);
    }

    /**
     * Display the specified template
     */
    public function show($id)
    {
        $template = WhatsappTemplate::with('creator')->findOrFail($id);

        return response()->json([
            'success' => true,
            'template' => $template
        ]);
    }

    /**
     * Show edit form for template
     */
    public function edit($id)
    {
        $template = WhatsappTemplate::findOrFail($id);

        // Return modal content
        $url = route('whatsapp.templates.update', $id);

        return view('pages.whatsapp.components.modals.add-template', compact('template', 'url'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, $id)
    {
        $template = WhatsappTemplate::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:general,marketing,notification,support,transactional',
            'message' => 'required|string',
            'language' => 'nullable|string|max:10',
            'buttons' => 'nullable|array',
            'variables' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $template->update([
            'whatsapptemplatemain_title' => $validated['title'],
            'whatsapptemplatemain_category' => $validated['category'],
            'whatsapptemplatemain_message' => $validated['message'],
            'whatsapptemplatemain_language' => $validated['language'] ?? 'en',
            'whatsapptemplatemain_buttons' => $validated['buttons'] ?? null,
            'whatsapptemplatemain_variables' => $validated['variables'] ?? null,
            'whatsapptemplatemain_is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully',
            'template' => $template
        ]);
    }

    /**
     * Remove the specified template
     */
    public function destroy($id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully'
        ]);
    }

    /**
     * Bulk delete templates
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:whatsapp_templates,whatsapptemplatemain_id'
        ]);

        WhatsappTemplate::whereIn('whatsapptemplatemain_id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' templates deleted successfully'
        ]);
    }

    /**
     * Toggle template status
     */
    public function toggleStatus($id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        $template->whatsapptemplatemain_is_active = !$template->whatsapptemplatemain_is_active;
        $template->save();

        return response()->json([
            'success' => true,
            'message' => 'Template status updated',
            'is_active' => $template->whatsapptemplatemain_is_active
        ]);
    }

    /**
     * Duplicate template
     */
    public function duplicate($id)
    {
        $template = WhatsappTemplate::findOrFail($id);

        $newTemplate = $template->replicate();
        $newTemplate->whatsapptemplatemain_uniqueid = str()->uuid();
        $newTemplate->whatsapptemplatemain_title = $template->whatsapptemplatemain_title . ' (Copy)';
        $newTemplate->whatsapptemplatemain_created_by = Auth::id();
        $newTemplate->save();

        return response()->json([
            'success' => true,
            'message' => 'Template duplicated successfully',
            'template' => $newTemplate
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
                __('lang.templates')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_templates'),
            'meta_title' => __('lang.whatsapp_templates'),
        ];
    }
}
