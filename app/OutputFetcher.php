<?php


namespace App;


use GuzzleHttp\Client;

class OutputFetcher
{
    public $client;

    public $apiUri;

    public $total;

    public $qty = 100;

    public $offset = 0;

    public $issn;

    public function __construct(Client $client, $issn)
    {
        $this->client = $client;
        $this->issn = $issn;

        $this->apiUri = $this->setApiUri('http://api.crossref.org/works?filter=issn:'. $issn);

        $this->setTotal(
            json_decode($this->client->request('GET', $this->apiUri)->getBody())->message->{'total-results'}
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

                $return[] = ['doi' => $item->DOI];

            }

            $this->offset += $this->qty;
        }

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