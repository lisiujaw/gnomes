<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GnomeEditRequets extends FormRequest
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
            'name'      => 'required|max:255',
            'age'       => 'required|integer|min:0|max:100',
            'strength'  => 'required|integer|min:0|max:100',
            'avatar'    => 'file|mimes:jpeg,png'
        ];
    }
}
