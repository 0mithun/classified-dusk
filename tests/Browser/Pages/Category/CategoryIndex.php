<?php

namespace Tests\Browser\Pages\Category;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class CategoryIndex extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/categories';
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

    public function assertDontSeeCategory(Browser $browser, $category)
    {
        $browser->assertDontSee($category->name)
                ->assertDontSee($category->icon)
            ;
    }

    public function assertSeeCategories(Browser $browser, $categories)
    {
        foreach($categories as $category){
            $browser->assertSee($category->name);
        }
    }


    public function pressCreateButton(Browser $browser)
    {
        $browser->click('@createCategory');
    }

    public function pressViewbutton(Browser $browser, $category)
    {
        $browser->click('@viewButton'.$category->id);
    }

    public function pressEditbutton(Browser $browser, $category)
    {
        $browser->click('@editButton'.$category->id);
    }


    public function pressDeletebutton(Browser $browser, $category)
    {
        $browser->click('@deleteButton'.$category->id)
            ->assertDialogOpened('Are you sure?')
            ->acceptDialog()
            ->assertPathIs('/admin/categories')
        ;
    }
    public function pressDeleteSelected(Browser $browser)
    {
        $browser->clickLink('Delete selected')
                ->assertDialogOpened('Are you sure?')
                ->acceptDialog()
                ->assertPathIs('/admin/categories')
        ;
    }

    public function selectCategories(Browser $browser, $categories)
    {
        $browser->pause(200);
        foreach ($categories as $category) {
            $browser->press("@selectCheckbox".$category->id)
                ->pause(50);
            // $browser->click(".checkbox".$category->id);
            // $browser->click("#DataTables_Table_0 > tbody > tr > td.checkbox".$category->id.".select-checkbox");
        }
    }


    public function assertDontSeeCategories(Browser $browser, $categories)
    {
        foreach($categories as $category){
            $browser->assertDontSee($category->name);
        }
    }

}
