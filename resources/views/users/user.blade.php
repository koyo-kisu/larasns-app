<div class="card mt-3">
  <div class="card-body">
    <div class="d-flex flex-row">
      <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
        <i class="fas fa-user-circle fa-3x"></i>
      </a>
      <!-- ログイン中のユーザーのidと、ユーザーページに表示されるユーザーのidを比較し、不一致の場合のみフォローボタンを表示する -->
      <!-- Authファサードのcheckメソッドを使うと、ログイン中かどうかを論理値で返します -->
      <!-- プロパティendpointを新たに定義し、Laravelのroute関数で取得したURLを渡しています -->
      @if( Auth::id() !== $user->id )
        <follow-button
          class="ml-auto"
          :initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'
          :authorized='@json(Auth::check())'
          endpoint="{{ route('users.follow', ['name' => $user->name]) }}"
        >
        </follow-button>
      @endif
    </div>
    <h2 class="h5 card-title m-0">
      <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
        {{ $user->name }}
      </a>
    </h2>
  </div>
  <div class="card-body">
    <div class="card-text">
      <a href="" class="text-muted">
      <!-- getCountFollowingssAttributeアクセサを利用する記述 -->
        <b>{{ $user->count_followings }}</b> フォロー中
      </a>
      <a href="" class="text-muted">
      <!-- getCountFollowersAttributeアクセサを利用する記述 -->
        <b>{{ $user->count_followers }}</b> フォロワー
      </a>
    </div>
  </div>
</div>