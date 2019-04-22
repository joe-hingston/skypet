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
use Illuminate\Support\Facades\Event;
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

    public $qty = 200;

    public $offset = 0;

    public $issn;

    public $records;

    public $res;

    public $journal;

    public function __construct($issn)
    {
        $this->issn = $issn;
        $this->onQueue('journals');
        $this->onConnection('redis');
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
                $this->setTotal(json_decode($this->client->get($this->getWorksUrl())->getBody())->message->counts->{'total-dois'});
                $journal = Journal::updateorCreate(['issn' => $this->issn], [
                    'issn' => $this->issn,
                    'total_articles' => $this->getTotal(),
                    'title' => $journalinfo->message->title
                ]);

                //Fire off events for creation
                if($journal->wasRecentlyCreated){Event::fire('reference.notnulljournal', $journal);} else {Event::fire('reference.referenceNullJournal', $journal);};

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
        return 'https://api.crossref.org/journals/'.$this->issn.'works?mailto='.HelperServiceProvider::getApiemail();
    }

    public function buildFilterUrl()
    {

        $this->filterUrl = 'http://api.crossref.org/works?filter=issn:'.$this->issn.'?mailto='.HelperServiceProvider::getApiemail();
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

        while ($this->offset < $this->getTotal()) {

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

}
