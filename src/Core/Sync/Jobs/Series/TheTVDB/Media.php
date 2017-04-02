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
     *
     * @var integer
     */
    protected $id;

    /**
     * Type of media
     *
     * @var string
     */
    protected $type;

    /**
     * Url media
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($series_id, $type, $path)
    {
        $this->series_id = $series_id;
        $this->type = $type;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $manager = new TheTVDBManager();
        $manager->syncMedia($this->series_id, $this->type, $this->path);

    }
}
