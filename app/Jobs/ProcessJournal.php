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

        Redis::throttle('key')->allow(3)->every(1)->then(function () {

            $this->client = new Client();
            try {

                $journalinfo = json_decode($this->client->get($this->getWorksUrl())->getBody());
                $this->setTotal(json_decode($this->client->get($this->buildFilterUrl())->getBody())->message->{'total-results'});


                //get the details of the journal from crossref;


                //add the journal if not new
                $journal = Journal::updateorCreate(['issn' => $this->issn], [
                    'issn' => $this->issn,
                    'total_articles' => $this->getTotal(),
                    'title' => $journalinfo->message->title
                ]);

                $this->journal = Journal::find($journal->id);

                //get the DOI's from the journal
                $this->fetchDoiList();
            } catch (RequestException $e) {
                return $e->getResponse()->getStatusCode();
            }

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

                if (isset($item->title)) { // Filter out the ones with no titles

                    $fields = [
                        'doi' => $item->DOI,
                        'reference_count' => $item->{'reference-count'},
                        'url' => $item->URL,
                        'title' => $item->title[0],
                        'issn' => $item->ISSN[0],
                        'publisher' => $item->publisher,
                        'language' => $item->language,
                        'is_referenced_by' => $item->{'is-referenced-by-count'}
                    ];

                    if (!empty($item->title)) {
                        $fields['title'] = is_array($item->title ? array_shift($item->title) : $item->title;
                    }

                    $output = $this->journal->outputs()->updateorCreate(['doi' => $item->DOI], $fields);

                    //get the abstract information
                    ProcessAbstract::dispatch($output)->onConnection('redis')->onQueue('abstracts');

                    //sleep for 1 second as to not hit the API limit
                    sleep(HelperServiceProvider::getCrossRefRateLimit);
                    //get the reference list and process
                    //loop through references, place on outputreference stack
                    //process all the articles from that journal if new
                    //job maximum time is 15 minutes time out, need to speed up the checking
                    //Stops at #44 journal - WHY?

                }
            }

            $this->offset += $this->qty;

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
