<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
  public function index()
  {
    // sortByDescメソッドを使いcreated_atの降順で並び替え
    $articles = Article::all()->sortByDesc('create_at');
    return view('articles.index', ['articles' => $articles]);
  }

  public function create()
  {
    return view('articles.create');
  }

  // 引数$requestはArticleRequestクラスのインスタンスである、ということを宣言
  // 引数$articleはArticleモデルのインスタンスである、ということを宣言
  // 外で生成されたクラスのインスタンスをメソッドの引数として受け取る流れをDI(Dependency Injection)と言います
  public function store(ArticleRequest $request, Article $article)
  {
    // 記事投稿画面から送信されたPOSTリクエストのパラメータを以下のように配列で取得できます
    $article->fill($request->all());

    // ログイン済みユーザーidをArticleモデルのインスタンスのuser_idプロパティに代入
    $article->user_id = $request->user()->id;
    $article->save();
    return redirect()->route('articles.index');
  }
}
