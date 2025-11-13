<?php

namespace App\Http\Controllers;

use App\Models\WhatsappMedia;
use App\Models\WhatsappTicket;
use Illuminate\Http\Request;

class WhatsappMediaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display media gallery page
     */
    public function index()
    {
        $media = WhatsappMedia::with(['ticket', 'message', 'sender'])
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        $page = $this->pageSettings('whatsapp_media');

        return view('pages.whatsapp.components.media-gallery', compact('media', 'page'));
    }

    /**
     * Get media for DataTables/Gallery AJAX
     */
    public function ajax(Request $request)
    {
        $query = WhatsappMedia::with(['ticket', 'message', 'sender']);

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where('filename', 'like', "%{$search}%");
        }

        // Filter by type
        if ($request->has('filter_type') && $request->filter_type) {
            $query->where('type', $request->filter_type);
        }

        // Filter by ticket
        if ($request->has('filter_ticket') && $request->filter_ticket) {
            $query->where('ticket_id', $request->filter_ticket);
        }

        // Filter by date range
        if ($request->has('filter_date_from') && $request->has('filter_date_to')) {
            $query->whereBetween('created_at', [
                $request->filter_date_from,
                $request->filter_date_to
            ]);
        }

        $totalRecords = $query->count();

        // Pagination
        $media = $query->skip($request->start ?? 0)
            ->take($request->length ?? 24)
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => WhatsappMedia::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $media
        ]);
    }

    /**
     * Display the specified media
     */
    public function show($id)
    {
        $media = WhatsappMedia::with(['ticket', 'message', 'sender'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'media' => $media
        ]);
    }

    /**
     * Remove the specified media
     */
    public function destroy($id)
    {
        $media = WhatsappMedia::findOrFail($id);

        // Delete file from storage
        if (file_exists(public_path($media->url))) {
            unlink(public_path($media->url));
        }

        if ($media->thumbnail_url && file_exists(public_path($media->thumbnail_url))) {
            unlink(public_path($media->thumbnail_url));
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully'
        ]);
    }

    /**
     * Bulk delete media
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:whatsapp_media,id'
        ]);

        $media = WhatsappMedia::whereIn('id', $request->ids)->get();

        foreach ($media as $item) {
            // Delete files
            if (file_exists(public_path($item->url))) {
                unlink(public_path($item->url));
            }

            if ($item->thumbnail_url && file_exists(public_path($item->thumbnail_url))) {
                unlink(public_path($item->thumbnail_url));
            }

            $item->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' media files deleted successfully'
        ]);
    }

    /**
     * Get media for a specific ticket
     */
    public function getByTicket($ticketId)
    {
        $ticket = WhatsappTicket::findOrFail($ticketId);

        $media = WhatsappMedia::where('ticket_id', $ticketId)
            ->with(['message', 'sender'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'media' => $media,
            'ticket' => $ticket
        ]);
    }

    /**
     * Get images only
     */
    public function getImages(Request $request)
    {
        $query = WhatsappMedia::images()->with(['ticket', 'sender']);

        if ($request->has('ticket_id')) {
            $query->where('ticket_id', $request->ticket_id);
        }

        $images = $query->orderBy('created_at', 'desc')
            ->paginate(24);

        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }

    /**
     * Get videos only
     */
    public function getVideos(Request $request)
    {
        $query = WhatsappMedia::videos()->with(['ticket', 'sender']);

        if ($request->has('ticket_id')) {
            $query->where('ticket_id', $request->ticket_id);
        }

        $videos = $query->orderBy('created_at', 'desc')
            ->paginate(24);

        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    /**
     * Get documents only
     */
    public function getDocuments(Request $request)
    {
        $query = WhatsappMedia::documents()->with(['ticket', 'sender']);

        if ($request->has('ticket_id')) {
            $query->where('ticket_id', $request->ticket_id);
        }

        $documents = $query->orderBy('created_at', 'desc')
            ->paginate(24);

        return response()->json([
            'success' => true,
            'documents' => $documents
        ]);
    }

    /**
     * Get audio files only
     */
    public function getAudio(Request $request)
    {
        $query = WhatsappMedia::audio()->with(['ticket', 'sender']);

        if ($request->has('ticket_id')) {
            $query->where('ticket_id', $request->ticket_id);
        }

        $audio = $query->orderBy('created_at', 'desc')
            ->paginate(24);

        return response()->json([
            'success' => true,
            'audio' => $audio
        ]);
    }

    /**
     * Get media statistics
     */
    public function statistics(Request $request)
    {
        $query = WhatsappMedia::query();

        // Filter by date range
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from,
                $request->date_to
            ]);
        }

        $totalMedia = $query->count();
        $totalSize = $query->sum('size');
        $imageCount = $query->clone()->where('type', 'image')->count();
        $videoCount = $query->clone()->where('type', 'video')->count();
        $documentCount = $query->clone()->where('type', 'document')->count();
        $audioCount = $query->clone()->where('type', 'audio')->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'total_media' => $totalMedia,
                'total_size' => $totalSize,
                'total_size_formatted' => $this->formatBytes($totalSize),
                'image_count' => $imageCount,
                'video_count' => $videoCount,
                'document_count' => $documentCount,
                'audio_count' => $audioCount
            ]
        ]);
    }

    /**
     * Download media file
     */
    public function download($id)
    {
        $media = WhatsappMedia::findOrFail($id);

        $filePath = public_path($media->url);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        return response()->download($filePath, $media->filename);
    }

    /**
     * Format bytes to human readable size
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
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
                __('lang.media')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_media'),
            'meta_title' => __('lang.whatsapp_media'),
        ];
    }
}
