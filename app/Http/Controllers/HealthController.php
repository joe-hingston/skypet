<?php

namespace App\Http\Controllers;

use App\Authorizable;
use App\StatusFunctions;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    use Authorizable;

    public function index()
    {



        $statusfunctions = new StatusFunctions();
        $this->horizonstatus = $statusfunctions->runCommand('ps auxw |grep \'artisan horizon$\' | grep -v grep | grep -v emacs');
        $this->elasticstatus = $statusfunctions->runCommand('service elasticsearch status | grep -qi "active" &&echo \'true\'');
        $this->redisstatus = $statusfunctions->runRedisCheck('redis-cli ping');
        $this->outputtotal = DB::table('outputs')->count();
        $this->journaltotal = DB::table('journals')->count();
        $this->database = DB::connection()->getDatabaseName();


        return view('healths.index', [
            'horizon'=> $this->horizonstatus,
            'elasticsearch'=> $this->elasticstatus,
            'redis'=> $this->redisstatus,
            'output'=> $this->outputtotal,
            'journal'=> $this->journaltotal,
            'database'=> $this->database,
        ]);
    }
}
