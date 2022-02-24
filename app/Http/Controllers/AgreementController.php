<?php

namespace App\Http\Controllers;

use App\Agreement;
use App\Http\Requests\AgreementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgreementController extends Controller
{
  // 決め事の登録を行う
  public function register(AgreementRequest $request)
  {
    $agreement = new Agreement;
    $agreement->fill($request->all())->save();
  }

  // 決め事の一覧を取得
  public function showList($id)
  {
    $agreement = new Agreement;
    return $agreement->where('team_id', $id)->get();
  }
}
