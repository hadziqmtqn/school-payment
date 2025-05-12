<?php

namespace App\Providers;

use App\Http\ViewComposers\AppViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        View::composer('*', AppViewComposer::class);
    }
}
