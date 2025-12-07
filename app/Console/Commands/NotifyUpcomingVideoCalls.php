<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VideoCall;
use App\Services\VideoCallNotificationService;
use Carbon\Carbon;

class NotifyUpcomingVideoCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videocalls:notify-upcoming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify students about video calls starting soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notificationService = app(VideoCallNotificationService::class);
        
        // Find meetings starting in next 15 minutes
        $upcomingCalls = VideoCall::where('status', 'scheduled')
            ->whereBetween('scheduled_at', [
                now(),
                now()->addMinutes(15)
            ])
            ->whereDoesntHave('notifications', function($query) {
                $query->where('type', 'App\Notifications\VideoCallReminder')
                    ->where('created_at', '>', now()->subHour());
            })
            ->get();

        $count = 0;
        foreach ($upcomingCalls as $call) {
            $notified = $notificationService->notifyMeetingStarted($call);
            $count += $notified;
            $this->info("Notified {$notified} students about: {$call->title}");
        }

        $this->info("Total notifications sent: {$count}");
        return Command::SUCCESS;
    }
}
