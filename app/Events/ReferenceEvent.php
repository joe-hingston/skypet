<?php

namespace App\Events;

use App\Journal;
use App\Output;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReferenceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function referenceStarted(Output $output)
    {

        Log::info("References scanned with DOI: ".$output->doi);

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function referenceEnded(Output $output)
    {

        Log::info("References scan ended with DOI: ".$output->doi);

    }

    public function referenceNullJournal(Journal $journal)
    {

        Log::info("References scan didn't locate a new journal for : ". $journal->issn);

    }

    public function referenceNotNullJournal(Journal $journal)
    {

        Log::info("References scan located a new journal with ISSN, added to database : ".$journal->issn);

    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
