<?php

namespace App\Providers;

use Laravel\Dusk\Browser;
use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\Assert;

class DuskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Browser::macro('assertElementHasClass', function($element, $className){
            Assert::assertTrue(
                in_array($className, explode(' ', $this->attribute($element, 'class'))),
                "Dit not see expected value [$className] within element [$element]"
            );

            return $this;
        });
    }
}
