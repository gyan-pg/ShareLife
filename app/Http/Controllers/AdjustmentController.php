<?php

namespace App\Http\Controllers;

use App\Himoku;
use App\Http\Requests\AdjustmentRequest;
use App\Payment;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdjustmentController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  // 費目リスト取得
  public function getHimoku() {
    return Himoku::all();
  }

  // ログインユーザーのチームIDを取得
  public function getTeamId() {
    $team = new Team();
    $team_info = $team->where('user1_id', Auth::id())->orWhere('user2_id', Auth::id())->first();
    return $team_info->id;
  }

  // 支払い登録
  public function register(AdjustmentRequest $request)
  {
    Log::debug($request);
    $payment = new Payment();
    $team_id = $this->getTeamId();
    $payment->fill(['user_id' => $request->user, 'team_id' => $team_id, 'type' => $request->type,
    'himoku' => $request->himoku, 'comment' => $request->comment, 'cost' => $request->cost, 'register_date' => $request->date])->save();
  }
  // 支払いデータの削除
  public function delete($id)
  {
    Log::debug($id);
    $payment = new Payment();

    // 自分のチームの支払いかを確認する。
    // 送られてきたidの支払いのチームを取得
    $result = $payment->where('id', $id)->first()->team->id;
    // 自分のチームを取得
    $team_id = $this->getTeamId();
    if ($result === $team_id) {
      $payment->where('id', $id)->delete();
    } else {
      return response()->json(['error' => '不正な値が流入しました。'], 422);
    }
  }

  // 一覧の取得
  public function show($date)
  {
    // 日付YYYY-MM形式なので、年と月を切り出す
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 2);

    $payment = new Payment();
    $team_id = $this->getTeamId();
    $result = $payment->where('team_id', $team_id)->whereYear('register_date', $year)->whereMonth('register_date', $month)->get();

    return $result;
  }

}
