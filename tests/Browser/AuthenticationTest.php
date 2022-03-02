<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthenticationTest extends DuskTestCase
{
    // use DatabaseMigrations;

    protected $user;
    protected $amdinUser;
    protected $nonAdminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');

        $this->user = User::factory()->create();
        $this->amdinUser = User::factory()->admin_user()->create();
        $this->nonAdminUser = User::factory()->non_admin_user()->create();
    }



    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_asserts_that_user_can_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Classifieds')
                    ->type('email', 'admin@admin.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/companies')
                    ->assertSee('Company List')
                    ->assertAuthenticated()
                    ->logout()
                    ;
        });
    }


    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_asserts_that_user_can_logged_out()
    {
        $user = $this->user;
        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                    ->visit('/admin')
                    ->clickLink('Logout')
                    ->assertGuest()
                    ->logout()
                    ;
        });
    }


    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_asserts_that_incorrect_login_fails()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'admin@admin.com')
                    ->type('password', 'invalid')
                    ->press('Login')
                    ->assertPathIs('/login')
                    ->assertSee('These credentials do not match our records.')
                    ->assertElementHasClass('input[name="email"]', 'is-invalid')
                    ->logout()
                    ;
            // $classLists = $browser->attribute('input[name="email"]', 'class');
            // $this->assertTrue(in_array('is-invalid', explode(' ', $classLists)));
        });
    }


    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_admin_users_can_manage_users_manage()
    {
        $user = User::factory()->admin_user()->create();
        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                    ->visit('/admin')
                    ->assertSee('User management')
                    ->visit('/admin/users')
                    ->assertSee('User List')
                    ->logout()
                    ;
        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_admin_users_can_not_manage_users_manage()
    {
        $user = User::factory()->non_admin_user()->create();
        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                    ->visit('/admin')
                    ->assertDontSee('User management')
                    ->visit('/admin/users')
                    // ->pause(5000)
                    ->assertSee('FORBIDDEN')
                    ->logout()
                    ;
        });
    }
}
