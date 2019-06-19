<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WordUpdateRequest extends FormRequest
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
        'word' => 'required',
//        'longdate' => 'required|unique:words',
      ];
    }

    public function messages()
    {
      return [
        'word.required' => 'Word has already been taken',
        'longdate.required'  => 'Date has already been taken',
      ];
    }
}
