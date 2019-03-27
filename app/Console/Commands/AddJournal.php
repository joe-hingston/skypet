<?php

namespace App\Console\Commands;

use App\Jobs\ProcessJournal;
use Illuminate\Console\Command;
use Validator;


class AddJournal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'journal:add';

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
        $issn = $this->ask('What is the journal ISSN?');
        $validator = Validator::make([
            'issn' => $issn,
        ], [
            'issn' => ['required', 'min:9'],
        ]);
        If ($validator->fails()) {
            $this->info('Journal Not added, see error message below');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        $validator->errors()->all();
        ProcessJournal::dispatch($issn)->onConnection('redis')->onQueue('journals');
    }
}
