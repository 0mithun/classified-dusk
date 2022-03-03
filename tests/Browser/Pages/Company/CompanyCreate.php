<?php

namespace Tests\Browser\Pages\Company;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class CompanyCreate extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/companies/create';
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


    public function fillFormDataAndSubmit(Browser $browser, $data)
    {
        $browser->type('name', $data['name'])
                ->type('address', $data['address'])
                ->type('description', $data['description'])
                ->select('city_id')
                ->select('categories[]')
                ->click('#logo-dropzone')
                // ->attach('logo', 'storage/app/public/images/'.$data['image'])
                ->attach('input.dz-hidden-input', storage_path('app/public/images/').$data['image'])
                ->press('Save')
        ;
    }



}
