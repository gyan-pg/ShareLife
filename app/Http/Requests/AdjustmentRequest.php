<?php

namespace App\Http\Requests;

use App\Rules\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class AdjustmentRequest extends FormRequest
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
          'cost' => 'required|numeric|max:999999',
          'comment' => 'max:400',
          'himoku' => 'required',
          'user' => new TeamMember
        ];
    }
}
