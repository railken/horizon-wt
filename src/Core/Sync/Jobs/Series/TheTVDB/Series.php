<?php

namespace Core\Sync\Jobs\Series\TheTVDB;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Core\Sync\Series\TheTVDB\TheTVDBManager;


class Series implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * ID
     */
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $manager = new TheTVDBManager();
        $manager->sync($this->id);

        foreach ($manager->getMedia($this->id) as $media) {

            dispatch((new Media($this->id, $media->type, $media->path))->onQueue('sync.resources.media'));
        }

    }
}
