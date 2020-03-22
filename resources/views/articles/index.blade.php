@extends('app')

@section('title', '記事一覧')

@section('content')
  <!-- includeを使うことで、別のビューを取り込めます。 -->
  @include('nav')
  <div class="container">
  @foreach($articles as $article)
    <!-- 1記事分だけ別ブレードに分けてforeachで繰り返す -->
    @include('articles.card')
  @endforeach
  </div>
@endsection