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




}
