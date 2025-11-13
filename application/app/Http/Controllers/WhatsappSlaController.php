<?php

namespace App\Http\Controllers;

use App\Models\WhatsappSlaPolicy;
use App\Models\WhatsappTicketSla;
use App\Models\WhatsappTicket;
use Illuminate\Http\Request;

class WhatsappSlaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display SLA policies page
     */
    public function index()
    {
        $policies = WhatsappSlaPolicy::orderBy('priority', 'asc')
            ->paginate(20);

        $page = $this->pageSettings('whatsapp_sla');

        return view('pages.whatsapp.components.sla.sla-management', compact('policies', 'page'));
    }

    /**
     * Get policies for DataTables AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappSlaPolicy::query();

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by priority
        if ($request->has('filter_priority') && $request->filter_priority) {
            $query->where('priority', $request->filter_priority);
        }

        // Filter by status
        if ($request->has('filter_status') && $request->filter_status !== '') {
            $query->where('is_active', $request->filter_status);
        }

        $totalRecords = $query->count();

        // Pagination
        $policies = $query->skip($request->start ?? 0)
            ->take($request->length ?? 20)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappSlaPolicy::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $policies
        ]);
    }

    /**
     * Store a new SLA policy
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|in:low,normal,high,urgent',
            'first_response_time' => 'required|integer|min:1',
            'resolution_time' => 'required|integer|min:1',
            'business_hours_only' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $validated['business_hours_only'] = $request->has('business_hours_only') ? 1 : 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $policy = WhatsappSlaPolicy::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'SLA policy created successfully',
            'policy' => $policy
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $url = route('whatsapp.sla.store');
        return view('pages.whatsapp.components.modals.add-sla-policy', compact('url'));
    }

    /**
     * Display the specified policy
     */
    public function show($id)
    {
        $policy = WhatsappSlaPolicy::with('slaTracking')->findOrFail($id);

        return response()->json([
            'success' => true,
            'policy' => $policy
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $policy = WhatsappSlaPolicy::findOrFail($id);
        $url = route('whatsapp.sla.update', $id);

        return view('pages.whatsapp.components.modals.add-sla-policy', compact('policy', 'url'));
    }

    /**
     * Update the specified policy
     */
    public function update(Request $request, $id)
    {
        $policy = WhatsappSlaPolicy::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|in:low,normal,high,urgent',
            'first_response_time' => 'required|integer|min:1',
            'resolution_time' => 'required|integer|min:1',
            'business_hours_only' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $validated['business_hours_only'] = $request->has('business_hours_only') ? 1 : 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $policy->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'SLA policy updated successfully',
            'policy' => $policy
        ]);
    }

    /**
     * Remove the specified policy
     */
    public function destroy($id)
    {
        $policy = WhatsappSlaPolicy::findOrFail($id);
        $policy->delete();

        return response()->json([
            'success' => true,
            'message' => 'SLA policy deleted successfully'
        ]);
    }

    /**
     * Toggle policy status
     */
    public function toggleStatus($id)
    {
        $policy = WhatsappSlaPolicy::findOrFail($id);
        $policy->is_active = !$policy->is_active;
        $policy->save();

        return response()->json([
            'success' => true,
            'message' => 'Policy status updated',
            'is_active' => $policy->is_active
        ]);
    }

    /**
     * Get SLA statistics
     */
    public function statistics(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $query = WhatsappTicketSla::whereBetween('created_at', [$dateFrom, $dateTo]);

        $totalSlas = $query->count();
        $metSlas = $query->where('status', 'met')->count();
        $breachedSlas = $query->where('status', 'breached')->count();
        $atRiskSlas = $query->where('status', 'at_risk')->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'total' => $totalSlas,
                'met' => $metSlas,
                'breached' => $breachedSlas,
                'at_risk' => $atRiskSlas,
                'met_percentage' => $totalSlas > 0 ? round(($metSlas / $totalSlas) * 100, 2) : 0,
                'breach_percentage' => $totalSlas > 0 ? round(($breachedSlas / $totalSlas) * 100, 2) : 0
            ]
        ]);
    }

    /**
     * Get SLA breaches report
     */
    public function breaches(Request $request)
    {
        $query = WhatsappTicketSla::with(['ticket', 'slaPolicy'])
            ->where('status', 'breached')
            ->orderBy('created_at', 'desc');

        // Filter by date range
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $breaches = $query->paginate(20);

        return response()->json([
            'success' => true,
            'breaches' => $breaches
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
                __('lang.sla')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_sla'),
            'meta_title' => __('lang.whatsapp_sla'),
        ];
    }
}
