<?php

namespace App\Http\Controllers;

use App\Agreement;
use App\Http\Requests\AgreementRequest;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgreementController extends Controller
{
  // 認証済みユーザーでないとアクセスできないよう制限
  public function __construct()
  {
    $this->middleware('auth');
  }
  
  // 決め事の登録を行う
  public function register(AgreementRequest $request)
  {
    Log::debug($request);
    $agreement = new Agreement;
    $user_id = Auth::id();
    $agreement->fill(['user_id' => $user_id, 'title' => $request->title, 'team_id' => $request->team_id, 'content' => $request->content])->save();
  }

  // 決め事の編集
  public function updateAgreement(AgreementRequest $request)
  {
    $agreement = new Agreement;
    $id = $request->id;
    
    // バリデーション
    $this->CheckId($id);

    $agreement = new Agreement();
    // 自分の決め事であることを確認
    $created_user = $agreement->find($id)->user;
    Log::debug($created_user);
    if ($created_user->id !== Auth::id()) {
      return response()->json(['error'=>'エラーが発生しました。'],422);
    }

    $agreement->where('id', $id)->update(['title' => $request->title, 'content' => $request->content, 'approval' => '0']);

  }

  // 決め事の一覧を取得
  public function showList($id)
  {
    $agreement = new Agreement;
    return $agreement->where('team_id', $id)->get();
  }

  // 決め事の削除
  public function delete($id)
  {
    $team = new Team();
    // 決め事のidのバリデーション
    // 空ではないか
    $this->CheckId($id);
    Log::debug('id');
    Log::debug($id);
    $agreement = new Agreement();
    // 自分のチームの決め事であることを確認
    $team_id = $agreement->find($id);
    Log::debug('team_id');
    Log::debug($team_id);
    $my_team = $team->where('user1_id', Auth::id())->orWhere('user2_id', Auth::id())->first();
    Log::debug('my_team');
    Log::debug($my_team->id);
    if ($team_id->team_id !== $my_team->id) {
      return response()->json(['error'=>'エラーが発生しました。'],422);
    }

    $agreement->where('id', $id)->delete();
  }

  // 決め事の承認
  public function approve(Request $request)
  {
    $team = new Team();
    $id = $request->id;
    $my_team = $team->where('user1_id', Auth::id())->orWhere('user2_id', Auth::id())->first();
    // 決め事のidのバリデーション
    $this->CheckId($id);

    // 正しい決め事かどうかを確認する。
    $agreement = new Agreement();
    $result = $agreement->where('id', $id)->first();
    // 自分の作った決め事ではないこと。まだ承認されていない決め事であること。自分のチームの決め事であること。
    $this->CheckStatus($result, $my_team->id);
    // if($result->user_id === Auth::id() || $result->approval !== '0' || $result->team_id !== $my_team->id){
    //   return response()->json(['error'=>'エラーが発生しました。'],422);
    // }

    // バリデーションOK
    $agreement->where('id', $id)->update(['approval' => 'approved']);
  }

  // 決め事の拒否
  public function denyAgreement(Request $request)
  {
    $id = $request->id;
    $team = new Team();
    // idのバリデーション
    $this->CheckId($id);

    $my_team = $team->where('user1_id', Auth::id())->orWhere('user2_id', Auth::id())->first();
    $agreement = new Agreement();
    
    $result = $agreement->where('id', $id)->first();
    // 自分の作った決め事ではないこと。まだ承認されていない決め事であること。自分のチームの決め事であること。
    $this->CheckStatus($result, $my_team->id);
    // if($result->user_id === Auth::id() || $result->approval !== '0' || $result->team_id !== $my_team->id){
    //   return response()->json(['error'=>'エラーが発生しました。'],422);
    // }

    // バリデーションOK
    $agreement->where('id', $id)->update(['approval' => 'deny']);

  }

  // 共通のバリデーション
  public function CheckId($id)
  {
    // 空文字チェック
    if($id === ""){
      abort(response()->json(['error'=>'エラーが発生しました。'],422));
    }

    // 数字であることを確認
    $result = preg_match('/^[0-9]+$/',$id);
    // エラー判定
    if(!$result){
      abort(response()->json(['error'=>'エラーが発生しました。'],422));
    }
  }
  // 承認ステータスのバリデーション
  public function CheckStatus($result, $team_id)
  {
    if($result->user_id === Auth::id() || $result->team_id !== $team_id || $result->approval !== '0'){
      abort(response()->json(['error'=>'エラーが発生しました。'],422));
    }
  }
}
