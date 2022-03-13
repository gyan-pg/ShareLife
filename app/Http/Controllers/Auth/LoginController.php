<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Team;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // AuthenticatesUsers.phpのメソッドを上書き。registerコントローラと同じ。
    protected function authenticated(Request $request, $user)
    {
      $response = new Collection();
      $member = new User();
      $team = new Team();
      $result = $team->where('user1_id', $user->id)->orWhere('user2_id', $user->id)->first();
      $response->push($user)->push($result);
      // チームがある場合($resultがある場合)、オーナーとパートナーの情報を詰める
      if ($result) {
        $owner = $member->where('id', $result->user1_id)->first();
        $teamMember = $member->where('id', $result->user2_id)->first();
        $response->push($owner)->push($teamMember);
      }

      return $response;
    }

    // AuthenticatesUsers.phpのメソッドを上書き。セッションを再生成する。
    protected function loggedOut(Request $request)
    {
      $request->session()->regenerate();
      // 値としては空のjsonを返す(xsrf-tokenなどの標準で返しているものは除く)
      return response()->json();
    }
}
