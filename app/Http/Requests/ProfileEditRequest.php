<?php

namespace App\Http\Requests;

use App\Rules\EmailDuplication;
use Illuminate\Foundation\Http\FormRequest;

class ProfileEditRequest extends FormRequest
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
          'email' => [
            'required',
            'email',
            new EmailDuplication,
          ],
          'name' => [
            'required',
            'max:20'
          ]
        ];
    }
}
