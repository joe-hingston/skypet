<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessJournal;
use App\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Journals extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Journal::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            ProcessJournal::dispatch($request->issn)->onConnection('redis')->onQueue('journals');
            return response($request)
                ->header('issn has been added', $request->issn);
        }


        //ensure user is authorised to do this action
        //add to the database
        //start the job to add journal

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
    public function destroyByISSN(Request $request)
    {
        Log::debug($request->issn);
//        Journal::where('issn', $request->issn)->firstorFail();
    }
}
