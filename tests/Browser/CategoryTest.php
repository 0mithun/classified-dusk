<?php

namespace Tests\Browser;

use App\Category;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Category\CategoryEdit;
use Tests\Browser\Pages\Category\CategoryIndex;
use Tests\Browser\Pages\Category\CategoryShow;
use Tests\Browser\Pages\Category\CreateCategory;

class CategoryTest extends DuskTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_it_assert_that_user_can_read_all_categories()
    {
        $user = User::factory()->admin_user()->create();
        $categories = Category::factory(10)->create();

        $this->browse(function (Browser $browser) use($user, $categories) {
            $browser->loginAs($user)
                ->visit(new CategoryIndex)
                ->assertSeeCategories($categories)

            ;
        });
    }

    /** @test */
    public function test_assert_user_can_view_single_category()
    {
        $user = User::factory()->admin_user()->create();
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use($user, $category) {
            $browser->loginAs($user)
                ->visit(new CategoryIndex)
                ->pressViewButton($category)
                ->on(new CategoryShow($category->id))
                ->assertSeeCategory($category)
                ->logout()

            ;
        });
    }

    /** @test */
    public function test_assert_user_can_create_new_category()
    {
        $user = User::factory()->admin_user()->create();
        $category = Category::factory()->make();

        $this->browse(function (Browser $browser) use($user, $category) {
            $browser->loginAs($user)
                ->visit(new CategoryIndex)
                ->pressCreateButton()
                ->on(new CreateCategory)
                ->fillCategoryForm($category)
                // ->pause(5000)
                ->pressCategoryCreateSave()
                ->on(new CategoryIndex)
                ->assertSeeCategory($category)
                // ->pause(5000)
                ->logout()

            ;
        });
    }

    /** @test */
    public function test_assert_user_can_edit_a_category()
    {
        $user = User::factory()->admin_user()->create();
        $categories = Category::factory(10)->create();
        $category = $categories->random();

        $this->browse(function (Browser $browser) use($user, $category) {
            $updateCategory = Category::factory()->make();
            $browser->loginAs($user)
                ->visit(new CategoryIndex)
                ->pressEditbutton($category)
                ->on(new CategoryEdit($category->id))
                ->fillCategoryForm($updateCategory)
                // ->pause(5000)
                ->pressCategoryEditSave()
                ->on(new CategoryIndex)
                ->assertSeeCategory($updateCategory)
                ->assertDontSee($category->name)
                ->assertDontSee($category->icon)
                // ->pause(5000)
                ->logout()

            ;
        });
    }

    /** @test */
    public function test_assert_user_can_delete_a_category()
    {
        $user = User::factory()->admin_user()->create();
        $categories = Category::factory(10)->create();
        $category = $categories->random();

        $this->browse(function (Browser $browser) use($user, $category) {
            $browser->loginAs($user)
                ->visit(new CategoryIndex)
                ->pressDeletebutton($category)
                ->on(new CategoryIndex)
                ->assertDontSee($category->name)
                ->assertDontSee($category->icon)
                // ->pause(5000)
                ->logout()

            ;
        });

        $this->assertSoftDeleted('categories', ['id'=>$category->id]);
    }

    /** @test */
    public function test_assert_user_can_delete_multiple_category()
    {
        $user = User::factory()->admin_user()->create();
        $categories = Category::factory(20)->create();

        $this->browse(function (Browser $browser) use($user, $categories) {
            $browser->loginAs($user)
                ->visit(new CategoryIndex)
                ->pause(2000)
                ->selectCategories($categories)
                ->pause(2000)
                ->pressDeleteSelected()
                ->on(new CategoryIndex)
                ->pause(2000)
                ->assertDontSeeCategories($categories)
                ->pause(2000)
                ->logout()

            ;
        });

    }




}
