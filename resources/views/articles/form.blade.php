@csrf
<div class="md-form">
  <label>タイトル</label>
  <!-- NULL合体演算子 -->
  <!-- 式1 ?? 式2 -->
  <!-- 式1がnullでない場合（更新）は、式1が結果となる
       式1がnullである場合（投稿）は、式2が結果となる -->
  <input type="text" name="title" class="form-control" required value="{{ $article->title ?? old('title') }}">
</div>
<div class="form-group">
  <article-tags-input
  >
  </article-tags-input>
</div>
<div class="form-group">
  <label></label>
  <!-- NULL合体演算子 -->
  <textarea name="body" required class="form-control" rows="16" placeholder="本文">{{ $article->body ?? old('body') }}</textarea>
</div>