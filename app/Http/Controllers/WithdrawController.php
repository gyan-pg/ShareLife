<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WithdrawController extends Controller
{
  public function withdraw(Request $request)
  {
    $user_id = Auth::id();
    // 送られてきたユーザーデータが自分のものか確認する。
    if ($request->id !== $user_id) {
      return response()->json(['error' => '不正な値です。'], 422);
    }

    $user = new User();

    // デフォルトのno image画像以外の画像パスが入っていた場合
    if (Auth::user()->image !== '/storage/images/noimage.png') {
      // ProfileEditControllerのdelete_img関数を使用する。
      $profEdit = app()->make('App\Http\Controllers\ProfileEditController');
      $profEdit->delete_image(Auth::user()->image);
    }

    $user->where('id', Auth::id())->delete();


    Auth::logout();
  }
}
