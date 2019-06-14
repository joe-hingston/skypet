<?php

namespace App\Providers;


use App\Journal;
use App\Observers\JournalObserver;
use App\Observers\OutputObserver;
use App\Outputs\OutputsRepository;
use App\Outputs\EloquentOutputsRepository;
use App\Output;
use App\User;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Schema;
use Elasticsearch\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OutputsRepository::class, EloquentOutputsRepository::class);
        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Output::observe(OutputObserver::class);
        Journal::observe(JournalObserver::class);

    }
}
