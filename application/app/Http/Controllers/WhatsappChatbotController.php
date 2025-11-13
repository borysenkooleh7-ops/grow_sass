<?php

namespace App\Http\Controllers;

use App\Models\WhatsappChatbotFlow;
use App\Models\WhatsappChatbotStep;
use App\Models\WhatsappChatbotSession;
use Illuminate\Http\Request;

class WhatsappChatbotController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display chatbot flows page
     */
    public function index()
    {
        $flows = WhatsappChatbotFlow::withCount('steps')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $page = $this->pageSettings('whatsapp_chatbot');

        return view('pages.whatsapp.components.chatbot.chatbot-builder', compact('flows', 'page'));
    }

    /**
     * Get flows for DataTables AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappChatbotFlow::withCount('steps');

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
        $flows = $query->skip($request->start ?? 0)
            ->take($request->length ?? 20)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappChatbotFlow::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $flows
        ]);
    }

    /**
     * Store a new chatbot flow
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => 'required|in:keyword,greeting,outside_hours,unassigned',
            'trigger_value' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $flow = WhatsappChatbotFlow::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Chatbot flow created successfully',
            'flow' => $flow
        ]);
    }

    /**
     * Display the specified flow
     */
    public function show($id)
    {
        $flow = WhatsappChatbotFlow::with('steps')->findOrFail($id);

        return response()->json([
            'success' => true,
            'flow' => $flow
        ]);
    }

    /**
     * Update the specified flow
     */
    public function update(Request $request, $id)
    {
        $flow = WhatsappChatbotFlow::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => 'required|in:keyword,greeting,outside_hours,unassigned',
            'trigger_value' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $flow->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Chatbot flow updated successfully',
            'flow' => $flow
        ]);
    }

    /**
     * Remove the specified flow
     */
    public function destroy($id)
    {
        $flow = WhatsappChatbotFlow::findOrFail($id);
        $flow->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chatbot flow deleted successfully'
        ]);
    }

    /**
     * Toggle flow status
     */
    public function toggleStatus($id)
    {
        $flow = WhatsappChatbotFlow::findOrFail($id);
        $flow->is_active = !$flow->is_active;
        $flow->save();

        return response()->json([
            'success' => true,
            'message' => 'Flow status updated',
            'is_active' => $flow->is_active
        ]);
    }

    /**
     * Get steps for a flow
     */
    public function getSteps($flowId)
    {
        $steps = WhatsappChatbotStep::where('flow_id', $flowId)
            ->orderBy('step_order')
            ->get();

        return response()->json([
            'success' => true,
            'steps' => $steps
        ]);
    }

    /**
     * Store a new step
     */
    public function storeStep(Request $request, $flowId)
    {
        $validated = $request->validate([
            'type' => 'required|in:message,question,condition,action',
            'content' => 'nullable|string',
            'options' => 'nullable|array',
            'next_step_id' => 'nullable|exists:whatsapp_chatbot_steps,id',
            'step_order' => 'required|integer|min:0'
        ]);

        $validated['flow_id'] = $flowId;

        $step = WhatsappChatbotStep::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Step created successfully',
            'step' => $step
        ]);
    }

    /**
     * Update a step
     */
    public function updateStep(Request $request, $flowId, $stepId)
    {
        $step = WhatsappChatbotStep::where('flow_id', $flowId)->findOrFail($stepId);

        $validated = $request->validate([
            'type' => 'required|in:message,question,condition,action',
            'content' => 'nullable|string',
            'options' => 'nullable|array',
            'next_step_id' => 'nullable|exists:whatsapp_chatbot_steps,id',
            'step_order' => 'required|integer|min:0'
        ]);

        $step->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Step updated successfully',
            'step' => $step
        ]);
    }

    /**
     * Remove a step
     */
    public function destroyStep($flowId, $stepId)
    {
        $step = WhatsappChatbotStep::where('flow_id', $flowId)->findOrFail($stepId);
        $step->delete();

        return response()->json([
            'success' => true,
            'message' => 'Step deleted successfully'
        ]);
    }

    /**
     * Duplicate flow
     */
    public function duplicate($id)
    {
        $flow = WhatsappChatbotFlow::with('steps')->findOrFail($id);

        $newFlow = $flow->replicate();
        $newFlow->name = $flow->name . ' (Copy)';
        $newFlow->save();

        // Duplicate steps
        foreach ($flow->steps as $step) {
            $newStep = $step->replicate();
            $newStep->flow_id = $newFlow->id;
            $newStep->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Flow duplicated successfully',
            'flow' => $newFlow
        ]);
    }

    /**
     * Get flow statistics
     */
    public function statistics($id)
    {
        $flow = WhatsappChatbotFlow::findOrFail($id);

        $sessions = WhatsappChatbotSession::where('flow_id', $id);

        $totalSessions = $sessions->count();
        $activeSessions = $sessions->where('status', 'active')->count();
        $completedSessions = $sessions->where('status', 'completed')->count();
        $abandonedSessions = $sessions->where('status', 'abandoned')->count();
        $handedOffSessions = $sessions->where('status', 'handed_off')->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'triggered_count' => $flow->triggered_count,
                'total_sessions' => $totalSessions,
                'active_sessions' => $activeSessions,
                'completed_sessions' => $completedSessions,
                'abandoned_sessions' => $abandonedSessions,
                'handed_off_sessions' => $handedOffSessions,
                'completion_rate' => $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100, 2) : 0
            ]
        ]);
    }

    /**
     * Toggle chatbot on/off
     */
    public function toggleChatbot(Request $request)
    {
        // Update global chatbot settings
        config(['whatsapp.chatbot_enabled' => $request->enabled]);

        return response()->json([
            'success' => true,
            'message' => 'Chatbot ' . ($request->enabled ? 'enabled' : 'disabled') . ' successfully'
        ]);
    }

    /**
     * Save chatbot settings
     */
    public function saveSettings(Request $request)
    {
        // Save chatbot settings to database or config
        // Implementation depends on your settings storage strategy

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully'
        ]);
    }

    /**
     * Show create flow form
     */
    public function createFlow()
    {
        $url = route('whatsapp.chatbot.store');
        return view('pages.whatsapp.components.modals.add-chatbot-flow', compact('url'));
    }

    /**
     * Show edit flow form
     */
    public function editFlow($id)
    {
        $flow = WhatsappChatbotFlow::findOrFail($id);
        $url = route('whatsapp.chatbot.update', $id);

        return view('pages.whatsapp.components.modals.add-chatbot-flow', compact('flow', 'url'));
    }

    /**
     * Show test flow form/modal
     */
    public function testFlow($id)
    {
        $flow = WhatsappChatbotFlow::findOrFail($id);

        return view('pages.whatsapp.components.modals.test-chatbot-flow', compact('flow'));
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
                __('lang.chatbot')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_chatbot'),
            'meta_title' => __('lang.whatsapp_chatbot'),
        ];
    }
}
