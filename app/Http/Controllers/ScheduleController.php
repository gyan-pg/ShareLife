<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Schedule;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
  // スケジュールの登録
  public function register(ScheduleRequest $request)
  {
    $team = new Team();
    $schedule = new Schedule();
    $user_team = $team->select('id')->where('user1_id', Auth::id())->orWhere('user2_id', Auth::id())->first();

    $schedule->fill(['title' => $request->title, 'detail' => $request->detail, 'start' => $request->start, 
    'end' => $request->end, 'team_id' => $user_team->id, 'user_id' => Auth::id(), 'color' => $request->color])->save();
    
  }
  // スケジュールの取得
  public function show() {

    $team = new Team();
    $schedule = new Schedule();

    $user_team = $team->select('id')->where('user1_id', Auth::id())->orWhere('user2_id', Auth::id())->first();
    $result = $schedule->where('team_id', $user_team->id)->orderBy('start', 'asc')->get();
    return $result;
  }

  // スケジュールの変更(ドラッグ&ドロップで日付変更)
  public function changeDay(Request $request)
  {
    $schedule = new Schedule();
    $schedule->where('id', $request->id)->update(['start' => $request->start, 'end' => $request->end]);
    Log::debug('done');
  }

  // スケジュールの削除
  public function delete($id)
  {
    Log::debug($id);
    // スケジュールのidのバリデーション
    // 空ではないか
    if($id === ""){
      return response()->json(['error'=>'エラーが発生しました。'],422);
    }

    // 数字であることを確認
    $result = preg_match('/^[0-9]+$/',$id);
    // エラー判定
    if($result){
      return response()->json(['error'=>'エラーが発生しました。'],422);
    }
  }
}
