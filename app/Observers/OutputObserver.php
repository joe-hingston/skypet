<?php

namespace App\Observers;

use App\Jobs\ProcessAbstract;
use App\Output;
use Illuminate\Support\Facades\Log;

class OutputObserver
{
    /**
     * Handle the output "created" event.
     *
     * @param  \App\Output  $output
     * @return void
     */
    public function created(Output $output)
    {
        Log::alert('Output created : ' . $output->id);
    }

    /**
     * Handle the output "updated" event.
     *
     * @param  \App\Output  $output
     * @return void
     */
    public function updated(Output $output)
    {
        //
    }

    /**
     * Handle the output "deleted" event.
     *
     * @param  \App\Output  $output
     * @return void
     */
    public function deleted(Output $output)
    {
        //
    }

    /**
     * Handle the output "restored" event.
     *
     * @param  \App\Output  $output
     * @return void
     */
    public function restored(Output $output)
    {
        //
    }

    /**
     * Handle the output "force deleted" event.
     *
     * @param  \App\Output  $output
     * @return void
     */
    public function forceDeleted(Output $output)
    {
        //
    }
}
