<?php

namespace App\Console\Commands\Spark;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StoreAdditionalPerformanceIndicatorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spark:additional-kpi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store the additional performance indicators.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('performance_indicators')
            ->whereDate('created_at', '=', Carbon::today())
            ->latest('created_at')
            ->update([
                'total_users' => DB::table('users')->count(),
                'total_threads' => DB::table('threads')->whereNull('deleted_at')->count(),
                'new_threads' => DB::table('threads')
                                    ->whereNull('deleted_at')
                                    ->whereDate('created_at', '=', Carbon::today())
                                    ->count(),
            ]);
    }
}
