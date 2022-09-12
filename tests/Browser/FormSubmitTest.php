<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FormSubmitTest extends DuskTestCase
{


    public function test_that_table_is_rendered_after_valid_input_form_submit()
    {

        $this->browse(function ($browser) {
            $browser->visit('/')
                ->value('#email', "raiyan24r@gmail.com")
                ->value('#company-symbol', 'AAME')
                ->value('#from', '2022-08-09')
                ->value('#to', '2022-09-01')
                ->press('#submit')
                ->waitFor('.table', 10)
                ->assertSee('Price Quotes');
        });
    }
    public function test_that_error_is_displayed_after_invalid_input_form_submit()
    {

        $this->browse(function ($browser) {
            $browser->visit('/')
                ->value('#email', "raiyan24r@gmail.com")
                ->value('#company-symbol', 'AAME')
                ->value('#from', '2022-08-09')
                ->value('#to', '123') //invalid date
                ->press('#submit')
                ->waitForTextIn('#form-errors', 'Something went wrong! Please try again', 10)
                ->assertSee('Something went wrong! Please try again');
        });
    }
}
