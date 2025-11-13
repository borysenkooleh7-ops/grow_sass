<?php

namespace App\Http\Controllers;

use App\Models\WhatsappBusinessProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappBusinessProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display business profile page
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $profile = WhatsappBusinessProfile::where('tenant_id', $tenantId)->first();

        $page = $this->pageSettings('whatsapp_business_profile');

        return view('pages.whatsapp.components.business-profile', compact('profile', 'page'));
    }

    /**
     * Get current business profile
     */
    public function show()
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $profile = WhatsappBusinessProfile::where('tenant_id', $tenantId)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'profile' => $profile
        ]);
    }

    /**
     * Create or update business profile
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'about' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:500',
            'profile_picture' => 'nullable|string|max:500',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'business_hours' => 'nullable|array'
        ]);

        $tenantId = Auth::user()->tenant_id ?? null;
        $validated['tenant_id'] = $tenantId;

        $profile = WhatsappBusinessProfile::updateOrCreate(
            ['tenant_id' => $tenantId],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Business profile saved successfully',
            'profile' => $profile
        ]);
    }

    /**
     * Update business profile
     */
    public function update(Request $request, $id)
    {
        $profile = WhatsappBusinessProfile::findOrFail($id);

        // Check tenant ownership
        $tenantId = Auth::user()->tenant_id ?? null;
        if ($profile->tenant_id != $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'about' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:500',
            'profile_picture' => 'nullable|string|max:500',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'business_hours' => 'nullable|array'
        ]);

        $profile->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Business profile updated successfully',
            'profile' => $profile
        ]);
    }

    /**
     * Upload profile picture
     */
    public function uploadPicture(Request $request)
    {
        $request->validate([
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $tenantId = Auth::user()->tenant_id ?? null;
        $profile = WhatsappBusinessProfile::where('tenant_id', $tenantId)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found. Please create a profile first.'
            ], 404);
        }

        // Handle file upload
        $file = $request->file('picture');
        $filename = 'business_profile_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('files/whatsapp/business'), $filename);

        $url = '/files/whatsapp/business/' . $filename;

        // Delete old picture
        if ($profile->profile_picture && file_exists(public_path($profile->profile_picture))) {
            unlink(public_path($profile->profile_picture));
        }

        $profile->profile_picture = $url;
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile picture uploaded successfully',
            'url' => $url
        ]);
    }

    /**
     * Update business hours
     */
    public function updateBusinessHours(Request $request)
    {
        $request->validate([
            'business_hours' => 'required|array',
            'business_hours.*.open' => 'required|string',
            'business_hours.*.close' => 'required|string',
            'business_hours.*.is_closed' => 'boolean'
        ]);

        $tenantId = Auth::user()->tenant_id ?? null;
        $profile = WhatsappBusinessProfile::where('tenant_id', $tenantId)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found'
            ], 404);
        }

        $profile->business_hours = $request->business_hours;
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Business hours updated successfully',
            'business_hours' => $profile->business_hours
        ]);
    }

    /**
     * Check if within business hours
     */
    public function checkBusinessHours()
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $profile = WhatsappBusinessProfile::where('tenant_id', $tenantId)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found'
            ], 404);
        }

        $isWithinHours = $profile->isWithinBusinessHours();

        return response()->json([
            'success' => true,
            'is_within_hours' => $isWithinHours,
            'current_time' => now()->format('H:i'),
            'current_day' => now()->format('l')
        ]);
    }

    /**
     * Update location
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100'
        ]);

        $tenantId = Auth::user()->tenant_id ?? null;
        $profile = WhatsappBusinessProfile::where('tenant_id', $tenantId)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found'
            ], 404);
        }

        $profile->update($request->only([
            'latitude',
            'longitude',
            'address',
            'city',
            'state',
            'postal_code',
            'country'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'location' => [
                'latitude' => $profile->latitude,
                'longitude' => $profile->longitude,
                'full_address' => $profile->full_address
            ]
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
                __('lang.business_profile')
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_heading' => __('lang.whatsapp_business_profile'),
            'meta_title' => __('lang.whatsapp_business_profile'),
        ];
    }
}
