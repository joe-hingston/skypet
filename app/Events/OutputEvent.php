<?php

namespace App\Events;

use App\Output;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OutputEvent
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

    public function outputCreated(Output $output)

    {

        Log::info("Output Created with ID: ".$output->doi);

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function outputUpdated(Output $output)

    {

        Log::info("Output Updated with DOI: ".$output->doi);

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function outputDeleted(Output $output)

    {

        Log::info("Output Deletec with DOI: ".$output->doi);

    }
}
