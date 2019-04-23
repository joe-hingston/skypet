<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartSkynet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:skypet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the skypet for testing purposes';

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
        $this->line('<fg=red>Clearing logs</>');
        exec('rm ' . storage_path('logs/laravel*'));
        $this->line('<fg=red>Migrate and refreshing databases</>');
        sleep(1);
        $this->call('migrate:refresh');
        $this->line('<fg=red>Flushing the redis queue</>');
        sleep(1);
        exec('redis-cli flushall');
        sleep(1);
        $this->line('<fg=red>Starting Horizon</>');
        $this->call('horizon');
        sleep(1);
        $this->line('<fg=red>Adding test journal</>');
        $this->call('journal:add', ['issn' => '0891-6640']);
        //
    }


}
