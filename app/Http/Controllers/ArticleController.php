<?php

namespace App\Http\Controllers;

use App\Article;
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
}
