<?php

namespace App\Http\Requests;

use App\Rules\MyEmail;
use App\Rules\NoUser;
use App\Rules\TeamDuplication;
use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
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
            new MyEmail,
            new TeamDuplication,
            new NoUser]
        ];
    }
}
