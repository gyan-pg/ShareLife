<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Rules\MyEmail;
use App\Team;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
  // チームの情報を返す
  public function userTeam () {
    if (Auth::check()) {
      $response = new Collection();
      $team = new Team();
      $result = $team->where('user1_id', Auth::id())->orWhere('user2_id', Auth::id())->first();
      Log::debug($result);
      // チーム情報がある時は、それぞれのユーザー情報を取得
      if ($result) {
        $owner = User::find($result->user1_id);
        $partner = User::find($result->user2_id);
        $response->push($result)->push($owner)->push($partner);
        return $response;
      }
      return $result;
    }
    return null;
  }

  public function createTeam (CreateTeamRequest $request)
  {
    $response = new Collection();
    $team = new Team();
    $user = new User();
    
    $partner_id = $user->select('id')->where('email', $request->email)->first();
    // $partner_id = {"id": 1}みたいな形で取得

    $team->fill(['user1_id' => Auth::id(), 'user2_id' => $partner_id->id, 'status' => 'unapproved'])->save();
    
    $owner = User::find(Auth::id());
    $partner = User::find($partner_id->id);

    $response->push($team)->push($owner)->push($partner);


    return $response;
  }

  public function approveTeam (Request $request) {
    Log::debug($request);
    $team = new Team();
    $result = $team->where('id', $request->id)->update(['status' => 'approve']);
    // 変更に成功した時
    if ($result) {
      return 'approve';
    }
    return $result;
  }

  public function denyTeam () {

  }
}
