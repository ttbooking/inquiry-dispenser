<?php

namespace TTBooking\InquiryDispenser;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

class CollectionMacroServiceProvider extends ServiceProvider
{
    public function register()
    {
        Collection::make(glob(__DIR__.'/Macros/*.php'))
            ->map(function ($path) {
                return pathinfo($path, PATHINFO_FILENAME);
            })
            ->each(function ($class) {
                $class = __NAMESPACE__.'\\Macros\\'.$class;
                Collection::mixin($this->app->make($class));
            });
    }
}
