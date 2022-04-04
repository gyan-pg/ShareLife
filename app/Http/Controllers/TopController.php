<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopController extends Controller
{
  public function top()
  {
    return view('top');
  }

  public function index()
  {
    $url = url('');
    return view('index', compact('url'));
  }
}
