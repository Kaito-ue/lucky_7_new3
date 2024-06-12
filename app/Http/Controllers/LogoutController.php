<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 追加

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout(); // ユーザーをログアウトさせる
        $request->session()->invalidate(); // セッションを無効化する
        $request->session()->regenerateToken(); // セッショントークンを再生成する

        return redirect('/'); // ログアウト後にリダイレクトする場所を指定
    }
}
