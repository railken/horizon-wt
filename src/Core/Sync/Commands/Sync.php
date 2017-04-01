<?php

namespace Core\Sync\Commands;

use Illuminate\Console\Command;
use Core\Sync\SyncManager;
use Core\Sync\Jobs\Series\SyncSeries;


use Core\Sync\Series\TheTVDB\TheTVDBManager;


class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:sync:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync with a database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->manager = new SyncManager();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {  
        
        dispatch((new SyncSeries())->onQueue('sync.resources'));
        // dispatch((new SyncManga()->onQueue('sync.resources'));
        $this->info('Added to queue');
    }
}