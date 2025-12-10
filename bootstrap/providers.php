<?php

$providers = [
    App\Providers\AppServiceProvider::class,
];

// Don't load Telescope in testing environment
if (!app()->environment('testing')) {
    $providers[] = App\Providers\TelescopeServiceProvider::class;
}

return $providers;
