<?php

namespace App\Console\Commands;

use App\Models\WhatsappBroadcast;
use App\Jobs\SendWhatsappBroadcastJob;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProcessScheduledBroadcasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:process-scheduled-broadcasts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled WhatsApp broadcasts that are due to be sent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing scheduled broadcasts...');

        // Find broadcasts that are scheduled and due to be sent
        $broadcasts = WhatsappBroadcast::where('whatsappbroadcast_status', 'scheduled')
            ->where('whatsappbroadcast_scheduled_at', '<=', Carbon::now())
            ->get();

        if ($broadcasts->isEmpty()) {
            $this->info('No scheduled broadcasts found.');
            return 0;
        }

        $count = 0;
        foreach ($broadcasts as $broadcast) {
            try {
                // Update status to sending
                $broadcast->whatsappbroadcast_status = 'sending';
                $broadcast->whatsappbroadcast_started_at = Carbon::now();
                $broadcast->save();

                // Dispatch the job
                dispatch(new SendWhatsappBroadcastJob($broadcast));

                $this->info("Broadcast #{$broadcast->whatsappbroadcast_id} ({$broadcast->whatsappbroadcast_name}) dispatched for sending.");
                $count++;

            } catch (\Exception $e) {
                $this->error("Failed to process broadcast #{$broadcast->whatsappbroadcast_id}: {$e->getMessage()}");

                // Mark as failed
                $broadcast->whatsappbroadcast_status = 'failed';
                $broadcast->save();
            }
        }

        $this->info("Successfully processed {$count} scheduled broadcast(s).");
        return 0;
    }
}
