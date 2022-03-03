<?php

namespace Tests\Browser\Pages\Category;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class CategoryEdit extends Page
{

    protected $categoryId;


    public function __construct($categoryId) {
        $this->categoryId = $categoryId;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return "/admin/categories/{$this->categoryId}/edit";
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


    public function pressCategoryEditSave(Browser $browser)
    {
        $browser->click('@categoryEditSave');
    }

    public function fillCategoryForm(Browser $browser, $category)
    {
        $browser->type('name', $category->name)
        ->type('icon', $category->icon)
        ;
    }
}
