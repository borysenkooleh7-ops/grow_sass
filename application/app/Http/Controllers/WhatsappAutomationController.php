<?php

namespace App\Http\Controllers;

use App\Models\WhatsappAutomationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappAutomationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display automation rules page
     */
    public function index()
    {
        $rules = WhatsappAutomationRule::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $page = $this->pageSettings('whatsapp_automation');

        return view('pages.whatsapp.components.automation.automation-rules', compact('rules', 'page'));
    }

    /**
     * Get rules for DataTables AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappAutomationRule::with('creator');

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by trigger type
        if ($request->has('filter_trigger') && $request->filter_trigger) {
            $query->where('trigger_type', $request->filter_trigger);
        }

        // Filter by status
        if ($request->has('filter_status') && $request->filter_status !== '') {
            $query->where('is_active', $request->filter_status);
        }

        $totalRecords = $query->count();

        // Pagination
        $rules = $query->skip($request->start ?? 0)
            ->take($request->length ?? 20)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappAutomationRule::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $rules
        ]);
    }

    /**
     * Store a new automation rule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => 'required|in:new_message,new_ticket,ticket_status_change,keyword_match,business_hours,no_response,ticket_assigned',
            'trigger_conditions' => 'nullable|array',
            'actions' => 'required|array',
            'is_active' => 'boolean',
            'stop_processing' => 'boolean'
        ]);

        $rule = WhatsappAutomationRule::create([
            'whatsappautomationrule_uniqueid' => str()->uuid(),
            'whatsappautomationrule_name' => $validated['name'],
            'whatsappautomationrule_description' => $validated['description'] ?? null,
            'whatsappautomationrule_trigger_type' => $validated['trigger_type'],
            'whatsappautomationrule_trigger_conditions' => $validated['trigger_conditions'] ?? null,
            'whatsappautomationrule_actions' => $validated['actions'],
            'whatsappautomationrule_is_active' => $request->has('is_active') ? 1 : 0,
            'whatsappautomationrule_stop_processing' => $request->has('stop_processing') ? 1 : 0,
            'whatsappautomationrule_created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Automation rule created successfully',
            'rule' => $rule
        ]);
    }

    /**
     * Display the specified rule
     */
    public function show($id)
    {
        $rule = WhatsappAutomationRule::with('creator')->findOrFail($id);

        return response()->json([
            'success' => true,
            'rule' => $rule
        ]);
    }

    /**
     * Update the specified rule
     */
    public function update(Request $request, $id)
    {
        $rule = WhatsappAutomationRule::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => 'required|in:new_message,new_ticket,ticket_status_change,keyword_match,business_hours,no_response,ticket_assigned',
            'trigger_conditions' => 'nullable|array',
            'actions' => 'required|array',
            'is_active' => 'boolean',
            'stop_processing' => 'boolean'
        ]);

        $rule->update([
            'whatsappautomationrule_name' => $validated['name'],
            'whatsappautomationrule_description' => $validated['description'] ?? null,
            'whatsappautomationrule_trigger_type' => $validated['trigger_type'],
            'whatsappautomationrule_trigger_conditions' => $validated['trigger_conditions'] ?? null,
            'whatsappautomationrule_actions' => $validated['actions'],
            'whatsappautomationrule_is_active' => $request->has('is_active') ? 1 : 0,
            'whatsappautomationrule_stop_processing' => $request->has('stop_processing') ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Automation rule updated successfully',
            'rule' => $rule
        ]);
    }

    /**
     * Remove the specified rule
     */
    public function destroy($id)
    {
        $rule = WhatsappAutomationRule::findOrFail($id);
        $rule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Automation rule deleted successfully'
        ]);
    }

    /**
     * Toggle rule status
     */
    public function toggleStatus($id)
    {
        $rule = WhatsappAutomationRule::findOrFail($id);
        $rule->whatsappautomationrule_is_active = !$rule->whatsappautomationrule_is_active;
        $rule->save();

        return response()->json([
            'success' => true,
            'message' => 'Rule status updated',
            'is_active' => $rule->whatsappautomationrule_is_active
        ]);
    }

    /**
     * Test automation rule
     */
    public function test(Request $request, $id)
    {
        $rule = WhatsappAutomationRule::findOrFail($id);

        $testData = $request->validate([
            'test_data' => 'required|array'
        ]);

        $matches = $rule->matchesConditions($testData['test_data']);

        return response()->json([
            'success' => true,
            'matches' => $matches,
            'message' => $matches ? 'Rule conditions match' : 'Rule conditions do not match'
        ]);
    }

    /**
     * Get rule statistics
     */
    public function statistics($id)
    {
        $rule = WhatsappAutomationRule::findOrFail($id);

        return response()->json([
            'success' => true,
            'statistics' => [
                'triggered_count' => $rule->whatsappautomationrule_triggered_count,
                'last_triggered_at' => $rule->whatsappautomationrule_last_triggered_at,
                'is_active' => $rule->whatsappautomationrule_is_active
            ]
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $url = route('whatsapp.automation.store');
        return view('pages.whatsapp.components.modals.add-edit-inc', compact('url'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $rule = WhatsappAutomationRule::findOrFail($id);
        $url = route('whatsapp.automation.update', $id);

        return view('pages.whatsapp.components.modals.add-edit-inc', compact('rule', 'url'));
    }

    /**
     * Duplicate automation rule
     */
    public function duplicate($id)
    {
        $rule = WhatsappAutomationRule::findOrFail($id);

        $newRule = $rule->replicate();
        $newRule->whatsappautomationrule_title = $rule->whatsappautomationrule_title . ' (Copy)';
        $newRule->whatsappautomationrule_created_by = auth()->id();
        $newRule->save();

        return response()->json([
            'success' => true,
            'message' => 'Automation rule duplicated successfully',
            'rule' => $newRule
        ]);
    }

    /**
     * Get automation rule logs
     */
    public function logs($id)
    {
        $rule = WhatsappAutomationRule::findOrFail($id);

        // Get logs (implement based on your logging strategy)
        $logs = []; // Placeholder

        return response()->json([
            'success' => true,
            'logs' => $logs
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
                __('lang.automation')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_automation'),
            'meta_title' => __('lang.whatsapp_automation'),
        ];
    }
}
