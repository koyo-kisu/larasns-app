<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// 「Googleでログイン」ボタンを押した後のルーティング
Route::prefix('login')->name('login.')->group(function () {
    Route::get('/{provider}', 'Auth\LoginController@redirectToProvider')->name('{provider}');

    Route::get('/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('{provider}.callback');
});

Route::prefix('register')->name('register.')->group(function () {
    Route::get('/{provider}', 'Auth\RegisterController@showProviderUserRegistrationForm')->name('{provider}');

    Route::post('/{provider}', 'Auth\RegisterController@registerProviderUser');
});

// 名前付きルーティング
// '/'にarticles.indexを割り当てる
Route::get('/', 'ArticleController@index')->name('articles.index');

// リソースフルルート
// articles.index/showを除いてlogin有無をチェック
// authミドルウェア: login有無をチェック
Route::resource('/articles', 'ArticleController')->except(['index', 'show'])->middleware('auth');

// showアクションメソッドに対してauthミドルウェアを使わないようにしています
Route::resource('/articles', 'ArticleController')->only(['show']);

// ルートグループ
// prefixメソッドは、引数として渡した文字列をURIの先頭に付けます
// nameメソッドは、ルーティングに名前を付けます
Route::prefix('articles')->name('articles.')->group(function () {
    Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
    Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
});

// 新しくタグ別記事一覧画面のルーティングを定義する
Route::get('/tags/{name}', 'TagController@show')->name('tags.show');


// ルートグループ
// prefixメソッドは、引数として渡した文字列をURIの先頭に付けます
// nameメソッドは、ルーティングに名前を付けます
// prefixメソッドとnameメソッドを使った上でgroupメソッドを使うことで、URIとルーティングの名前を簡潔に記述できるようにしています
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/{name}', 'UserController@show')->name('show');
    // いいねタブが押された場合のユーザーページ表示のルーティング
    Route::get('/{name}/likes', 'UserController@likes')->name('likes');

    // フォロー中のユーザー・フォロワーの一覧がそれぞれ表示されるようにするルーティング
    Route::get('/{name}/followings', 'UserController@followings')->name('followings');
    Route::get('/{name}/followers', 'UserController@followers')->name('followers');

    Route::middleware('auth')->group(function () {
        Route::put('/{name}/follow', 'UserController@follow')->name('follow');
        Route::delete('/{name}/follow', 'UserController@unfollow')->name('unfollow');
    });
});