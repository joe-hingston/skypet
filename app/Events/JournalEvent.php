<?php

namespace App\Events;

use App\Journal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class JournalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()

    {

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function journalCreated(Journal $journal)
    {

        Log::info("Journal Created with ID: ".$journal->id);

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function journalUpdated(Journal $journal)
    {

        Log::info("Journal updated with ID: ".$journal->id);

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function journalDeleted(Journal $journal)
    {

        Log::info("Journal deleted with ID: ".$journal->id);

    }
}
