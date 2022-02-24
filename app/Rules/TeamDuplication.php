<?php

namespace App\Rules;

use App\Team;
use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class TeamDuplication implements Rule
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
      $team = new Team();
      $partner_id = $user->select('id')->where('email', $value)->first();
      $count = $team->where('user1_id', $partner_id->id)->orWhere('user2_id', $partner_id->id)->count();
      Log::debug($partner_id);
      Log::debug($count);

      if ($count) {
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
        return trans('validation.TeamDuplication');
    }
}
