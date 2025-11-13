<?php

/**
 * @fileoverview WhatsApp Automation Service
 * @description Handles automated messages and ticket closure based on line configuration
 */

namespace App\Services;

use App\Models\WhatsappTicket;
use App\Models\WhatsappMessage;
use App\Models\WhatsappLineConfig;
use App\Services\WhatsappIntegrationService;
use Illuminate\Support\Facades\Log;

class WhatsappAutomationService
{
    protected $integrationService;

    public function __construct(WhatsappIntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    /**
     * Send welcome message to new ticket
     *
     * @param WhatsappTicket $ticket
     * @return bool
     */
    public function sendWelcomeMessage($ticket)
    {
        try {
            $lineConfig = WhatsappLineConfig::where('whatsapplineconfig_connectionid', $ticket->whatsappticket_connectionid)->first();

            if (!$lineConfig || empty($lineConfig->whatsapplineconfig_welcome_message)) {
                return false;
            }

            // Check if within business hours
            if ($lineConfig->whatsapplineconfig_business_hours_enabled && !$this->isWithinBusinessHours($lineConfig)) {
                // Send away message instead
                return $this->sendAwayMessage($ticket);
            }

            // Create and send welcome message
            $message = WhatsappMessage::create([
                'whatsappmessage_uniqueid' => str_unique(),
                'whatsappmessage_ticketid' => $ticket->whatsappticket_id,
                'whatsappmessage_contactid' => $ticket->whatsappticket_contactid,
                'whatsappmessage_userid' => null, // System message
                'whatsappmessage_direction' => 'outgoing',
                'whatsappmessage_channel' => 'whatsapp',
                'whatsappmessage_type' => 'text',
                'whatsappmessage_content' => $lineConfig->whatsapplineconfig_welcome_message,
                'whatsappmessage_status' => 'pending',
            ]);

            // Send via Integration Service
            $this->integrationService->sendMessage($message);

            Log::info('Welcome message sent', [
                'ticket_id' => $ticket->whatsappticket_id,
                'message_id' => $message->whatsappmessage_id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Welcome message error', [
                'ticket_id' => $ticket->whatsappticket_id ?? null,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send away message (outside business hours)
     *
     * @param WhatsappTicket $ticket
     * @return bool
     */
    public function sendAwayMessage($ticket)
    {
        try {
            $lineConfig = WhatsappLineConfig::where('whatsapplineconfig_connectionid', $ticket->whatsappticket_connectionid)->first();

            if (!$lineConfig || empty($lineConfig->whatsapplineconfig_away_message)) {
                return false;
            }

            // Create and send away message
            $message = WhatsappMessage::create([
                'whatsappmessage_uniqueid' => str_unique(),
                'whatsappmessage_ticketid' => $ticket->whatsappticket_id,
                'whatsappmessage_contactid' => $ticket->whatsappticket_contactid,
                'whatsappmessage_userid' => null,
                'whatsappmessage_direction' => 'outgoing',
                'whatsappmessage_channel' => 'whatsapp',
                'whatsappmessage_type' => 'text',
                'whatsappmessage_content' => $lineConfig->whatsapplineconfig_away_message,
                'whatsappmessage_status' => 'pending',
            ]);

            // Send via Integration Service
            $this->integrationService->sendMessage($message);

            Log::info('Away message sent', [
                'ticket_id' => $ticket->whatsappticket_id,
                'message_id' => $message->whatsappmessage_id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Away message error', [
                'ticket_id' => $ticket->whatsappticket_id ?? null,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send closure message when ticket is closed
     *
     * @param WhatsappTicket $ticket
     * @return bool
     */
    public function sendClosureMessage($ticket)
    {
        try {
            $lineConfig = WhatsappLineConfig::where('whatsapplineconfig_connectionid', $ticket->whatsappticket_connectionid)->first();

            if (!$lineConfig || empty($lineConfig->whatsapplineconfig_closure_message)) {
                return false;
            }

            // Check if we can still send message (24-hour window)
            if (!$ticket->isWithin24HourWindow()) {
                Log::warning('Cannot send closure message - outside 24h window', [
                    'ticket_id' => $ticket->whatsappticket_id,
                ]);
                return false;
            }

            // Create and send closure message
            $message = WhatsappMessage::create([
                'whatsappmessage_uniqueid' => str_unique(),
                'whatsappmessage_ticketid' => $ticket->whatsappticket_id,
                'whatsappmessage_contactid' => $ticket->whatsappticket_contactid,
                'whatsappmessage_userid' => null,
                'whatsappmessage_direction' => 'outgoing',
                'whatsappmessage_channel' => 'whatsapp',
                'whatsappmessage_type' => 'text',
                'whatsappmessage_content' => $lineConfig->whatsapplineconfig_closure_message,
                'whatsappmessage_status' => 'pending',
            ]);

            // Send via Integration Service
            $this->integrationService->sendMessage($message);

            Log::info('Closure message sent', [
                'ticket_id' => $ticket->whatsappticket_id,
                'message_id' => $message->whatsappmessage_id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Closure message error', [
                'ticket_id' => $ticket->whatsappticket_id ?? null,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send inactivity warning message before auto-close
     *
     * @param WhatsappTicket $ticket
     * @return bool
     */
    public function sendInactivityMessage($ticket)
    {
        try {
            $lineConfig = WhatsappLineConfig::where('whatsapplineconfig_connectionid', $ticket->whatsappticket_connectionid)->first();

            if (!$lineConfig || empty($lineConfig->whatsapplineconfig_inactivity_message)) {
                return false;
            }

            // Check if we can still send message (24-hour window)
            if (!$ticket->isWithin24HourWindow()) {
                Log::warning('Cannot send inactivity message - outside 24h window', [
                    'ticket_id' => $ticket->whatsappticket_id,
                ]);
                return false;
            }

            // Create and send inactivity message
            $message = WhatsappMessage::create([
                'whatsappmessage_uniqueid' => str_unique(),
                'whatsappmessage_ticketid' => $ticket->whatsappticket_id,
                'whatsappmessage_contactid' => $ticket->whatsappticket_contactid,
                'whatsappmessage_userid' => null,
                'whatsappmessage_direction' => 'outgoing',
                'whatsappmessage_channel' => 'whatsapp',
                'whatsappmessage_type' => 'text',
                'whatsappmessage_content' => $lineConfig->whatsapplineconfig_inactivity_message,
                'whatsappmessage_status' => 'pending',
            ]);

            // Send via Integration Service
            $this->integrationService->sendMessage($message);

            Log::info('Inactivity message sent', [
                'ticket_id' => $ticket->whatsappticket_id,
                'message_id' => $message->whatsappmessage_id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Inactivity message error', [
                'ticket_id' => $ticket->whatsappticket_id ?? null,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Check and auto-close inactive tickets
     * This should be run via scheduled task/cron job
     *
     * @return int Number of tickets closed
     */
    public function checkAndCloseInactiveTickets()
    {
        try {
            $closedCount = 0;

            // Get all line configs with auto-close enabled
            $lineConfigs = WhatsappLineConfig::where('whatsapplineconfig_auto_close_enabled', true)->get();

            foreach ($lineConfigs as $lineConfig) {
                $inactivityMinutes = $lineConfig->whatsapplineconfig_inactivity_minutes ?? 60;

                // Find inactive tickets for this connection
                $inactiveTickets = WhatsappTicket::where('whatsappticket_connectionid', $lineConfig->whatsapplineconfig_connectionid)
                    ->whereIn('whatsappticket_status', ['on_hold', 'open'])
                    ->where('whatsappticket_last_message_at', '<', now()->subMinutes($inactivityMinutes))
                    ->get();

                foreach ($inactiveTickets as $ticket) {
                    // Send inactivity message
                    $this->sendInactivityMessage($ticket);

                    // Close ticket
                    $ticket->whatsappticket_status = 'closed';
                    $ticket->whatsappticket_closed_at = now();
                    $ticket->save();

                    $closedCount++;

                    Log::info('Ticket auto-closed due to inactivity', [
                        'ticket_id' => $ticket->whatsappticket_id,
                        'inactivity_minutes' => $inactivityMinutes,
                    ]);
                }
            }

            return $closedCount;

        } catch (\Exception $e) {
            Log::error('Auto-close check error', [
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    /**
     * Check if within business hours
     *
     * @param WhatsappLineConfig $lineConfig
     * @return bool
     */
    protected function isWithinBusinessHours($lineConfig)
    {
        if (!$lineConfig->whatsapplineconfig_business_hours_enabled) {
            return true;
        }

        $now = now();
        $currentDay = strtolower($now->format('l'));
        $currentTime = $now->format('H:i:s');

        $businessDays = explode(',', $lineConfig->whatsapplineconfig_business_days ?? '');
        if (!in_array($currentDay, $businessDays)) {
            return false;
        }

        if ($currentTime < $lineConfig->whatsapplineconfig_business_hours_start ||
            $currentTime > $lineConfig->whatsapplineconfig_business_hours_end) {
            return false;
        }

        return true;
    }
}
