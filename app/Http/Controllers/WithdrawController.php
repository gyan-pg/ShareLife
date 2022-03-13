<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
  public function withdraw(Request $request)
  {
    
    Auth::logout();
  }
}
