<?php

use App\Http\Controllers\AgreementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');
// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
// ログインユーザーを返却するだけのapi
Route::get('/user', function(){
  return Auth::user();
})->name('user');
// ログインしているユーザーのチームidを返却するだけのapi
Route::get('/user/team', 'TeamController@userTeam');
// トークンリフレッシュ
Route::get('/reflesh-token', function(Request $request){
  $request->session()->regenerateToken();
  return response()->json();
});
// ユーザーのチームへの招待
Route::post('/team/invitation', 'TeamController@createTeam');
// ユーザーのチームへの参加承認・拒否
// 参加
Route::put('/team/invitation', 'TeamController@approveTeam');
// 拒否
Route::delete('/team/invitation', 'TeamController@denyTeam');
// 決め事の登録
Route::post('/agreement/register', 'AgreementController@register');
// 決め事の取得
Route::get('/agreement/{id}/list', 'AgreementController@showList');
// 予定の登録
Route::post('/schedule/register', 'ScheduleController@register');
// 予定の取得
Route::get('/schedule/show', 'ScheduleController@show');
// 予定の削除
Route::delete('/schedule/delete', 'ScheduleController@delete');
// 予定の変更(ドラッグイベント)
Route::put('/schedule/changeDay', 'ScheduleController@changeDay');