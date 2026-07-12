<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contest;
use Illuminate\Support\Carbon;

class UpdateContestStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contest:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-open and auto-close contests based on their start and end times';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // 1. Auto-open contests: if starts_at <= now, ends_at > now (or null), and is_active is false
        $toActivate = Contest::whereNotNull('starts_at')
            ->where('starts_at', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>', $now);
            })
            ->where('is_active', false)
            ->get();

        foreach ($toActivate as $contest) {
            $contest->is_active = true;
            $contest->save();
            $this->info("Activated contest: {$contest->title} (ID: {$contest->id})");
        }

        // 2. Auto-close contests: if ends_at <= now and is_active is true
        $toDeactivate = Contest::whereNotNull('ends_at')
            ->where('ends_at', '<=', $now)
            ->where('is_active', true)
            ->get();

        foreach ($toDeactivate as $contest) {
            $contest->is_active = false;
            $contest->save();
            $this->info("Deactivated contest: {$contest->title} (ID: {$contest->id})");
        }

        $this->info('Contest statuses updated successfully.');
        return Command::SUCCESS;
    }
}
