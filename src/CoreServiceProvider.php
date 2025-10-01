<?php

namespace Eauto\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Optional: register policies, morph maps, etc.
        // Gate::policy(Vehicle::class, VehiclePolicy::class);
    }
}