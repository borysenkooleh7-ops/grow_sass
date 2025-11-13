<?php

namespace App\Jobs;

use App\Models\WhatsappTicketSla;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateSlaStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * This job runs periodically to update SLA statuses
     */
    public function handle()
    {
        try {
            // Get all active SLA tracking records that are not completed
            $slaTracking = WhatsappTicketSla::whereIn('status', ['at_risk', 'met'])
                ->whereNull('achieved_at')
                ->with(['ticket', 'slaPolicy'])
                ->get();

            $updated = 0;
            $breached = 0;

            foreach ($slaTracking as $sla) {
                $oldStatus = $sla->status;

                // Update status based on current time
                $sla->updateStatus();

                if ($oldStatus !== $sla->status) {
                    $updated++;

                    // Check if newly breached
                    if ($sla->status === 'breached') {
                        $breached++;

                        // Trigger breach notification/event
                        $this->notifySlaBreach($sla);
                    }
                }
            }

            Log::info('SLA status update completed', [
                'checked' => $slaTracking->count(),
                'updated' => $updated,
                'breached' => $breached
            ]);

        } catch (\Exception $e) {
            Log::error('SLA status update failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Notify about SLA breach
     */
    protected function notifySlaBreach($sla)
    {
        try {
            Log::warning('SLA Breached', [
                'ticket_id' => $sla->ticket_id,
                'sla_policy_id' => $sla->sla_policy_id,
                'sla_type' => $sla->sla_type,
                'target_time' => $sla->target_time
            ]);

            // TODO: Send notification to assigned agent
            // TODO: Trigger automation rules for SLA breach

        } catch (\Exception $e) {
            Log::error('SLA breach notification failed', [
                'sla_id' => $sla->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('SLA status update job failed', [
            'error' => $exception->getMessage()
        ]);
    }
}
