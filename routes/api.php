<?php

use App\Http\Controllers\AdjustmentController;
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
// 仮会員登録
Route::post('/preRegister', 'PreRegisterController@sendEmail');
// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');
// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
// 退会
Route::post('/withdraw', 'WithdrawController@withdraw');
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
// --- 決め事 ---
// 決め事の登録
Route::post('/agreement/register', 'AgreementController@register');
// 決め事の取得
Route::get('/agreement/{id}/list', 'AgreementController@showList');
// 決め事の消去
Route::delete('/agreement/{id}', 'AgreementController@delete');
// 決め事の承認
Route::put('/agreement/approve', 'AgreementController@approve');
// 決め事の編集
Route::put('/agreement/update', 'AgreementController@updateAgreement');
// 決め事の拒否
Route::put('/agreement/deny', 'AgreementController@denyAgreement');
// --- 予定 ---
// 予定の登録
Route::post('/schedule/register', 'ScheduleController@register');
// 予定の取得
Route::get('/schedule/show', 'ScheduleController@show');
// 予定の削除
Route::delete('/schedule/delete/{id}', 'ScheduleController@delete');
// 予定の変更(ドラッグイベント)
Route::put('/schedule/changeDay', 'ScheduleController@changeDay');
// 予定の変更(イベント詳細から)
Route::put('/schedule/changeSchedule', 'ScheduleController@changeSchedule');
// -- プロフィール --
// 画像の更新
Route::post('/profile/image', 'ProfileEditController@updateImage');
// 画像のリセット
Route::put('/profile/image/reset', 'ProfileEditController@resetImage');
// その他情報の更新
Route::put('/profile/update', 'ProfileEditController@updateProfile');
// -- 精算 --
// 登録
Route::post('/adjustment/register', 'AdjustmentController@register');
// 費目の取得
Route::get('/adjustment/himoku/get', 'AdjustmentController@getHimoku');
// 精算の一覧の取得
Route::get('/adjustment/show/{date}', 'AdjustmentController@show');
// 精算の削除
Route::delete('/adjustment/delete/{id}', 'AdjustmentController@delete');

// 休日一覧の取得
Route::get('/getHoliday/{year}', 'ScheduleController@getHoliday');