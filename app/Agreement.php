<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
  protected $fillable = [
    'user_id', 'team_id', 'title', 'content'
  ];
}
