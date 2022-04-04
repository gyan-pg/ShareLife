<?php

namespace App\Http\Controllers;

use App\Mail\resetPassword as MailResetPassword;
use App\ResetPassword;
use App\Rules\NoUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
  // パスワードリセット用のEメールを送信
  public function resetReception(Request $request)
  {
    $request->validate([
      'email' => ['bail', 'required', 'email', new NoUser]
    ]);

    // uuid v4の一発生成をしてくれる関数
    $token = (String)Str::uuid();

    $reset_password = new ResetPassword();
    $reset_password->where('email', $request->email)->delete();
    $reset_password->fill(['token' => $token, 'email' => $request->email])->save();

    Mail::to($request->email)->send(new MailResetPassword($token));
  }

  // パスワードリセット用のEメールからリンクを踏んできたのかチェックする
  public function checkToken(Request $request)
  {
    // Eメールに添付されているトークンの値
    $token = $request->token;
    $reset_password = new ResetPassword();
    // 同じトークンが登録されているか確認する
    $result = $reset_password->where('token', $token)->first();
    if ($result) {
      // 記録されている時間と今の時間を比較する。
      $now = new Carbon();
      $record_time = new Carbon($result->created_at);
      $diff_seconds = $now->diffInSeconds($record_time);
      // トークンの有効期限は30分とする。

      if ($diff_seconds > 60 * 30) {
        // 有効期限切れのレコードは削除
        $reset_password->where('token', $token)->delete();
        return response()->json(['result' => 'expired'], 200);
      }

      return response()->json(['result' => 'ok'], 200);

    } else {
      return response()->json(['result' => 'ng'], 200);
    }
  }

  public function passwordResetting(Request $request)
  {
    $request->validate([
      'password' => ['bail', 'required', 'string', 'min:8', 'confirmed']
    ]);

    // トークンが正しいことを確認する。
    $reset_password = new ResetPassword();
    $count = $reset_password->where('token', $request->token)->count();
    // トークンの値が正しい時
    if ($count) {
      $reset_data = $reset_password->where('token', $request->token)->first();
      $user = new User();
      // パスワードの変更
      $user->where('email', $reset_data->email)->update(['password' => Hash::make($request->password)]);
      // トークンの情報を削除
      // $reset_password->where('token', $request->token)->delete();
      return response()->json(['email' => $reset_data->email, 'password' => $request->password]);
    } else {
      return response()->json([]);
    }
  }
}
