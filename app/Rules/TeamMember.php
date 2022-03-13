<?php

namespace App\Rules;

use App\Team;
use Illuminate\Contracts\Validation\Rule;

class TeamMember implements Rule
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
      $team = new Team();
      $count = $team->where('user1_id', $value)->orWhere('user2_id', $value)->count();
      if ($count) {
        return true;
      }
      return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.TeamMember');
    }
}
