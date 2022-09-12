<?php

namespace App\Http\Requests;

use App\Services\CompanyService;
use Illuminate\Foundation\Http\FormRequest;

class FormSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email'  => 'email address',
            'symbol' => 'company symbol',
            'from'   => 'start date',
            'to'     => 'end date',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $validSymbols = (new CompanyService)->getSymbols()->toArray();
        return [
            'email'         => ['required_if:sendEmail,==,true', 'email'],
            'symbol'        => ['required', 'string', 'in:' . implode(',', $validSymbols)],
            'from'          => 'required|date|date_format:Y-m-d|before_or_equal:to|before_or_equal:' . now()->format('Y-m-d'),
            'to'            => 'required|date|date_format:Y-m-d|after_or_equal:from|before_or_equal:' . now()->format('Y-m-d'),
            'companyName'   => 'required_if:sendEmail,==,true|string|min:1',
            'page'          => 'sometimes|integer|min:1',
            'withChartData' => 'sometimes',
            'sendEmail'     => 'required',
        ];
    }
}
