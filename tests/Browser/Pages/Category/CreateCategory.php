<?php

namespace Tests\Browser\Pages\Category;

use App\Category;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class CreateCategory extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/categories/create';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->assertSee('Create Category');
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

    public function pressCategoryCreateSave(Browser $browser)
    {
        $browser->click('@categoryCreateSave');
    }

    public function fillCategoryForm(Browser $browser, $category)
    {
        $browser->type('name', $category->name)
        ->type('icon', $category->icon)
        ;
    }
}
