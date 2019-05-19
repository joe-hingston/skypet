<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class flushredisqueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush:redis';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('<fg=red>Stopping Horizon</>');
        exec('supervisorctl stop worker-redis-long-running:*');
        exec('supervisorctl stop worker-redis-abstract-long-running:*');
        exec('sudo supervisorctl stop all');
        exec('echo "" > '.storage_path('logs/laravel.log'));
        exec('echo "" > '.storage_path('horizon.log'));
        $files = Arr::where(Storage::disk('log')->files(), function($filename) {
            return Str::endsWith($filename,'.log');
        });


        $count = count($files);

        if(Storage::disk('log')->delete($files)) {
            $this->info(sprintf('Deleted %s %s!', $count, Str::plural('file', $count)));
        } else {
            $this->error('Error in deleting log files!');
        }

        $this->info('Logs have been cleared');
            $this->line('<fg=red>Flushing the redis queue</>');
            exec('redis-cli flushall');
            $this->line('<fg=red>Restarting Horizon</>');
            exec('sudo supervisorctl start all');
            exec('supervisorctl start worker-redis-long-running:*');
            exec('supervisorctl start worker-redis-abstract-long-running:*');
        }

}
