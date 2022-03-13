<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
  protected $fillable = [
    'user1_id', 'user2_id', 'status'
  ];

  protected $visible = [
    'id', 'status', 'user1_id', 'user2_id'
  ];

  public function payments()
  {
    return $this->hasMany('App\Payment');
  }
}
