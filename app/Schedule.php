<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
  protected $fillable = [
    'title', 'detail', 'start', 'end', 'team_id', 'user_id', 'color'
  ];

  protected $visible = [
    'id', 'title', 'detail', 'start', 'end', 'color'
  ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
