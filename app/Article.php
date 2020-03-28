<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
  // 複数代入するカラムを指定
  // 不正なリクエストへの対策
  protected $fillable = [
    'title',
    'body'
  ];

  // userメソッドの戻り値が、BelongsToクラスであることを宣言
  public function user(): BelongsTo
  {
    // $thisは、Articleクラスのインスタンス自身
    // 記事と、記事を書いたユーザーは多対1の関係ですが、そのような関係性の場合には、belongsToメソッドを使います。
    return $this->belongsTo('App\User');
  }

  public function likes(): BelongsToMany
  {
    // 「いいね」における記事モデルとユーザーモデルの関係は多対多
    // 第一引数には関係するモデルのモデル名
    // 第二引数には中間テーブルのテーブル名
    return $this->belongsToMany('App\User', 'likes')->withTimestamps();
  }

  // $userの型が、Userモデルであることをnullableな型として宣言
  public function isLikedBy(?User $user): bool
  {
    return $user
    // nullかどうか判定する
    // 記事モデルからlikesテーブル経由で紐付くユーザーモデルが、コレクション(配列を拡張したもの)で返ります
    // whereメソッドの第一引数にキー名、第二引数に値を渡すと、その条件に一致するコレクションが返ります
    // countメソッドは、コレクションの要素数を数えて、数値を返します
    // 型キャスト：(bool)と記述することで変数を論理値、つまりtrueかfalseに変換
      ? (bool)$this->likes->where('id', $user->id)->count()
      : false;
  }

  // アクセサ
  public function getCountLikesAttribute(): int
  {
    // 記事モデルからlikesテーブル経由で紐付いているユーザーモデルが、コレクション(配列を拡張したもの)で返ります
    return $this->likes->count();
  }

  // BelongsToMany: 記事モデルとタグモデルの関係は多対多となります
  // 第二引数には中間テーブルのテーブル名を渡します
  // 中間テーブルの名前がarticle_tagといった2つのモデル名の単数形をアルファベット順に結合した名前ですので、第二引数は省略可能
  public function tags(): BelongsToMany
  {
    return $this->belongsToMany('App\Tag')->withTimestamps();
  }
}