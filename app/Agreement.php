<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
  protected $fillable = [
    'user_id', 'team_id', 'title', 'content'
  ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
