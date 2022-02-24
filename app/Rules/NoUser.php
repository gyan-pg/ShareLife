<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class NoUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      $user = new User();
      $count = $user->where('email', $value)->count();
      if (!$count) {
        return false;
      }
      return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.NoUser');
    }
}
