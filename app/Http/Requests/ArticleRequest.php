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
    return [
      'title' => 'タイトル',
      'body' => '本文',
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
      ];
  }
}
