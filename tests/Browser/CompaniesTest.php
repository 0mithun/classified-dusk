<?php

namespace Tests\Browser;

use App\Category;
use App\City;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Company\CompanyCreate;
use Tests\Browser\Pages\Company\CompanyIndex;
use Tests\DuskTestCase;

class CompaniesTest extends DuskTestCase
{
    use WithFaker;
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
    public function test_it_assert_that_user_can_add_a_new_company()
    {
        City::factory(10)->create();
        Category::factory(10)->create();

        $fakeCompanyData = $this->getFakeCompanyData();

        $this->browse(function (Browser $browser) use($fakeCompanyData) {
            $browser->loginAs('admin@admin.com')
                ->visit('/admin/companies')
                ->clickLink('Add Company')
                ->on(new CompanyCreate)
                ->fillFormDataAndSubmit($fakeCompanyData)
                ->on(new CompanyIndex)
                ->assertCompanyDetails($fakeCompanyData)
            ;
        });
    }


    protected function getFakeCompanyData()
    {
        return [
            'name'      =>  $this->faker->company,
            'address'      =>  $this->faker->streetAddress,
            'description'      =>  $this->faker->text(),
            'image'         =>  $this->faker->image(storage_path('app/public/images'), 200, 150, 'cats', false),
        ];
    }
}
