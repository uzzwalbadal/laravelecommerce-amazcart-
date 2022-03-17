<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\MultiVendor\Events\SellerCarrierCreateEvent;
use Modules\MultiVendor\Events\SellerPickupLocationCreated;
use Modules\MultiVendor\Events\SellerShippingConfigEvent;
use Modules\MultiVendor\Events\SellerShippingRateEvent;
use Modules\MultiVendor\Listeners\SellerCarrierCreateListener;
use Modules\MultiVendor\Listeners\SellerPickupLocationCreatedListener;
use Modules\MultiVendor\Listeners\SellerShippingConfigListener;
use Modules\MultiVendor\Listeners\SellerShippingRateListener;


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
        SellerPickupLocationCreated::class => [
            SellerPickupLocationCreatedListener::class,
        ],
        SellerCarrierCreateEvent::class => [
            SellerCarrierCreateListener::class,
        ],
        SellerShippingConfigEvent::class => [
            SellerShippingConfigListener::class,
        ],

        SellerShippingRateEvent::class => [
            SellerShippingRateListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
