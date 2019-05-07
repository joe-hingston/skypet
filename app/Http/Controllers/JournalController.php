<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessJournal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
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

    public $doiArray;



    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {

        //TODO Check that the incoming request is in ISSN format and is valid crossref before passing onto the Process Journal Queue

        ProcessJournal::dispatch($request->issn)->onConnection('redis')->onQueue('journals');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
        //
    }



}
