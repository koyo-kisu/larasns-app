<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


// フォームリクエスト
// 記事投稿画面や記事更新画面から送信された記事タイトルや記事本文のバリデーションなどを行います
class ArticleRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    // jsonを指定し、JSON形式かどうかのバリデーションを行います
    // 半角スペースが無いことをチェックする正規表現です
    return [
      'title' => 'タイトル',
      'body' => '本文',
      'tags' => 'タグ',
    ];
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
      return [
        'title' => 'required|max:50',
        'body' => 'required|max:500',
        'tags' => 'json|regex:/^(?!.*\s).+$/u',
      ];
  }

  // フォームリクエストのバリデーションが成功した後に自動的に呼ばれるメソッド
  public function passedValidation()
  {
    // JSON形式の文字列であるタグ情報を連想配列に変換
    // さらにそれをコレクションに変換
      $this->tags = collect(json_decode($this->tags))
          // 最初の5個だけが残ります
          ->slice(0, 5)
          // コレクションの各要素に対して順に処理を行い、新しいコレクションを作成します
          // タグ情報のtextだけを返す
          ->map(function ($requestTag) {
              return $requestTag->text;
          });
  }
}
