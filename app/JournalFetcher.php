<?php


namespace App;


use App\Jobs\ProcessDois;
use hamburgscleanest\LaravelGuzzleThrottle\Facades\LaravelGuzzleThrottle;
use Illuminate\Support\Facades\Log;

class JournalFetcher
{

    public $total;

    public $startQty = 0;

    public $offset = 100;

    public $records;

    public $res;

    public $journal;

    public $cursor = '*';

    public $filter = 'type:journal-article';

    public $mailto = 'afletcher53@gmail.com';

    public $rows = 1000;

    protected $issn;

    protected $DOIurl;


    public function __construct($issn)
    {
        $this->issn = $issn;

    }

    public function fetch()
    {
        $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
        $res = $client->get($this->getJournalUrl($this->issn));
        $decoded_items = json_decode($res->getBody())->message;

        //Get the PRINT issn
        if (isset($decoded_items->{'issn-type'})) {
            $this->issn = collect($decoded_items->{'issn-type'})->where('type', 'print')->first();
        }
        if (isset($decoded_items->{'issn-type'})) {
            $this->electronic_issn = collect($decoded_items->{'issn-type'})->where('type', 'electronic')->first();
        }
        if (!isset($decoded_items->{'issn-type'}) && isset($decoded_items->ISSN)) {
            $this->issn = $decoded_items->ISSN;
        };


        $this->journal = Journal::updateorCreate(['issn' => $this->issn->value], [

            'issn' => $this->issn->value ?: null,
            'eissn' => $this->electronic_issn->value ?: null,
            'title' => $decoded_items->title ?: null,
            'publisher' => $decoded_items->publisher?: null,

        ]);


        //get the total journal articles
        $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
        $res = $client->get($this->getDOIUri($this->issn->value));
        $decoded_items = json_decode($res->getBody())->message;

        //Set and save the Total journal articles.
        $this->setTotal($decoded_items->{'total-results'});
        $this->journal->total_articles = $this->getTotal();
        $this->journal->save();




     //   $this->rows = $this->getTotal() < $this->offset ? $this->getTotal() : $this->offset;


        //TODO - Ensure that loops through every results

        while ($this->startQty < $this->getTotal()) {

            Log::alert('*********starting number' . $this->startQty);

            $this->client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
            $res = $this->client->get($this->getDOIUri($this->journal->issn));
            $decoded_items = json_decode($res->getBody())->message->items;
            foreach ($decoded_items as $item) {
                ProcessDois::dispatch($item->DOI, $this->journal);
            }
            $this->cursor = \GuzzleHttp\json_decode($res->getBody())->message->{'next-cursor'};
            $this->startQty += $this->rows;
            Log::alert($this->startQty . ' rows ' . $this->rows);
        }
    }

    public function getTotal()
    {
        return $this->total;
    }


    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function checkStatusCode(){
        $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
        $res = $client->get($this->getJournalUrl($this->issn));

        return $res->getStatusCode();
    }

    public function getDOIUri($issn)
    {
        $this->DOIurl = 'https://api.crossref.org/v1/journals/'.$issn.'/works?';
        $fields = array(
            'rows' => $this->rows, 'cursor' => $this->cursor, 'filter' => $this->filter, 'mailto' => $this->mailto
        );
        $this->DOIurl = $this->DOIurl.http_build_query($fields);


        return $this->DOIurl;
    }


    public function getJournalUrl($issn)
    {
        $this->journalurl = 'https://api.crossref.org/v1/journals/'.$issn;


        return $this->journalurl;

    }
}
