<?php

namespace Modules\DiscountMode\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\DiscountMode\Events\DiscountEvent;
use Modules\DiscountMode\Listeners\DiscountModelUpdate;

// use Illuminate\Support\ServiceProvider;

class DiscountModeEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        DiscountEvent::class => [
            DiscountModelUpdate::class,
        ],
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
