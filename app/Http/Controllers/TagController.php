<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(string $name)
    {
        // whereメソッドを使って、$nameと一致するタグ名を持つタグモデルをコレクションで取得
        // tagsテーブルのnameカラムにはユニーク制約を付けてあるので、tagsテーブルには同じタグ名を持つレコードは存在しません
        // whereメソッドによって取得できるタグモデルは最大でも1件となります
        $tag = Tag::where('name', $name)->first();
 
        return view('tags.show', ['tag' => $tag]);
    }
}
