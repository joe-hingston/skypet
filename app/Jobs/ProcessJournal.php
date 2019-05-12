<?php

namespace App\Jobs;

use App\JournalFetcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;


class ProcessJournal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $fetcher;
    /**
     * Create a new job instance.
     *
     * @return void
     */


    public function __construct($issn)
    {

        $this->onQueue('default_long');
        $this->onConnection('redis');
        $this->fetcher =   new JournalFetcher($issn);

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

    public function failed($exception)
    {
        Log::alert('Journal has failed - Why?');
        Log::critical($exception->getMessage());
    }

}
