<?php

namespace App\Policies;

use App\Article;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any articles.
     *
     * @param  \App\User  $user
     * @return mixed
     */

    // '?'その引数がnullであることも許容されます
    public function viewAny(?User $user)
    {
        // コントローラーのindexアクションメソッドに対応
        return true;
    }

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */

    // '?'その引数がnullであることも許容されます
    public function view(?User $user, Article $article)
    {
        // コントローラーのshowアクションメソッドに対応
        return true;
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // コントローラーのcreate/storeアクションメソッドに対応
        // 投稿画面で投稿ボタンを押した段階ではユーザーIDを比較するといったことはできないため一律true
        return true;
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function update(User $user, Article $article)
    {
        // コントローラーのedit/updateアクションメソッドに対応
        // ログイン中のユーザーIDと記事のユーザーIDが一致する場合trueを返す
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function delete(User $user, Article $article)
    {
        // コントローラーのdeleteアクションメソッドに対応
        // ログイン中のユーザーIDと記事のユーザーIDが一致する場合trueを返す
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can restore the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function restore(User $user, Article $article)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function forceDelete(User $user, Article $article)
    {
        //
    }
}
