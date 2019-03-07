<?php


namespace App;


use App\Jobs\ProcessOutput;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OutputFetcher
{
    public $client;

    public $apiUri;

    public $total;

    public $qty = 1000;

    public $offset = 0;

    public $issn;

    public $records;

    public $journalID;

    public function __construct(Client $client, Journal $journal)
    {
        $this->client = $client;
        $this->journal = $journal;
        $this->apiUri = 'http://api.crossref.org/works?filter=issn:'. $journal->issn;
        $this->setTotal(
            json_decode($this->client->request('GET', $this->buildURL())->getBody())->message->{'total-results'}
        );
    }

    /**
     * @param array $records
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetch($records = [])
    {



        while ($this->offset < $this->total) {
            $res = $this->client->request('GET', $this->buildUrl());
            $decoded_items = json_decode($res->getBody())->message->items;
            foreach ($decoded_items as $item) {


            if (isset($item->title)) { // Filter out the ones with no titles
                $output = Output::updateorCreate(['doi' => $item->DOI],
                    [
                        'doi'=> $item->DOI,
                        'journal_id' => $this->journalID,
                        'reference_count' => $item->{'reference-count'},
                        'url'=>$item->URL,
                        'title'=>$item->title[0],
                        'issn'=>$item->ISSN[0],
                        'publisher'=>$item->publisher,
                        'language'=>$item->language,
                        'is_referenced_by'=>$item->{'is-referenced-by-count'}

                    ] );

                //Post processing
                ProcessOutput::dispatch($output, $item->DOI)->onConnection('redis'); //grab the abstract

            }   };



            $this->offset += $this->qty;
        }
;


        return $records;
    }

    /**
     * @return string
     */
    public function buildUrl()
    {

        return $this->apiUri . http_build_query([
            'offset' => $this->offset,
            'rows' => $this->qty
        ]);
    }


    /**
     * @param $url
     */
    public function setApiUri($url)
    {


        $this->apiUri = $url;
    }

    /**
     * @return mixed
     */
    public function getApiUri()
    {
        return $this->apiUri;
    }

    /**
     * @param $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }


    public function getTotal($total)
    {
        return $this->total;
    }



}