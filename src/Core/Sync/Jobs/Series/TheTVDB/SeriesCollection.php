<?php

namespace Core\Sync\Jobs\Series\TheTVDB;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Core\Sync\Series\TheTVDB\TheTVDBManager;

class SeriesCollection implements ShouldQueue
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
        $manager = new TheTVDBManager();

        foreach ($manager->toUpdate($this->force) as $series) {
            dispatch((new Series($series->id, $this->force))->onQueue('sync.resources'));
        }
    }
}
