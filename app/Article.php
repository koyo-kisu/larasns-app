<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

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
}
