<?php

namespace App\Jobs;

use App\Journal;
use App\OutputFetcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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


    protected $fetcher;
    protected $doi;
    protected $journal;
    protected $output;


    public function __construct($doi, Journal $journal)
    {

        $this->onQueue('default_long');
        $this->onConnection('redis');
        $this->doi = $doi;
        $this->journal = $journal;
        $this->fetcher =   new OutputFetcher($this->doi, $journal);
    }

    public function handle()
    {
        Redis::funnel('key')->limit(1)->then(function () {
            $this->output = $this->fetcher->fetch();

            //TODO fire off abstracts when job completed
        }, function () {
            // Could not obtain lock...
            return $this->release(10);
        });
    }

    public function failed($exception)
    {
        Log::alert('DOI has failed - Why?');
        Log::critical($exception->getMessage());
    }

}
