<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
  public function passwordChange (Request $request) 
  {
    $request->validate([
      'password' => ['bail', 'required', 'string', 'min:8', 'confirmed']
    ]);
    
    $user = new User();
    $user->where('id', Auth::id())->update(['password' => Hash::make($request->password)]);
  }
}
