<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Http\Requests\ScheduleRequest;
use App\Schedule;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
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
  }

  // スケジュールの削除
  public function delete($id)
  {
    // スケジュールのidのバリデーション
    // 空ではないか
    if($id === ""){
      return response()->json(['error'=>'エラーが発生しました。'],422);
    }

    // 数字であることを確認
    $result = preg_match('/^[0-9]+$/',$id);
    // エラー判定
    if(!$result){
      return response()->json(['error'=>'エラーが発生しました。'],422);
    }

    // 自分の予定であることを確認
    $schedule = new Schedule();
    $created_user = $schedule->find($id)->user;
    if ($created_user->id !== Auth::id()) {
      return response()->json(['error'=>'エラーが発生しました。'],422);
    }
    // バリデーションOK
    $schedule->where('id', $id)->delete();
  }

  //スケジュールの更新
  public function changeSchedule(ScheduleRequest $request)
  {
    $schedule = new Schedule();
    $schedule->where('id', $request->id)->update(['title' => $request->title,
    'detail' => $request->detail, 'start' => $request->start, 'end' => $request->end, 'color' => $request->color]);
  }

  public function getHoliday($year)
  {
    $url = "https://holidays-jp.github.io/api/v1/".$year."/date.json";
    $method = "GET";

    $client = new Client();

    $response = $client->request($method, $url);
    
    return $response;
  }
}
