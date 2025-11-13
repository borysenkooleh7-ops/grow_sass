<?php

namespace App\Http\Controllers;

use App\Models\WhatsappQuickReply;
use App\Http\Responses\WhatsappQuickReplies\CreateResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappQuickReplyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of quick replies
     */
    public function index()
    {
        $quickReplies = WhatsappQuickReply::with('creator')
            ->where(function($query) {
                $query->where('whatsappquickreply_created_by', Auth::id())
                    ->orWhere('whatsappquickreply_is_shared', true);
            })
            ->orderBy('whatsappquickreply_created', 'desc')
            ->paginate(20);

        $page = $this->pageSettings('whatsapp_quick_replies');

        return view('pages.whatsapp.components.quick-replies.quick-replies', compact('quickReplies', 'page'));
    }

    /**
     * Show create form for new quick reply
     */
    public function create()
    {
        // Initialize empty quick reply object with database column names
        $quick_reply = (object) [
            'whatsappquickreply_title' => '',
            'whatsappquickreply_shortcut' => '',
            'whatsappquickreply_message' => '',
            'whatsappquickreply_category' => '',
            'whatsappquickreply_is_shared' => false
        ];

        // URL for form submission
        $url = route('whatsapp.quick-replies.store');

        // Response payload
        $payload = [
            'quick_reply' => $quick_reply,
            'url' => $url,
        ];

        // Return modal using Response class
        return new CreateResponse($payload);
    }

    /**
     * Get quick replies for DataTables AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappQuickReply::with('creator')
            ->where(function($q) {
                $q->where('whatsappquickreply_created_by', Auth::id())
                    ->orWhere('whatsappquickreply_is_shared', true);
            });

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('whatsappquickreply_title', 'like', "%{$search}%")
                  ->orWhere('whatsappquickreply_message', 'like', "%{$search}%")
                  ->orWhere('whatsappquickreply_shortcut', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('filter_category') && $request->filter_category) {
            $query->where('whatsappquickreply_category', $request->filter_category);
        }

        $totalRecords = $query->count();

        // Pagination
        $quickReplies = $query->skip($request->start ?? 0)
            ->take($request->length ?? 20)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappQuickReply::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $quickReplies
        ]);
    }

    /**
     * Store a newly created quick reply
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'shortcut' => 'nullable|string|max:50|unique:whatsapp_quick_replies,whatsappquickreply_shortcut',
            'message' => 'required|string',
            'category' => 'nullable|string|max:50',
            'is_shared' => 'boolean'
        ]);

        $quickReply = WhatsappQuickReply::create([
            'whatsappquickreply_uniqueid' => str_unique(),
            'whatsappquickreply_title' => $validated['title'],
            'whatsappquickreply_shortcut' => $validated['shortcut'] ?? null,
            'whatsappquickreply_message' => $validated['message'],
            'whatsappquickreply_category' => $validated['category'] ?? 'general',
            'whatsappquickreply_is_shared' => $request->has('is_shared') ? 1 : 0,
            'whatsappquickreply_created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quick reply created successfully',
            'quickReply' => $quickReply
        ]);
    }

    /**
     * Display the specified quick reply
     */
    public function show($id)
    {
        $quickReply = WhatsappQuickReply::with('creator')->findOrFail($id);

        return response()->json([
            'success' => true,
            'quickReply' => $quickReply
        ]);
    }

    /**
     * Show edit form for quick reply
     */
    public function edit($id)
    {
        $quickReply = WhatsappQuickReply::findOrFail($id);

        // Return modal content
        $url = route('whatsapp.quick-replies.update', $id);

        return view('pages.whatsapp.components.modals.add-quick-reply', compact('quickReply', 'url'));
    }

    /**
     * Update the specified quick reply
     */
    public function update(Request $request, $id)
    {
        $quickReply = WhatsappQuickReply::findOrFail($id);

        // Check ownership
        if ($quickReply->whatsappquickreply_created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only edit your own quick replies'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'shortcut' => 'nullable|string|max:50|unique:whatsapp_quick_replies,whatsappquickreply_shortcut,' . $id . ',whatsappquickreply_id',
            'message' => 'required|string',
            'category' => 'nullable|string|max:50',
            'is_shared' => 'boolean'
        ]);

        $quickReply->update([
            'whatsappquickreply_title' => $validated['title'],
            'whatsappquickreply_shortcut' => $validated['shortcut'] ?? null,
            'whatsappquickreply_message' => $validated['message'],
            'whatsappquickreply_category' => $validated['category'] ?? 'general',
            'whatsappquickreply_is_shared' => $request->has('is_shared') ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quick reply updated successfully',
            'quickReply' => $quickReply
        ]);
    }

    /**
     * Remove the specified quick reply
     */
    public function destroy($id)
    {
        $quickReply = WhatsappQuickReply::findOrFail($id);

        // Check ownership
        if ($quickReply->whatsappquickreply_created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own quick replies'
            ], 403);
        }

        $quickReply->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quick reply deleted successfully'
        ]);
    }

    /**
     * Search quick replies by shortcut
     */
    public function searchByShortcut(Request $request)
    {
        $request->validate([
            'shortcut' => 'required|string'
        ]);

        $quickReply = WhatsappQuickReply::where('whatsappquickreply_shortcut', $request->shortcut)
            ->where(function($q) {
                $q->where('whatsappquickreply_created_by', Auth::id())
                    ->orWhere('whatsappquickreply_is_shared', true);
            })
            ->first();

        if ($quickReply) {
            return response()->json([
                'success' => true,
                'quickReply' => $quickReply
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Quick reply not found'
        ], 404);
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
                __('lang.quick_replies')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_quick_replies'),
            'meta_title' => __('lang.whatsapp_quick_replies'),
        ];
    }
}
