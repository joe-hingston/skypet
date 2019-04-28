<?php

namespace App\Http\Controllers;

use App\Journal;
use hamburgscleanest\LaravelGuzzleThrottle\Facades\LaravelGuzzleThrottle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     *
     */


    public $journalurl;

    public $filterUrl;

    public $total;

    public $qty = 200;

    public $offset = 100;

    public $issn;

    public $records;

    public $res;

    public $journal;

    public $cursor = '*';

    public $filter = 'type:journal-article';

    public $mailto = 'afletcher53@gmail.com';

    public $DOIurl;

    public $rows;



    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($issn)
    {

        $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
        $res = $client->get($this->getJournalUrl($issn));
        $decoded_items = json_decode($res->getBody())->message;

     //   dd($decoded_items);
       //
       //

        //Get the PRINT issn
        if(isset($decoded_items->{'issn-type'})){ $this->issn = collect($decoded_items->{'issn-type'})->where('type', 'print')->first();}
        if(!isset($decoded_items->{'issn-type'}) && isset($decoded_items->ISSN)){$this->issn = $decoded_items->ISSN;};


        $journal = Journal::updateorCreate(['issn' => $this->issn->value], [
            'issn' => $this->issn->value,
            'total_articles' => $this->getTotal(),
            'title' => $decoded_items->title
        ]);


        //get the total journal articles
        $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);


        $res = $client->get($this->getDOIUri($issn));
        $decoded_items = json_decode($res->getBody())->message;
        $this->setTotal($decoded_items->{'total-results'});

        //If total articles are less that the row quantity, set to total articles, if not set to offset.
        $this->rows = ($this->getTotal() < $this->offset ? $this->getTotal() : $this->offset);




        while ($this->qty < $this->getTotal()) {

            $this->client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);

            $res = $this->client->get($this->getDOIUri($journal->issn));
            $decoded_items = json_decode($res->getBody())->message->items;

            foreach ($decoded_items as $item) {
                Storage::append('DOIlist.text', $item->DOI);

            }
            $this->cursor = \GuzzleHttp\json_decode($res->getBody())->message->{'next-cursor'};
            $this->qty += $this->rows;
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
        $fields = array('rows' => $this->rows, 'cursor' => $this->cursor, 'filter' => $this->filter, 'mailto' => $this->mailto);
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
