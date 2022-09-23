<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingRequest extends FormRequest
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
            'comment' => 'required|max:70',
            'author' => 'required|max:40',
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'Prašome įvesti komentarą.',
            'comment.max' => 'Komentarą gali sudaryti iki 70 simbolių.',
            'author.required' => 'Prašome įvesti komentaro autoriaus vardą.',
            'author.max' => 'Komentaro autoriaus varas negali būti ilgesnis nei 40 simbolių.',
        ];
    }
}
