<?php

namespace Tests\Browser\Pages\Category;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class CategoryShow extends Page
{

    protected $categoryId;


    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/categories/'.$this->categoryId;
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

    public function assertSeeCategory(Browser $browser, $category)
    {
        $browser->assertSee($category->name)
        ->assertSee($category->icon)
        ;
    }
}
