<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExternalContestService;
use Illuminate\Support\Facades\Cache;

class RefreshExternalContests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contests:refresh-external';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh and re-cache the external contests list from APIs';

    /**
     * Execute the console command.
     */
    public function handle(ExternalContestService $service)
    {
        $this->info('Clearing cached external contests list...');
        Cache::forget('all_external_contests');

        $this->info('Fetching and caching fresh contests list...');
        $contests = $service->getUpcomingContests();

        $this->info('Successfully cached ' . count($contests) . ' external contests.');
        return Command::SUCCESS;
    }
}
