<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
  // クラスのインスタンスが生成された時に初期処理として必ず実行
  public function __construct()
  {
    // 第一引数には、モデルのクラス名
    // 第二引数には、そのモデルのIDがセットされる、ルーティングのパラメータ名

    // 各アクションメソッドを処理する、しないをポリシーのメソッドで定義した判定条件の通りとなります
    $this->authorizeResource(Article::class, 'article');
  }

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

  public function edit(Article $article)
  {
    return view('articles.edit', ['article' => $article]);
  }

  public function update(ArticleRequest $request, Article $article)
  {
    $article->fill($request->all())->save();
    return redirect()->route('articles.index');
  }

  public function destory(Article $article)
  {
    $article->delete();
    return redirect()->route('atricles.index');
  }

  public function show(Article $article)
  {
    return view('articles.show', ['article' => $article]);
  }

  public function like(Request $request, Article $article)
  {
    // 記事モデルからlikesテーブル経由で紐付いているユーザーモデルのコレクションが返ります
    // attach: この記事モデルと、リクエストを送信したユーザーのユーザーモデルの両者を紐づけるlikesテーブルのレコードが新規登録されます
    // detachメソッドであれば、逆に削除されます
    $article->likes()->detach($request->user()->id);
    $article->likes()->attach($request->user()->id);

    // likesテーブルを更新した後は、上記の連想配列をクライアントにレスポンスしています
    return [
        'id' => $article->id,
        'countLikes' => $article->count_likes,
    ];
  }

    public function unlike(Request $request, Article $article)
  {
    $article->likes()->detach($request->user()->id);

    return [
        'id' => $article->id,
        'countLikes' => $article->count_likes,
    ];
  }
}
