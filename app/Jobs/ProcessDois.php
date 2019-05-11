<?php

namespace App\Jobs;

use App\Journal;
use App\Output;
use App\OutputFetcher;
use App\Providers\HelperServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;

class ProcessDois implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    //TODO Simplify to a class like JournalFetcher
    /**
     * Create a new job instance.
     *
     * @return void
     */


    public $tries = 3;
    protected $fetcher;
    protected $doi;
    protected $journal;


    public function __construct($doi, Journal $journal)
    {

        $this->onQueue('journals');
        $this->onConnection('redis');
        $this->doi = $doi;
        $this->journal = $journal;
        $this->fetcher =   new OutputFetcher($this->doi, $journal);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::funnel('key')->limit(1)->then(function () {

            $this->fetcher->fetch();

        }, function () {
            // Could not obtain lock...

            return $this->release(10);
        });
    }

}
