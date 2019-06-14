<?php

namespace App\Http\Controllers;

use App\HealthFunctions;
use App\Jobs\ProcessJournal;
use App\Journal;
use App\JournalFetcher;
use App\Output;
use App\User;
use Exception;
use hamburgscleanest\LaravelGuzzleThrottle\Facades\LaravelGuzzleThrottle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     *
     *
     */






    public function index()
    {
        return view('journals.index', [
            'Journals' => Journal::all(),
        ]);
    }
    public function health(Request $request)
    {

        $journal = Journal::find($request->id);


        $request = new HealthFunctions($journal);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {


        if (preg_match('/[1-9]\d{6}/', $request->issn)) {

            try {
                $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
                $res = $client->get($this->getJournalUrl($request->issn));

                if($res->getStatusCode()==200) {
                    ProcessJournal::dispatch($request->issn)->onConnection('redis');
                    $decoded_items = json_decode($res->getBody())->message;


                    if (isset($decoded_items->{'issn-type'})) {
                        $this->issn = collect($decoded_items->{'issn-type'})->where('type', 'print')->first();
                    }
                    if (isset($decoded_items->{'issn-type'})) {
                        $this->electronic_issn = collect($decoded_items->{'issn-type'})->where('type', 'electronic')->first();
                    }
                    if (!isset($decoded_items->{'issn-type'}) && isset($decoded_items->ISSN)) {
                        $this->issn = $decoded_items->ISSN;
                    };


                    $response = [
                        'title' => $decoded_items->title,
                        'publisher' => $decoded_items->publisher,
                        'issn' => $this->issn->value,
                        'eissn' => $this->electronic_issn->value,
                        'totaldois' => $decoded_items->counts->{'total-dois'},

                    ];


                    return view('layouts.journal.create', $response);
                }// Validate the value...
            } catch (Exception $e) {
                abort(403 , $e->getMessage());
            }

        } elseif (!preg_match('/[1-9]\d{6}/', $request->issn))
        {
            //TODO cleaner error page
            abort(403 , 'Incorrect ISSN syntax Provided');
        }

    }

    public function getJournalUrl($issn)
    {
        $this->journalurl = 'https://api.crossref.org/v1/journals/'.$issn;


        return $this->journalurl;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {


        if (preg_match('/[1-9]\d{6}/', $request->issn)) {

            try {
                $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://api.crossref.org']);
                $res = $client->get($this->getJournalUrl($request->issn));

                if($res->getStatusCode()==200) {
                    ProcessJournal::dispatch($request->issn)->onConnection('redis');
                    $decoded_items = json_decode($res->getBody())->message;


                    if (isset($decoded_items->{'issn-type'})) {
                        $this->issn = collect($decoded_items->{'issn-type'})->where('type', 'print')->first();
                    }
                    if (isset($decoded_items->{'issn-type'})) {
                        $this->electronic_issn = collect($decoded_items->{'issn-type'})->where('type', 'electronic')->first();
                    }
                    if (!isset($decoded_items->{'issn-type'}) && isset($decoded_items->ISSN)) {
                        $this->issn = $decoded_items->ISSN;
                    };

                    $response = [
                        'title' => $decoded_items->title,
                        'publisher' => $decoded_items->publisher,
                        'issn' => isset($this->issn->value) ? $this->issn->value : null,
                        'eissn' => isset($this->electronic_issn->value) ? $this->electronic_issn->value : null,
                        'totaldois' => $decoded_items->counts->{'total-dois'},

                    ];


                    return view('layouts.journal.create', $response);
                }// Validate the value...
            } catch (Exception $e) {
                abort(403 , $e->getMessage());
            }

        } elseif (!preg_match('/[1-9]\d{6}/', $request->issn))
        {
            //TODO cleaner error page
            abort(403 , 'Incorrect ISSN syntax Provided');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Journal $journal)
    {
       return view('journals.single', compact('journal',$journal));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Journal::destroy($id);
        Echo "Journal destroyed, you naughty boy :D";

    }



}
