<?php

namespace Core\Sync\Jobs\Series;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Core\Sync\Jobs\Series\TheTVDB as TheTVDB;


class SyncSeries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Force sync
     *
     * @var boolean
     */
    protected $force;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($force = false)
    {
        $this->force = $force;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dispatch((new TheTVDB\SeriesCollection($this->force))->onQueue('sync.resources'));
    }
}
