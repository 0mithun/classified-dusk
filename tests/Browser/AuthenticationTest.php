<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthenticationTest extends DuskTestCase
{
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
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'admin@admin.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->clickLink('Logout')
                    ->assertGuest()
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
                    ;
            // $classLists = $browser->attribute('input[name="email"]', 'class');
            // $this->assertTrue(in_array('is-invalid', explode(' ', $classLists)));
        });
    }
}
