<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\Event' => [

            'App\Listeners\EventListener',

        ],

        'output.created' => [

            'App\Events\OutputEvent@outputCreated',

        ],

        'output.updated' => [

            'App\Events\OutputEvent@outputUpdated',

        ],

        'output.deleted' => [

            'App\Events\OutputEvent@outputDeleted',

        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
