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
// 名前付きルーティング
// '/'にarticles.indexを割り当てる
Route::get('/', 'ArticleController@index')->name('articles.index');

// リソースフルルート
// articles.indexを除く部分的リソースルート
Route::resource('/articles', 'ArticleController')->except(['index']);