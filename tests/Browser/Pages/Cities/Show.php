<?php

namespace Tests\Browser\Pages\Cities;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class Show extends Page
{

    protected $cityId;

    public function __construct($cityId) {
        $this->cityId = $cityId;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/cities/'.$this->cityId;
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }


    public function assertSeeCityDetails(Browser $browser, $city)
    {
        $browser
            ->assertSee($city->name)
            ->assertSee($city->id)
            ;
    }
}
