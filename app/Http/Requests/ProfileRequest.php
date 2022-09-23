<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'phone' => ['nullable', 'regex:/(86|\+3706)\d{3}\d{4}/', 'min:9','max:12'],
            'description' => 'max:255',
            'birth_date' => 'date|before:today',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'phone.numeric' => 'Telefono numeris turi būti skaičius.',
            'phone.regex' => 'Telefono numeris turi būti lietuviško formato +3706 arba 86 bei tinkamo ilgio',
            'phone.min' => 'Telefono numeris turi būti nuo 9 iki 12 simbolių.',
            'phone.max' => 'Telefono numeris turi būti nuo 9 iki 12 simbolių.',
            'description.max' => 'Aprašymas negali būti ilgesnis nei 255 simbolių.',
            'birth_date.date' => 'Neteisingai įvesta gimimo data.',
            'birth_date.before' => 'Gimimo data negali ateityje.',
            'image.mimes' => 'Netinkamas paveikslėlio formatas.',
            'image.max' => 'Paveikslėlio dydis negali viršyti 2048 baitų.',
        ];
    }
}
