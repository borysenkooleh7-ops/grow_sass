<?php

/**
 * @fileoverview WhatsApp Contact Repository
 * @description Business logic for WhatsApp contacts
 */

namespace App\Repositories;

use App\Models\WhatsappContact;
use Illuminate\Support\Str;

class WhatsappContactRepository
{
    /**
     * Find or create contact by phone number
     * Used by webhook when new message arrives
     */
    public function findOrCreate($phone, $connectionId, $name = null, $profilePic = null)
    {
        // Normalize phone number
        $phone = WhatsappContact::normalizePhone($phone);

        // Look for existing contact
        $contact = WhatsappContact::where('whatsappcontact_phone', $phone)
            ->where('whatsappcontact_connectionid', $connectionId)
            ->first();

        if ($contact) {
            // Update name and profile pic if provided
            if ($name && empty($contact->whatsappcontact_name)) {
                $contact->whatsappcontact_name = $name;
                $contact->whatsappcontact_display_name = $name;
            }

            if ($profilePic && empty($contact->whatsappcontact_profile_pic)) {
                $contact->whatsappcontact_profile_pic = $profilePic;
            }

            $contact->save();

            return $contact;
        }

        // Create new contact
        $contact = new WhatsappContact();
        $contact->whatsappcontact_uniqueid = Str::uuid();
        $contact->whatsappcontact_connectionid = $connectionId;
        $contact->whatsappcontact_phone = $phone;
        $contact->whatsappcontact_name = $name;
        $contact->whatsappcontact_display_name = $name ?? $phone;
        $contact->whatsappcontact_profile_pic = $profilePic;
        $contact->save();

        return $contact;
    }

    /**
     * Link contact to an existing client
     */
    public function linkToClient($contactId, $clientId)
    {
        $contact = WhatsappContact::find($contactId);

        if (!$contact) {
            return [
                'success' => false,
                'error' => 'Contact not found',
            ];
        }

        // Verify client exists
        $client = \App\Models\Client::find($clientId);

        if (!$client) {
            return [
                'success' => false,
                'error' => 'Client not found',
            ];
        }

        $contact->whatsappcontact_clientid = $clientId;

        if ($contact->save()) {
            // Update all tickets for this contact to include client_id
            \App\Models\WhatsappTicket::where('whatsappticket_contactid', $contactId)
                ->whereNull('whatsappticket_clientid')
                ->update(['whatsappticket_clientid' => $clientId]);

            return [
                'success' => true,
                'contact' => $contact,
            ];
        }

        return [
            'success' => false,
            'error' => 'Failed to link contact',
        ];
    }

    /**
     * Search contacts
     */
    public function search($limit = 25)
    {
        $query = WhatsappContact::query();

        // Join with related tables
        $query->with([
            'connection',
            'client',
            'tickets' => function ($q) {
                $q->orderBy('whatsappticket_created', 'desc')->limit(5);
            },
        ]);

        // Filter by connection
        if (request()->filled('filter_connectionid')) {
            $query->where('whatsappcontact_connectionid', request('filter_connectionid'));
        }

        // Filter by client link status
        if (request()->filled('filter_linked')) {
            if (request('filter_linked') === 'yes') {
                $query->whereNotNull('whatsappcontact_clientid');
            } else if (request('filter_linked') === 'no') {
                $query->whereNull('whatsappcontact_clientid');
            }
        }

        // Filter by specific client
        if (request()->filled('filter_clientid')) {
            $query->where('whatsappcontact_clientid', request('filter_clientid'));
        }

        // Search by name or phone
        if (request()->filled('search_query')) {
            $searchQuery = request('search_query');
            $query->where(function ($q) use ($searchQuery) {
                $q->where('whatsappcontact_name', 'like', "%{$searchQuery}%")
                  ->orWhere('whatsappcontact_display_name', 'like', "%{$searchQuery}%")
                  ->orWhere('whatsappcontact_phone', 'like', "%{$searchQuery}%");
            });
        }

        // Sorting
        $sortBy = request('sort_by', 'whatsappcontact_last_message_at');
        $sortOrder = request('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        return $query->paginate($limit);
    }

    /**
     * Get contact by phone number
     */
    public function findByPhone($phone, $connectionId)
    {
        $phone = WhatsappContact::normalizePhone($phone);

        return WhatsappContact::where('whatsappcontact_phone', $phone)
            ->where('whatsappcontact_connectionid', $connectionId)
            ->first();
    }

    /**
     * Get contacts for a specific client
     */
    public function getByClient($clientId)
    {
        return WhatsappContact::where('whatsappcontact_clientid', $clientId)
            ->with(['connection', 'tickets'])
            ->orderBy('whatsappcontact_last_message_at', 'desc')
            ->get();
    }

    /**
     * Update contact information
     */
    public function update($contactId, $data)
    {
        $contact = WhatsappContact::find($contactId);

        if (!$contact) {
            return [
                'success' => false,
                'error' => 'Contact not found',
            ];
        }

        // Update allowed fields
        if (isset($data['name'])) {
            $contact->whatsappcontact_name = $data['name'];
            $contact->whatsappcontact_display_name = $data['name'];
        }

        if (isset($data['notes'])) {
            $contact->whatsappcontact_notes = $data['notes'];
        }

        if (isset($data['tags'])) {
            $contact->whatsappcontact_tags = is_array($data['tags']) ? json_encode($data['tags']) : $data['tags'];
        }

        if ($contact->save()) {
            return [
                'success' => true,
                'contact' => $contact,
            ];
        }

        return [
            'success' => false,
            'error' => 'Failed to update contact',
        ];
    }

    /**
     * Delete contact
     */
    public function delete($contactId)
    {
        $contact = WhatsappContact::find($contactId);

        if (!$contact) {
            return [
                'success' => false,
                'error' => 'Contact not found',
            ];
        }

        // Check if contact has any tickets
        $ticketCount = \App\Models\WhatsappTicket::where('whatsappticket_contactid', $contactId)->count();

        if ($ticketCount > 0) {
            return [
                'success' => false,
                'error' => 'Cannot delete contact with existing tickets',
            ];
        }

        if ($contact->delete()) {
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'error' => 'Failed to delete contact',
        ];
    }

    /**
     * Get contact statistics
     */
    public function getStatistics($contactId)
    {
        $contact = WhatsappContact::find($contactId);

        if (!$contact) {
            return null;
        }

        return [
            'total_tickets' => $contact->tickets()->count(),
            'open_tickets' => $contact->tickets()->whereIn('whatsappticket_status', ['open', 'on_hold'])->count(),
            'resolved_tickets' => $contact->tickets()->where('whatsappticket_status', 'resolved')->count(),
            'closed_tickets' => $contact->tickets()->where('whatsappticket_status', 'closed')->count(),
            'total_messages' => $contact->messages()->count(),
            'last_message_at' => $contact->whatsappcontact_last_message_at,
        ];
    }
}
