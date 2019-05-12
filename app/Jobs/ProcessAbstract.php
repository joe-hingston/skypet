<?php

namespace App\Jobs;

use App\AbstractFetcher;
use App\Output;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessAbstract implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $output;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Output $output)
    {
        $this->onQueue('default_long');
        $this->onConnection('redis');
        $this->output = $output;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Redis::funnel('key')->limit(1)->then(function () {
            // Job logic...
            $abstract = new AbstractFetcher($this->output);
            $abstract->fetch();
        }, function () {
            // Could not obtain lock...
            return $this->release(10);
        });
    }


}
