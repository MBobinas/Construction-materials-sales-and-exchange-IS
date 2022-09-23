<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'subject'=>'required',
            'message' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Prašome įvesti el. pašto adresą.',
            'email.email' => 'El. pašto adresas neteisingas.',
            'subject.required' => 'Prašome įvesti temą.',
            'message.required' => 'Prašome įvesti žinutę.'
        ];
    }
}
