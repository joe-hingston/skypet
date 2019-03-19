<?php

namespace App\Jobs;

use App\Journal;
use App\Providers\HelperServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;

class ProcessDois implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $journal;

    public $client;

    public $apiUri;

    public $filterUrl;

    public $total;

    public $issn;

    public $records;

    public $res;

    public $doi;

    public function __construct($doi, Journal $journal)
    {
        $this->doi = $doi;
        $this->journal = Journal::find($journal->id);
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
            try {
                $this->fetchDoiInformation();
            } catch (RequestException $e) {
                return $e->getResponse()->getStatusCode();
            }
        }, function () {
            return $this->release(10);

        });
    }

    public function fetchDoiinformation()

    {

        $res = $this->client->get($this->buildFilterUrl());
        $decoded_items = json_decode($res->getBody())->message;

        if (isset($decoded_items->title)) { // Filter out the ones with no titles
            $this->journal->outputs()->updateorCreate(['doi' => $decoded_items->DOI],
                [
                    'doi' => $decoded_items->DOI,
                    'reference_count' => $decoded_items->{'reference-count'},
                    'url' => $decoded_items->URL,
                    'title' => $decoded_items->title[0],
                    'issn' => $decoded_items->ISSN[0],
                    'publisher' => $decoded_items->publisher,
                    'language' => $decoded_items->language,
                    'is_referenced_by' => $decoded_items->{'is-referenced-by-count'}
                ]);
        }


    }


    public function buildFilterUrl()
    {
        $this->filterUrl = 'https://api.crossref.org/works/:' . $this->doi . '?mailto=' . HelperServiceProvider::getApiemail();
        return $this->filterUrl;
    }

}
