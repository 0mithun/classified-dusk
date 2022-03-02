<?php

namespace Tests\Browser;

use App\City;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CityTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }


    /** @test */
    public function it_asserts_that_user_can_read_all_cities()
    {
        $user = User::factory()->admin_user()->create();
        $cities = City::factory(10)->create();

        $this->browse(function(Browser $browser) use($user, $cities){
            $browser->loginAs($user)
                ->visit('/admin/cities')
                ->assertSee($cities->random()->name)
                ->logout()
                ;
        });
    }


    /** @test */
    public function it_asserts_that_user_can_read_single_cities()
    {
        $user = User::factory()->admin_user()->create();
        $city = City::factory()->create();

        $this->browse(function(Browser $browser) use($user, $city){
            $browser->loginAs($user)
                ->visit('/admin/cities/'.$city->id)
                ->assertSee($city->name)
                ->assertSee($city->id)
                ->logout()
                ;
        });
    }
    /** @test */
    public function it_asserts_that_user_can_add_new_city()
    {
        $user = User::factory()->admin_user()->create();

        $this->browse(function(Browser $browser) use($user){
            $city = City::factory()->make();
            $browser->loginAs($user)
                ->visit('/admin/cities/create')
                ->assertSee('Create City')
                ->type('name', $city->name)
                ->press('Save')
                ->assertPathIs('/admin/cities')
                ->assertSee($city->name)
                ->logout()
                ;

            $this->assertDatabaseHas('cities', ['name'=>$city->name]);
        });
    }


    /** @test */
    public function it_asserts_that_user_can_edit_a_city()
    {
        $user = User::factory()->admin_user()->create();

        $this->browse(function(Browser $browser) use($user){
            $city = City::factory()->create();
            $new_city = City::factory()->make();
            $browser->loginAs($user)
                ->visit("/admin/cities/{$city->id}/edit")
                ->assertSee('Edit City')
                ->type('name', $new_city->name)
                ->press('Save')
                ->assertPathIs('/admin/cities')
                ->assertSee($new_city->name)
                ->assertDontSee($city->name)
                ->logout()
                ;

            $this->assertDatabaseHas('cities', ['name'=>$new_city->name]);
            $this->assertDatabaseMissing('cities', ['name'=>$city->name]);
            $this->assertCount(1, City::all());
        });
    }




    /** @test */
    public function it_asserts_that_user_can_delete_a_city()
    {
        $user = User::factory()->admin_user()->create();

        $this->browse(function(Browser $browser) use($user){
            $city = City::factory()->create();
            $browser->loginAs($user)
                ->visit("/admin/cities")
                ->click('#DataTables_Table_0 > tbody > tr > td:nth-child(4) > form > input.btn.btn-xs.btn-danger')
                ->assertDialogOpened('Are you sure?')
                ->acceptDialog()
                ->assertPathIs('/admin/cities')
                ->assertDontSee($city->name)
                ->logout()
                ;

            // $this->assertDatabaseMissing('cities', ['name'=>$city->name]);
            // $this->assertCount(0, City::all());
            $this->assertSoftDeleted('cities', ['id'=>$city->id]);
        });
    }




}
