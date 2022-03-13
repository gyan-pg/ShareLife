<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

  protected $fillable = [
    'user_id', 'team_id', 'type', 'himoku', 'comment', 'cost', 'register_date'
  ];

  public function team ()
  {
    return $this->belongsTo('App\Team');
  }
}
