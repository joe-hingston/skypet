<?php


namespace App;


use App\Jobs\ProcessDois;
use hamburgscleanest\LaravelGuzzleThrottle\Facades\LaravelGuzzleThrottle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
      try {
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

          Log::error($decoded_items);

          $this->journal = Journal::updateorCreate(['issn' => $this->issn->value], [
              'title' => $decoded_items->title,
              'publisher' => $decoded_items->publisher,
              'issn' => isset($this->issn->value) ? $this->issn->value : null,
              'eissn' => isset($this->electronic_issn->value) ? $this->electronic_issn->value : null,
              'totaldois' => $decoded_items->counts->{'total-dois'},
          ]);


          //get the total journal articles
          $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
          $res = $client->get($this->getDOIUri($this->issn->value));
          $decoded_items = json_decode($res->getBody())->message;

          //Set and save the Total journal articles.
          $this->setTotal($decoded_items->{'total-results'});
          $this->journal->total_articles = $this->getTotal();
          $this->journal->save();


          $doiCollection = new Collection();
          Log::alert('Processing ' . $this->journal->title);

          while ($this->startQty < $this->getTotal()) {

              $this->client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
              $res = $this->client->get($this->getDOIUri($this->journal->issn));
              $decoded_items = json_decode($res->getBody())->message->items;

              foreach ($decoded_items as $item) {
                  $doiCollection->push($item->DOI);
              }

              $this->cursor = \GuzzleHttp\json_decode($res->getBody())->message->{'next-cursor'};
              $this->startQty += $this->rows;

          }

          foreach ($doiCollection as $doi) {
              ProcessDois::dispatch($doi, $this->journal);
          }

          Log::alert('Completed processing of  ' . $this->journal->title);

      } catch (\GuzzleHttp\Exception\RequestException $e) {
          if ($e->hasResponse()) {
              $message = $e->getResponse()->getBody();
              return (string) $message;
          }
          else {
              return $e->getMessage();
          }
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
