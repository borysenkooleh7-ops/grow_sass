<?php

/**
 * @fileoverview WhatsApp Ticket Repository
 * @description Business logic for WhatsApp tickets
 */

namespace App\Repositories;

use App\Models\WhatsappTicket;
use App\Models\WhatsappContact;
use App\Models\Task;
use Illuminate\Support\Str;

class WhatsappTicketRepository
{
    /**
     * Search and filter tickets
     */
    public function search($limit = 25)
    {
        $query = WhatsappTicket::query();

        // Join with related tables
        $query->with([
            'contact',
            'client',
            'assignedAgent',
            'ticketType',
            'connection',
            'task',
        ]);

        // Filter by status
        if (request()->filled('filter_status')) {
            $query->where('whatsappticket_status', request('filter_status'));
        }

        // Filter by assigned user
        if (request()->filled('filter_assigned_to')) {
            if (request('filter_assigned_to') == 'unassigned') {
                $query->whereNull('whatsappticket_assigned_to');
            } else {
                $query->where('whatsappticket_assigned_to', request('filter_assigned_to'));
            }
        }

        // Filter by client
        if (request()->filled('filter_clientid')) {
            $query->where('whatsappticket_clientid', request('filter_clientid'));
        }

        // Filter by connection
        if (request()->filled('filter_connectionid')) {
            $query->where('whatsappticket_connectionid', request('filter_connectionid'));
        }

        // Filter by ticket type
        if (request()->filled('filter_typeid')) {
            $query->where('whatsappticket_typeid', request('filter_typeid'));
        }

        // Filter by priority
        if (request()->filled('filter_priority')) {
            $query->where('whatsappticket_priority', request('filter_priority'));
        }

        // Search by ticket number or subject
        if (request()->filled('search_query')) {
            $searchQuery = request('search_query');
            $query->where(function ($q) use ($searchQuery) {
                $q->where('whatsappticket_number', 'like', "%{$searchQuery}%")
                  ->orWhere('whatsappticket_subject', 'like', "%{$searchQuery}%");
            });
        }

        // Sorting
        $sortBy = request('sort_by', 'whatsappticket_created');
        $sortOrder = request('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        return $query->paginate($limit);
    }

    /**
     * Find or create ticket for a contact
     * This is called when a new message arrives from a contact
     */
    public function findOrCreateForContact($contactId, $connectionId)
    {
        $contact = WhatsappContact::find($contactId);

        if (!$contact) {
            return null;
        }

        // Look for existing open or on_hold ticket
        $existingTicket = WhatsappTicket::where('whatsappticket_contactid', $contactId)
            ->whereIn('whatsappticket_status', ['open', 'on_hold'])
            ->orderBy('whatsappticket_created', 'desc')
            ->first();

        if ($existingTicket) {
            return $existingTicket;
        }

        // Create new ticket (and corresponding task)
        return $this->createTicket($contactId, $connectionId);
    }

    /**
     * Create a new ticket with linked task
     * IMPORTANT: This also creates a Task in the tasks table
     */
    private function createTicket($contactId, $connectionId)
    {
        $contact = WhatsappContact::find($contactId);

        if (!$contact) {
            return null;
        }

        // 1. Create the Task first
        $task = new Task();
        $task->task_uniqueid = Str::uuid();
        $task->task_title = "WhatsApp: " . $contact->display_name;
        $task->task_description = "Auto-created task for WhatsApp conversation";
        $task->task_status = 'new';
        $task->task_priority = 'normal';
        $task->task_date_start = now();
        $task->task_date_due = now()->addDays(7);
        $task->task_creatorid = 1; // System user
        $task->task_clientid = $contact->whatsappcontact_clientid;

        if (!$task->save()) {
            return null;
        }

        // 2. Create the WhatsApp Ticket linked to the Task
        $ticket = new WhatsappTicket();
        $ticket->whatsappticket_uniqueid = Str::uuid();
        $ticket->whatsappticket_number = WhatsappTicket::generateTicketNumber();
        $ticket->whatsappticket_taskid = $task->task_id; // CRITICAL LINK
        $ticket->whatsappticket_connectionid = $connectionId;
        $ticket->whatsappticket_contactid = $contactId;
        $ticket->whatsappticket_clientid = $contact->whatsappcontact_clientid;
        $ticket->whatsappticket_status = 'on_hold'; // Default status
        $ticket->whatsappticket_priority = 'medium';
        $ticket->whatsappticket_subject = "Chat with " . $contact->display_name;

        // Auto-assign if enabled
        $connection = \App\Models\WhatsappConnection::find($connectionId);
        if ($connection && $connection->lineConfig && $connection->lineConfig->whatsapplineconfig_auto_assign_enabled) {
            $assignedUserId = $this->getNextUserForAssignment($connection->lineConfig->whatsapplineconfig_auto_assign_logic);
            if ($assignedUserId) {
                $ticket->whatsappticket_assigned_to = $assignedUserId;
                $ticket->whatsappticket_status = 'open'; // Change to open if assigned
            }
        }

        $ticket->save();

        return $ticket;
    }

    /**
     * Update ticket status
     */
    public function updateStatus($ticketId, $status)
    {
        $ticket = WhatsappTicket::find($ticketId);

        if (!$ticket) {
            return false;
        }

        $ticket->whatsappticket_status = $status;

        // Update resolved/closed timestamps
        if ($status === 'resolved' && empty($ticket->whatsappticket_resolved_at)) {
            $ticket->whatsappticket_resolved_at = now();
        }

        if ($status === 'closed' && empty($ticket->whatsappticket_closed_at)) {
            $ticket->whatsappticket_closed_at = now();
        }

        // Update linked task status
        if ($ticket->task) {
            if ($status === 'resolved' || $status === 'closed') {
                $ticket->task->task_status = 'completed';
                $ticket->task->save();
            } else if ($status === 'open') {
                $ticket->task->task_status = 'in_progress';
                $ticket->task->save();
            }
        }

        return $ticket->save();
    }

    /**
     * Assign ticket to user
     */
    public function assignTicket($ticketId, $userId)
    {
        $ticket = WhatsappTicket::find($ticketId);

        if (!$ticket) {
            return false;
        }

        $ticket->whatsappticket_assigned_to = $userId;

        // If ticket was on_hold, change to open
        if ($ticket->whatsappticket_status === 'on_hold') {
            $ticket->whatsappticket_status = 'open';
        }

        return $ticket->save();
    }

    /**
     * Get next user for auto-assignment based on logic
     */
    private function getNextUserForAssignment($logic = 'round_robin')
    {
        // Get all active team users
        $users = \App\Models\User::where('status', 'active')
            ->where('type', 'team')
            ->get();

        if ($users->isEmpty()) {
            return null;
        }

        switch ($logic) {
            case 'round_robin':
                // Get user with least recent assignment
                $userIds = $users->pluck('id')->toArray();
                $lastAssigned = WhatsappTicket::whereIn('whatsappticket_assigned_to', $userIds)
                    ->orderBy('whatsappticket_created', 'desc')
                    ->first();

                if (!$lastAssigned) {
                    return $users->first()->id;
                }

                // Find next user in rotation
                $currentIndex = array_search($lastAssigned->whatsappticket_assigned_to, $userIds);
                $nextIndex = ($currentIndex + 1) % count($userIds);
                return $userIds[$nextIndex];

            case 'least_active':
                // Get user with fewest open tickets
                $userTicketCounts = [];
                foreach ($users as $user) {
                    $count = WhatsappTicket::where('whatsappticket_assigned_to', $user->id)
                        ->whereIn('whatsappticket_status', ['open', 'on_hold'])
                        ->count();
                    $userTicketCounts[$user->id] = $count;
                }
                asort($userTicketCounts);
                return array_key_first($userTicketCounts);

            case 'random':
                return $users->random()->id;

            default:
                return $users->first()->id;
        }
    }

    /**
     * Get ticket statistics
     */
    public function getStatistics($userId = null)
    {
        $query = WhatsappTicket::query();

        if ($userId) {
            $query->where('whatsappticket_assigned_to', $userId);
        }

        return [
            'total' => $query->count(),
            'on_hold' => (clone $query)->where('whatsappticket_status', 'on_hold')->count(),
            'open' => (clone $query)->where('whatsappticket_status', 'open')->count(),
            'resolved' => (clone $query)->where('whatsappticket_status', 'resolved')->count(),
            'closed' => (clone $query)->where('whatsappticket_status', 'closed')->count(),
            'unassigned' => WhatsappTicket::whereNull('whatsappticket_assigned_to')
                ->whereIn('whatsappticket_status', ['open', 'on_hold'])
                ->count(),
        ];
    }
}
