@extends('app')

@section('title', $user->name . 'の記事')

@section('content')
  @include('nav')
  <div class="container">
    @include('users.user')
    <!-- 第二引数に変数名とその値を連想配列形式で渡すことができます -->
    @include('users.tabs', ['hasArticles' => true, 'hasLikes' => false])
    @foreach($articles as $article)
      @include('articles.card')
    @endforeach
  </div>
@endsection