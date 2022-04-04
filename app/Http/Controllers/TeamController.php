<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Mail\PartnerIvitationEmail;
use App\Rules\MyEmail;
use App\Team;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
  // チームの情報を返す
  public function userTeam () {
    if (Auth::check()) {
      $response = new Collection();
      $team = new Team();
      $result = $team->where('user1_id', Auth::id())->orWhere('user2_id', Auth::id())->first();
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
    
    $owner = User::find(Auth::id());
    $partner_id = $user->select('id')->where('email', $request->email)->first();
    $partner = User::find($partner_id->id);

    Mail::to($request->email)->send(new PartnerIvitationEmail($owner));

    $team->fill(['user1_id' => Auth::id(), 'user2_id' => $partner_id->id, 'status' => 'unapproved'])->save();


    $response->push($team)->push($owner)->push($partner);

    return $response;
  }

  public function approveTeam (Request $request) {
    $team = new Team();
    $result = $team->where('id', $request->id)->update(['status' => 'approve']);
    // 変更に成功した時
    if ($result) {
      return 'approve';
    }
    return $result;
  }

  public function denyTeam ()
  {
    $user = new User();
    $team = new Team();
    $id = Auth::id();
    
    $team->where('user2_id', $id)->delete();
  }
}
