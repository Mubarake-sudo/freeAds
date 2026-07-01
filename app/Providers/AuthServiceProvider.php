<?php

namespace App\Providers;

use App\Models\Ad;
use App\Policies\AdPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Ad::class => AdPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
