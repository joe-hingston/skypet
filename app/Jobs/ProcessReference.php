<?php

namespace App\Jobs;

use App\Journal;
use App\Providers\HelperServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;

class ProcessReference implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;
    protected $doi;
    public $client;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($doi)
    {
        $this->doi = $doi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Redis::throttle('key')->allow(1)->every(1)->then(function () {
            $this->client = new Client();
            $res = $this->client->get($this->buildFilterUrl());

            $decoded_items = json_decode($res->getBody())->message;

            //check to see what ISSN is given, and exists in the database
            $this->issn = collect($decoded_items->{'issn-type'})->where('type', 'print')->first();


            $journal = Journal::where('issn', $this->issn->value);
            if (!$journal->exists()) {
                ProcessJournal::dispatch($this->issn->value)->onConnection('redis')->onQueue('journals');
            };

        }, function () {
            return $this->release(10);

        });
    }


    public function buildFilterUrl()
    {
        $this->filterUrl = 'https://api.crossref.org/works/:'.$this->doi.'?mailto='.HelperServiceProvider::getApiemail();
        return $this->filterUrl;
    }
}
