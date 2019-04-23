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


    public function __construct()

    {

    }


    public function journalCreated(Journal $journal)
    {

        Log::info("------------------------- Journal Created with ID: ".$journal->id ."------------------------- ");

    }


    public function journalUpdated(Journal $journal)
    {

        Log::info("Journal updated with ID: ".$journal->id);

    }


    public function journalDeleted(Journal $journal)
    {

        Log::info("Journal deleted with ID: ".$journal->id);

    }
}
