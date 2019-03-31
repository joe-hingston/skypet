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

        Log::info("Item Created Event Fire: " . $output);

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function outputUpdated(Output $output)

    {

        Log::info("Item Updated Event Fire: " . $output);

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function outputDeleted(Output $output)

    {

        Log::info("Item Deleted Event Fire: " . $output);

    }
}
