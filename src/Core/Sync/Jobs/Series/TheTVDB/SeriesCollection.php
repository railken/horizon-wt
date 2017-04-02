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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $manager = new TheTVDBManager();

        foreach ($manager->toUpdate() as $series) {
            dispatch((new Series($series->id))->onQueue('sync.resources'));
        }
    }
}
