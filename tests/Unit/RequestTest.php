<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class RequestTest extends TestCase
{

    public function test_that_form_submit_request_authorize_method_returns_true()
    {
        $request = new \App\Http\Requests\FormSubmitRequest();
        $this->assertTrue($request->authorize());
    }

    public function test_that_valid_form_submit_returns_successful_response()
    {
        $data = [
            'email'         => 'raiyan24r@gmail.com',
            'symbol'        => 'AAME',
            'from'          => '2022-08-09',
            'to'            => '2022-09-04',
            'companyName'   => 'Atlantic American Corporation',
            'sendEmail'     => 'true',
        ];

        $this->formSubmit($data)->assertStatus(200);
    }

    public function test_that_form_submit_with_invalid_symbol_returns_error_response()
    {

        $data = [
            'symbol'        => '123wdd243',
            'email'         => 'raiyan24r@gmail.com',
            'from'          => '2022-08-09',
            'to'            => '2022-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }

    public function test_that_form_submit_without_symbol_returns_error_response()
    {

        $data = [
            'email'         => 'raiyan24r@gmail.com',
            'from'          => '2022-08-09',
            'to'            => '2022-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }


    public function test_that_form_submit_without_email_returns_error_response()
    {

        $data = [
            'symbol'        => 'AAME',
            'from'          => '2022-08-09',
            'to'            => '2022-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }

    public function test_that_form_submit_with_invalid_email_returns_error_response()
    {

        $data = [
            'email'         => 'raiyan24rgmail.com',
            'symbol'        => 'AAME',
            'from'          => '2022-08-09',
            'to'            => '2022-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }

    public function test_that_form_submit_without_start_date_or_end_date_returns_error_response()
    {
        $data = [
            'email'         => 'raiyan24rgmail.com',
            'symbol'        => 'AAME',
            'to'            => '2022-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);

        $data = [
            'email'         => 'raiyan24rgmail.com',
            'symbol'        => 'AAME',
            'from'          => '2022-08-09',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }

    public function test_that_form_submit_with_start_date_greater_than_today_returns_error_response()
    {

        $data = [
            'email'         => 'raiyan24rgmail.com',
            'symbol'        => 'AAME',
            'from'          => '2023-09-09',
            'to'            => '2022-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }

    public function test_that_form_submit_with_start_date_less_than_end_date_returns_error_response()
    {

        $data = [
            'email'         => 'raiyan24rgmail.com',
            'symbol'        => 'AAME',
            'from'          => '2022-09-09',
            'to'            => '2021-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }

    public function test_that_form_submit_with_end_date_greater_than_today_returns_error_response()
    {

        $data = [
            'email'         => 'raiyan24rgmail.com',
            'symbol'        => 'AAME',
            'from'          => '2022-09-09',
            'to'            => '2023-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }

    public function test_that_form_submit_with_end_date_greater_than_start_date_returns_error_response()
    {

        $data = [
            'email'         => 'raiyan24rgmail.com',
            'symbol'        => 'AAME',
            'from'          => '2022-09-09',
            'to'            => '2023-09-04',
            'companyName'   => 'Atlantic American Corporation',
        ];

        $this->formSubmit($data)->assertStatus(422);
    }


    public function formSubmit($data)
    {
        $response = $this->postJson('/api/symbol-data', $data);
        return $response;
    }
}
