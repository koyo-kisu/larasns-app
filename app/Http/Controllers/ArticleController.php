<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;
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
    // タグテーブルから全てのタグ情報を取得し、Bladeに変数$allTagNamesとして渡しています
    // タグテーブルに登録済みのタグ数が膨大になってきた場合考慮必要
    $allTagNames = Tag::all()->map(function ($tag) {
      return ['text' => $tag->name];
    });

    return view('articles.create', [
        'allTagNames' => $allTagNames,
    ]);
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

    // eachメソッドに渡すコールバックは、クロージャ(無名関数)としています
    // クロージャの第一引数にはコレクションの値が、第二引数にはコレクションのキーが入ります
    // 第二引数は今回のクロージャの中の処理で特に使わないので、省略しています
    // use ($article)とあるのは、クロージャの中の処理で変数$articleを使うためです
    $request->tags->each(function ($tagName) use ($article) {
      // 引数として渡した「カラム名と値のペア」を持つレコードがテーブルに存在するかどうかを探し、もし存在すればそのモデルを返します
      $tag = Tag::firstOrCreate(['name' => $tagName]);
      $article->tags()->attach($tag);
    });

    return redirect()->route('articles.index');
  }

  public function edit(Article $article)
  {
    $tagNames = $article->tags->map(function ($tag) {
      return ['text' => $tag->name];
    });

    // タグテーブルから全てのタグ情報を取得し、Bladeに変数$allTagNamesとして渡しています
    // タグテーブルに登録済みのタグ数が膨大になってきた場合に考慮必要
    $allTagNames = Tag::all()->map(function ($tag) {
      return ['text' => $tag->name];
    });

    return view('articles.edit', [
      'article' => $article,
      'tagNames' => $tagNames,
      'allTagNames' => $allTagNames,
    ]);
  }

  public function update(ArticleRequest $request, Article $article)
  {
    $article->fill($request->all())->save();

    // detachメソッドを引数無しで使うと、そのリレーションを紐付ける中間テーブルのレコードが全削除されます
    // 一旦全削除して、新しくタグを登録し直す
    $article->tags()->detach();
      $request->tags->each(function ($tagName) use ($article) {
          $tag = Tag::firstOrCreate(['name' => $tagName]);
          $article->tags()->attach($tag);
      });

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
