<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileEditRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileEditController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  // プロフィール画像をno imageにする
  public function resetImage() {
    $user = new User();
    $current_user = Auth::user();

    // 設定していた画像を消去する。
    // 初期値の画像は消去しない。
    if ($current_user->image !== '/storage/images/noimage.png') {
      $this->delete_image($current_user->image);
    }

    $user->where('id', Auth::id())->update(['image' => '/storage/images/noimage.png']);
  }

  public function updateImage(Request $request)
  {
    $request->validate([
      'image' => 'file|mimes:png,jpeg,jpg,gif|max:500'
    ]);

    $path = $request->file('image')->store('public/images');
    $path = str_replace('public', '/storage', $path);
    $user = new User();
    $current_user = Auth::user();

    // 初期値の画像は消去しない。
    if ($current_user->image !== '/storage/images/noimage.png') {
      $this->delete_image($current_user->image);
    }
    // 画像のパスをアップロード
    $user->where('id', Auth::id())->update(['image' => $path]);
  }

  public function updateProfile(ProfileEditRequest $request)
  {

    $user = new User();
    $user->where('id', Auth::id())->update(['name' => $request->name, 'email' => $request->email]);
  }

  public function delete_image($path){
    // Storage::disk('local')のルートが、storage/appなので、ファイルパスを変更する。
    $path_delete = str_replace('/storage', '/public', $path);
    Storage::disk('local')->delete($path_delete);
  }
}
