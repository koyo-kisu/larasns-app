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

    // 引数$nameには、URLusers/{name}/followの{name}(フォローされる側のユーザーの名前)の部分が渡ってきます
    public function follow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();
 
        // 自分自身をフォローできないようにします
        // abort関数は、第一引数にステータスコードを渡します
        // 第二引数にはクライアントにレスポンスするテキストを渡すことができます
        if ($user->id === $request->user()->id)
        {
            return abort('404', 'Cannot follow yourself.');
        }
 
        // request->user()で、リクエストを行なったユーザーのユーザーモデルが返ります
        // followingsメソッドは、多対多のリレーション(BelongsToManyクラスのインスタンス)が返ることを想定しています
        // いいねボタンのように一旦削除してから新規登録する
        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);
 
        // どのユーザーへのフォローが成功したかがわかるよう、ユーザーの名前を返しています
        return ['name' => $name];
    }
    
    public function unfollow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();
 
        if ($user->id === $request->user()->id)
        {
            return abort('404', 'Cannot follow yourself.');
        }
 
        // followメソッド同様ですがattachメソッドのみ実行
        $request->user()->followings()->detach($user);
 
        return ['name' => $name];
    }
}
