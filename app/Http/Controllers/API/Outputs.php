<?php

namespace App\Http\Controllers\API;

use App\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class Outputs extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Outputs\OutputsRepository $repository, Request $request)
    {


        //todo Ensure that sets the pagesize etc from url parameter, also check loadtimes

        $size = isset($resquest->size) ? Input::get('size') : 100;
        $start = isset($resquest->start) ? Input::get('start') : 0;
        $articles['outputs'] = $repository->all($size, $start);
        $articles['pagesize'] = count($articles['outputs']);
        $articles['total-count'] = Output::count();
        $articles['pages'] = Output::count()/$articles['pagesize'];
        return response()->json($articles);

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
}
