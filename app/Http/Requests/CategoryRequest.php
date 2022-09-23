<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'category_name'=> 'required|min:3|max:255|string',
            'parent_id' => 'sometimes|nullable|numeric'
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => 'Prašome įvesti kategorijos pavadinimą.',
            'category_name.min' => 'Kategorijos pavadinimas turi būti bent 3 simbolių ilgio.',
            'category_name.max' => 'Kategorijos pavadinimas negali būti ilgesnis nei 255 simbolių.',
            'category_name.string' => 'Kategorijos pavadinimas turi būti tekstinis.',
            'parent_id.numeric' => 'Neteisingas kategorijos pavadinimo ID.'
        ];
    }
}
