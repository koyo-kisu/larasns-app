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
        // ユーザーモデルに追加したリレーションのarticlesを使って、ユーザーの投稿した記事モデルをコレクションで取得
        $articles = $user->articles->sortByDesc('created_at');
 
        return view('users.show', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    public function likes(string $name)
    {
        $user = User::where('name', $name)->first();
        
        // いいねした記事モデルのコレクションを代入
        $articles = $user->likes->sortByDesc('created_at');
 
        return view('users.likes', [
            'user' => $user,
            'articles' => $articles,
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

    public function followings(string $name)
    {
        // ユーザーモデルのリレーションfollowings/followersを使用して、フォロー中・フォロワーのユーザーモデルをコレクションで取得しています
        $user = User::where('name', $name)->first();
 
        $followings = $user->followings->sortByDesc('created_at');
 
        return view('users.followings', [
            'user' => $user,
            'followings' => $followings,
        ]);
    }
    
    public function followers(string $name)
    {
        $user = User::where('name', $name)->first();
 
        $followers = $user->followers->sortByDesc('created_at');
 
        return view('users.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }
}
