<?php


namespace App;


use App\Jobs\ProcessAbstract;
use App\Jobs\ProcessReference;
use GuzzleHttp\Client;

class OutputFetcher
{
    public $client;

    public $apiUri;

    public $total;

    public $qty = 1;

    public $offset = 0;

    public $issn;

    public $records;

    public $journalID;

    public function __construct(Client $client, Journal $journal)
    {
        $this->client = $client;
        $this->journal = $journal;
        $email = "?mailto=afletcher53@gmail.com";

        $this->setApiUri('http://api.crossref.org/works?filter=issn:'. $journal->issn . $email);
        $this->setTotal(
            json_decode($this->client->request('GET', $this->buildURL())->getBody())->message->{'total-results'}
        );
    }

    /**
     * @param array $records
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetch()
    {
        while ($this->offset < $this->total) {

            $res = $this->client->request('GET', $this->buildUrl());

            $decoded_items = json_decode($res->getBody())->message->items;

            foreach ($decoded_items as $item) {

                if (isset($item->title)) { // Filter out the ones with no titles

                    $output  = $this->journal->outputs()->updateorCreate(['doi' => $item->DOI],
                        [
                            'doi'=> $item->DOI,
                            'reference_count' => $item->{'reference-count'},
                            'url'=>$item->URL,
                            'title'=>$item->title[0],
                            'issn'=>$item->ISSN[0],
                            'publisher'=>$item->publisher,
                            'language'=>$item->language,
                            'is_referenced_by'=>$item->{'is-referenced-by-count'}
                        ]);

                    if (!isset($item->reference)) continue;
                    foreach($item->reference as $reference){
                        if (!isset($reference->DOI)) continue;
                            $reference =  $output->outputreferences()->updateorCreate(['doi'=>$reference->DOI],
                                ['doi'=>$reference->DOI]);

                            //new output found so lets process it
                        ProcessReference::dispatch($reference->DOI)->onConnection('redis');

                          }
                    //Post processing
                    ProcessAbstract::dispatch($output, $item->DOI)->onConnection('redis'); //grab the abstract

                }   
            }

            $this->offset += $this->qty;

        }
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