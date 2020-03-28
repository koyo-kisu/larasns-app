<?php

namespace App;

use App\Mail\BareMail;
use App\Notifications\PasswordResetNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 通知クラスである、PasswordResetNotificationクラスのインスタンスを生成し、notifyメソッドに渡します
    // カスタマイズしたテキストメールがパスワード再設定メールとして送信される
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token, new BareMail()));
    }

    // ユーザーモデルから「フォロワーであるユーザー」のモデルにアクセス可能にするメソッド
    // フォローにおけるユーザーモデルとユーザーモデルの関係は多対多となります
    // 第一引数には関係するモデルのモデル名
    // 第二引数には中間テーブルのテーブル名
    // リレーション元のusersテーブルのidは、中間テーブルのfollowee_idと紐付く
    // リレーション先のusersテーブルのidは、中間テーブルのfollower_idと紐付く
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    // これからフォローするユーザー、あるいはフォロー中のユーザーのモデルにアクセス可能にするためのリレーションメソッド
    // followingsメソッドは、既存のfollowersメソッドとは、第三・第四引数が逆になっています
    // リレーション元のusersテーブルのidは、中間テーブルのfollower_idと紐付く
    // リレーション先のusersテーブルのidは、中間テーブルのfollowee_idと紐付く
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    // あるユーザーをフォロー中かどうか判定するメソッド
    // nullかどうか判定する
    // 記事モデルからlikesテーブル経由で紐付くユーザーモデルが、コレクション(配列を拡張したもの)で返ります
    // whereメソッドの第一引数にキー名、第二引数に値を渡すと、その条件に一致するコレクションが返ります
    // countメソッドは、コレクションの要素数を数えて、数値を返します
    // 型キャスト：(bool)と記述することで変数を論理値、つまりtrueかfalseに変換
    public function isFollowedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->followers->where('id', $user->id)->count()
            : false;
    }

    // ユーザーモデルに現在のフォロー中・フォロワー数を算出するアクセサ
    public function getCountFollowersAttribute(): int
    {
        // このユーザーモデルの全フォロワーがコレクションで返ります
        return $this->followers->count();
    }
 
    // ユーザーモデルに現在のフォロー中・フォロワー数を算出するアクセサ
    public function getCountFollowingsAttribute(): int
    {
        // このユーザーモデルが現在フォロー中のユーザー数が求まります
        return $this->followings->count();
    }
}
