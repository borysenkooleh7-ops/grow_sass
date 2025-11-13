<?php

namespace App\Http\Controllers;

use App\Models\WhatsappRoutingRule;
use App\Models\User;
use Illuminate\Http\Request;

class WhatsappRoutingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display routing rules page
     */
    public function index()
    {
        $rules = WhatsappRoutingRule::orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $users = User::where('type', 'team')->where('status', 1)->get();

        $page = $this->pageSettings('whatsapp_routing');

        return view('pages.whatsapp.components.routing.assignment-rules', compact('rules', 'users', 'page'));
    }

    /**
     * Get rules for DataTables AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappRoutingRule::query();

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('filter_status') && $request->filter_status !== '') {
            $query->where('is_active', $request->filter_status);
        }

        // Filter by assignment type
        if ($request->has('filter_type') && $request->filter_type) {
            $query->where('assign_to_type', $request->filter_type);
        }

        $totalRecords = $query->count();

        // Pagination and ordering
        $rules = $query->orderBy('priority', 'desc')
            ->skip($request->start ?? 0)
            ->take($request->length ?? 20)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappRoutingRule::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $rules
        ]);
    }

    /**
     * Store a new routing rule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|integer|min:0|max:100',
            'conditions' => 'nullable|array',
            'assign_to_type' => 'required|in:user,team,auto',
            'assign_to_id' => 'required_unless:assign_to_type,auto|nullable|integer',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $rule = WhatsappRoutingRule::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Routing rule created successfully',
            'rule' => $rule
        ]);
    }

    /**
     * Display the specified rule
     */
    public function show($id)
    {
        $rule = WhatsappRoutingRule::findOrFail($id);

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
        $rule = WhatsappRoutingRule::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|integer|min:0|max:100',
            'conditions' => 'nullable|array',
            'assign_to_type' => 'required|in:user,team,auto',
            'assign_to_id' => 'required_unless:assign_to_type,auto|nullable|integer',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $rule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Routing rule updated successfully',
            'rule' => $rule
        ]);
    }

    /**
     * Remove the specified rule
     */
    public function destroy($id)
    {
        $rule = WhatsappRoutingRule::findOrFail($id);
        $rule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Routing rule deleted successfully'
        ]);
    }

    /**
     * Toggle rule status
     */
    public function toggleStatus($id)
    {
        $rule = WhatsappRoutingRule::findOrFail($id);
        $rule->is_active = !$rule->is_active;
        $rule->save();

        return response()->json([
            'success' => true,
            'message' => 'Rule status updated',
            'is_active' => $rule->is_active
        ]);
    }

    /**
     * Update rule priorities (reorder)
     */
    public function updatePriorities(Request $request)
    {
        $request->validate([
            'priorities' => 'required|array',
            'priorities.*.id' => 'required|exists:whatsapp_routing_rules,id',
            'priorities.*.priority' => 'required|integer|min:0'
        ]);

        foreach ($request->priorities as $item) {
            WhatsappRoutingRule::where('id', $item['id'])
                ->update(['priority' => $item['priority']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Priorities updated successfully'
        ]);
    }

    /**
     * Test routing rule
     */
    public function test(Request $request, $id)
    {
        $rule = WhatsappRoutingRule::findOrFail($id);

        $testData = $request->validate([
            'test_data' => 'required|array'
        ]);

        $matches = $rule->matchesConditions($testData['test_data']);

        return response()->json([
            'success' => true,
            'matches' => $matches,
            'message' => $matches ? 'Rule conditions match' : 'Rule conditions do not match',
            'would_assign_to' => $matches ? [
                'type' => $rule->assign_to_type,
                'id' => $rule->assign_to_id
            ] : null
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
                __('lang.routing')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_routing'),
            'meta_title' => __('lang.whatsapp_routing'),
        ];
    }
}
