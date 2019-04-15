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

class ProcessJournal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $client;

    public $apiUri;

    public $filterUrl;

    public $total;

    public $qty = 1000;

    public $offset = 0;

    public $issn;

    public $records;

    public $res;

    public $journal;

    public function __construct($ISSN)
    {
        $this->issn = $ISSN;

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

                $journalinfo = json_decode($this->client->get($this->getWorksUrl())->getBody());
                $this->setTotal(json_decode($this->client->get($this->buildFilterUrl())->getBody())->message->{'total-results'});

                $journal = Journal::updateorCreate(['issn' => $this->issn], [
                    'issn' => $this->issn,
                    'total_articles' => $this->getTotal(),
                    'title' => $journalinfo->message->title
                ]);

                $this->journal = Journal::find($journal->id);
                $this->fetchDoiList();
            } catch (RequestException $e) {
                return $e->getResponse()->getStatusCode();
            }
        }, function () {
            return $this->release(10);

        });

    }

    public function getWorksUrl()
    {
        return 'https://api.crossref.org/journals/' . $this->issn . 'mailto=' . HelperServiceProvider::getApiemail();
    }

    public function buildFilterUrl()
    {
        $this->filterUrl = 'http://api.crossref.org/works?filter=issn:' . $this->issn . '?mailto=' . HelperServiceProvider::getApiemail();
        return $this->filterUrl . http_build_query([
                'offset' => $this->offset,
                'rows' => $this->qty
            ]);
    }

    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function fetchDoiList()
    {
        while ($this->offset < $this->total) {

            $res = $this->client->get($this->buildFilterUrl());
            $decoded_items = json_decode($res->getBody())->message->items;

            foreach ($decoded_items as $item) {

                ProcessDois::withChain([
                    new ProcessAbstract($item->DOI),
                ])->dispatch($item->DOI, $this->journal);


                $this->offset += $this->qty;

        }
        }
    }

    /**
     * @return mixed
     */
    public function getApiUri()
    {
        return $this->apiUri;
    }

    /**
     * @param $url
     */
    public function setApiUri($url)
    {
        $this->apiUri = $url;
    }

    private function getFilterUrl()
    {
        return 'http://api.crossref.org/works?filter=issn:' . $this->issn . '?mailto=' . HelperServiceProvider::getApiemail();
    }
}
