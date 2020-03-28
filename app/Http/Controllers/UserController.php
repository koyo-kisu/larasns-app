<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $name)
    {
        // $nameと一致する名前を持つユーザーモデルをコレクションで取得
        $user = User::where('name', $name)->first();
 
        return view('users.show', [
            'user' => $user,
        ]);
    }
}
