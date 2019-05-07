<?php

namespace App\Jobs;

use App\Journal;
use hamburgscleanest\LaravelGuzzleThrottle\Facades\LaravelGuzzleThrottle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;


class ProcessJournal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */




    public $total;

    public $qty = 200;

    public $offset = 100;

    public $records;

    public $res;

    public $journal;

    public $cursor = '*';

    public $filter = 'type:journal-article';

    public $mailto = 'afletcher53@gmail.com';

    public $rows = 1000;

    protected $issn;



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


        Redis::funnel('key')->limit(1)->then(function () {

            $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
            $res = $client->get($this->getJournalUrl($this->issn));
            $decoded_items = json_decode($res->getBody())->message;


            //Get the PRINT issn
            if(isset($decoded_items->{'issn-type'})){ $this->issn = collect($decoded_items->{'issn-type'})->where('type', 'print')->first();}
            if(!isset($decoded_items->{'issn-type'}) && isset($decoded_items->ISSN)){$this->issn = $decoded_items->ISSN;};

            //TODO get all the information from the journal to put into the database
            $this->journal = Journal::updateorCreate(['issn' => $this->issn->value], [
                'issn' => $this->issn->value,
                'total_articles' => $this->getTotal(),
                'title' => $decoded_items->title
            ]);


            //get the total journal articles
            $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
            $res = $client->get($this->getDOIUri($this->issn->value));
            $decoded_items = json_decode($res->getBody())->message;
            $this->setTotal($decoded_items->{'total-results'});

            //If total articles are less that the row quantity, set to total articles, if not set to offset.
            $this->rows = ($this->getTotal() < $this->offset ? $this->getTotal() : $this->offset);

            while ($this->qty < $this->getTotal()) {

                $this->client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
                $res = $this->client->get($this->getDOIUri($this->journal->issn));
                $decoded_items = json_decode($res->getBody())->message->items;
                foreach ($decoded_items as $item) {
                    ProcessDois::dispatch($item->DOI, $this->journal)->onConnection('redis')->onQueue('journals');

                }
                $this->cursor = \GuzzleHttp\json_decode($res->getBody())->message->{'next-cursor'};
                $this->qty += $this->rows;
            }


        }, function () {
            // Could not obtain lock...

            return $this->release(10);
        });

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


    /**
     * @return mixed
     */
    public function getDOIUri($issn)
    {
        $this->DOIurl = 'https://api.crossref.org/v1/journals/'.$issn.'/works?';
        $fields = array(
            'rows' => $this->rows, 'cursor' => $this->cursor, 'filter' => $this->filter, 'mailto' => $this->mailto
        );
        $this->DOIurl = $this->DOIurl.http_build_query($fields);

        return $this->DOIurl;
    }

    /**
     * @param $url
     */
    public function getJournalUrl($issn)
    {
        $this->journalurl = 'https://api.crossref.org/v1/journals/'.$issn;


        return $this->journalurl;

    }
}
