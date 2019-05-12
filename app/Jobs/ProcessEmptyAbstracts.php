<?php

namespace App\Jobs;

use App\AbstractFetcher;
use App\Output;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessEmptyAbstracts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('abstracts');
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Redis::throttle('key')->allow(1)->every(5)->then(function () {
            $outputs = Output::where('abstract', null)->get();

            foreach ($outputs as $output) {
                $abstract = New AbstractFetcher($output);
                $abstract->fetch();
            }

        }, function () {
            return $this->release(10);

        });
        //
    }

    public function getAPIUrl ($doi){
        return  'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&WebEnv=1&usehistory=y&term='.$doi;
    }

}
