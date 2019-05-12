<?php


namespace App;


use GuzzleHttp\Exception\GuzzleException;
use hamburgscleanest\LaravelGuzzleThrottle\Facades\LaravelGuzzleThrottle;
use Illuminate\Support\Facades\Storage;

class OutputFetcher
{


    public $mailto = 'afletcher53@gmail.com';
    protected $issn;
    protected $electronic_issn;


    public function __construct($doi, Journal $journal)
    {
        $this->journal = $journal;
        $this->doi = $doi;
    }

    /**
     * @param array $records
     * @return array
     * @throws GuzzleException
     */


    public function fetch()

    {

        $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);

        $res = $client->get($this->buildFilterUrl());
        Storage::append('Fuzzle.log', $res->getStatusCode());
        $decoded_items = json_decode($res->getBody())->message;


        if (isset($decoded_items->title)) { // Filter out the ones with no titles

            if (isset($decoded_items->{'issn-type'})) {
                $this->issn = collect($decoded_items->{'issn-type'})->where('type', 'print')->first();
            }
            if (isset($decoded_items->{'issn-type'})) {
                $this->electronic_issn = collect($decoded_items->{'issn-type'})->where('type', 'electronic')->first();
            }
            if (!isset($decoded_items->{'issn-type'}) && isset($decoded_items->ISSN)) {
                $this->issn = $decoded_items->ISSN;
            };



            $fields = [

                //TODO add in all available information scraped from CrossRef


                'doi' => isset($decoded_items->DOI) ? $decoded_items->DOI : null,
                'reference_count' => isset($decoded_items->{'reference-count'}) ? $decoded_items->{'reference-count'} : null,
                'url' => isset($decoded_items->URL) ? $decoded_items->URL : null,
                'publisher' => isset($decoded_items->publisher) ? $decoded_items->publisher: null,
                'language' => isset($decoded_items->language) ? $decoded_items->language: null,
                'is_referenced_by' => isset($decoded_items->{'is-referenced-by-count'})? $decoded_items->{'is-referenced-by-count'}: null,
                'issn' => isset($this->issn->value) ? $this->issn->value: null,
                'created' => isset($decoded_items->created->{'date-time'}) ? date("Y-m-d H:i:s", strtotime($decoded_items->created->{'date-time'})) : null,
                'desposited' => isset($decoded_items->deposited->{'date-time'}) ? date("Y-m-d H:i:s", strtotime($decoded_items->deposited->{'date-time'})) : null,
                'eissn' => isset($this->electronic_issn->value) ? $this->electronic_issn->value : null,
                'page' => isset($decoded_items->page) ? $decoded_items->page : null,
                'source' => isset($decoded_items->source) ? $decoded_items->source : null,
                'prefix' => isset($decoded_items->prefix) ? $decoded_items->prefix : null,
                'volume' => isset($decoded_items->volume) ? $decoded_items->volume : null,
                'member' => isset($decoded_items->member) ? $decoded_items->member : null,
                'score' => isset($decoded_items->score) ? $decoded_items->score : null,
                'issue' => isset($decoded_items->issue) ? $decoded_items->issue : null,

            ];



            //Flatten array of titles
            $fields['title'] = is_array($decoded_items->title) ? array_shift($decoded_items->title) : $decoded_items->title;
            if(isset($decoded_items->link)){$fields['miningurl'] = is_array($decoded_items->link) ? array_shift($decoded_items->link)->URL : null;}
            $fields['license'] = is_array($decoded_items->license) ? array_shift($decoded_items->license)->URL : null;
            $fields['short-container-title'] = is_array($decoded_items->{'short-container-title'}) ? array_shift($decoded_items->{'short-container-title'}) : null;
            $fields['container-title'] = is_array($decoded_items->{'container-title'}) ? array_shift($decoded_items->{'container-title'}) : null;

            //TODO Add in authors in a seperate relationship table?

            $output = $this->journal->outputs()->updateorCreate(['doi' => $decoded_items->DOI], $fields);


            //TODO search through the references


        }


        return $output;

    }

    public function buildFilterUrl()
    {
        $this->filterUrl = 'https://api.crossref.org/works/:'.$this->doi.'?mailto=' . $this->mailto;
        return $this->filterUrl;
    }


}
