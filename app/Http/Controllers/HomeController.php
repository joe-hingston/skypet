<?php

namespace App\Http\Controllers;

use App\StatusFunctions;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function status()
    {

        $statusfunctions = new StatusFunctions();
        $this->horizonstatus = $statusfunctions->runCommand('ps auxw |grep \'artisan horizon$\' | grep -v grep | grep -v emacs');
        $this->elasticstatus = $statusfunctions->runCommand('service elasticsearch status | grep -qi "active" &&echo \'true\'');
        $this->redisstatus = $statusfunctions->runRedisCheck('redis-cli ping');
        $this->outputtotal = DB::table('outputs')->count();
        $this->journaltotal = DB::table('journals')->count();
        $this->database = DB::connection()->getDatabaseName();


        return view('layouts.status.index', [
            'horizon'=> $this->horizonstatus,
            'elasticsearch'=> $this->elasticstatus,
            'redis'=> $this->redisstatus,
            'output'=> $this->outputtotal,
            'journal'=> $this->journaltotal,
            'database'=> $this->database,
        ]);
    }


}



