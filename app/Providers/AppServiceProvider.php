<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register morph map untuk polymorphic relations
        Relation::enforceMorphMap([
            'sekolah' => \App\Models\Sekolah::class,
            'korwil' => \App\Models\Korwil::class,
        ]);
    }

    public function register(): void
    {
        //
    }
}
