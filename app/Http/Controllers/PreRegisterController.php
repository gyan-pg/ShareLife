<?php

namespace App\Http\Controllers;

use App\Mail\verifyEmail;
use App\Rules\EmailDuplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PreRegisterController extends Controller
{ 
  // トークン作成用の関数
  function makeToken($length = 8){
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPZRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0;$i < $length; ++$i){
        $str .= $chars[mt_rand(0,61)];
    }
    return $str;
  }

  // 仮会員登録用のEメールを送信する
  public function sendEmail(Request $request) {

    $request->validate([
      'email' => new EmailDuplication
    ]);

    $token = $this->makeToken();

    // tokenはEメールで送信する。
    Mail::to($request->email)->send(new verifyEmail($token));

    return response()->json(['register_token' => $token], 200);
  }
}
