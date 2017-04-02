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
     * Series id
     *
     * @var id
     */
    protected $id;

    /**
     * Force sync
     *
     * @var boolean
     */
    protected $force;

    /**
     * Create a new job instance.
     *
     * @param integer $id
     * @param boolean $force
     *
     * @return void
     */
    public function __construct($id, $force = false)
    {
        $this->id = $id;
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
        $results = $manager->sync($this->id, $this->force);

        if (!$results)
            return;

        foreach ($manager->getMedia($this->id) as $media) {

            dispatch((new Media($this->id, $media->type, $media->path))->onQueue('sync.resources.media'));
        }

    }
}
