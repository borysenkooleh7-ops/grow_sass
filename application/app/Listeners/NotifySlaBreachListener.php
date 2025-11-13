<?php

namespace App\Listeners;

use App\Events\WhatsappSlaBreached;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotifySlaBreachListener
{
    /**
     * Handle the event.
     */
    public function handle(WhatsappSlaBreached $event)
    {
        $sla = $event->sla;
        $ticket = $sla->ticket;

        if (!$ticket) {
            Log::warning('SLA Breach: Ticket not found', ['sla_id' => $sla->whatsappticketsla_id]);
            return;
        }

        // Determine breach type
        $breachType = null;
        if ($sla->whatsappticketsla_first_response_breached) {
            $breachType = 'first_response';
        } elseif ($sla->whatsappticketsla_resolution_breached) {
            $breachType = 'resolution';
        }

        if (!$breachType) {
            return; // No breach detected
        }

        // Get users to notify
        $usersToNotify = $this->getUsersToNotify($ticket);

        if ($usersToNotify->isEmpty()) {
            Log::info('SLA Breach: No users to notify', [
                'ticket_id' => $ticket->whatsappticket_id
            ]);
            return;
        }

        // Send notifications
        foreach ($usersToNotify as $user) {
            $this->sendEmailNotification($user, $ticket, $sla, $breachType);
        }

        Log::info('SLA Breach notifications sent', [
            'ticket_id' => $ticket->whatsappticket_id,
            'breach_type' => $breachType,
            'notified_users' => $usersToNotify->count()
        ]);
    }

    /**
     * Get users who should be notified
     */
    protected function getUsersToNotify($ticket)
    {
        $users = collect();

        // Notify assigned agent
        if ($ticket->whatsappticket_assigned_to) {
            $assignedUser = User::find($ticket->whatsappticket_assigned_to);
            if ($assignedUser) {
                $users->push($assignedUser);
            }
        }

        // Notify team leads/managers (users with role 'admin' or 'team')
        $managers = User::whereIn('type', ['admin', 'team'])
            ->where('status', 'active')
            ->get();

        $users = $users->merge($managers);

        return $users->unique('id');
    }

    /**
     * Send email notification
     */
    protected function sendEmailNotification($user, $ticket, $sla, $breachType)
    {
        try {
            $contactName = $ticket->contact ? $ticket->contact->whatsappcontact_name : 'Unknown';
            $contactPhone = $ticket->contact ? $ticket->contact->whatsappcontact_phone : 'N/A';

            $subject = 'SLA Breach Alert: Ticket #' . $ticket->whatsappticket_number;

            $breachDescription = $breachType === 'first_response'
                ? 'First Response Time breached'
                : 'Resolution Time breached';

            $targetTime = $breachType === 'first_response'
                ? $sla->whatsappticketsla_first_response_target
                : $sla->whatsappticketsla_resolution_target;

            $message = view('emails.whatsapp-sla-breach', [
                'userName' => $user->first_name ?? $user->name ?? 'Team Member',
                'ticketNumber' => $ticket->whatsappticket_number,
                'ticketSubject' => $ticket->whatsappticket_subject,
                'contactName' => $contactName,
                'contactPhone' => $contactPhone,
                'breachType' => $breachDescription,
                'targetTime' => $targetTime ? $targetTime->format('Y-m-d H:i:s') : 'N/A',
                'ticketUrl' => url('/whatsapp/conversations?ticket=' . $ticket->whatsappticket_id),
            ])->render();

            // Send email
            Mail::send([], [], function ($email) use ($user, $subject, $message) {
                $email->to($user->email, $user->first_name ?? $user->name)
                    ->subject($subject)
                    ->html($message);
            });

            Log::info('SLA Breach email sent', [
                'user_id' => $user->id,
                'ticket_id' => $ticket->whatsappticket_id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send SLA breach notification', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
                'ticket_id' => $ticket->whatsappticket_id ?? null
            ]);
        }
    }
}
