<?php

namespace App\Notifications;

use App\Mail\BareMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    // 文字列である引数$tokenと、BaraMailクラスのインスタンスである引数$mailをプロパティに代入
    public function __construct(string $token, BareMail $mail)
    {
        $this->token = $token;
        $this->mail = $mail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // toMailメソッド内で、メールの具体的な設定
        return $this->mail
            // formメソッド
            // 第一引数には送信元メールアドレス
            // 第二引数にはメールの送信者名(省略可)
            ->from(config('mail.from.address'), config('mail.from.name'))
            // toメソッド　送信先メールアドレスを渡す
            // $notifiableには、パスワード再設定メール送信先となるUserモデルが代入
            // パスワード再設定メール送信先ユーザーのメールアドレスを取得
            ->to($notifiable->email)
            // メールの件名を渡します
            ->subject('[memo]パスワード再設定')
            // テキスト形式のメールを送る場合に使う
            ->text('emails.password_reset')
            // Bladeに渡す変数を、withメソッドに連想配列形式で渡します
            ->with([
                // キーurlの値には、route関数を使ってpassword.resetのルーティングをセット
                // http://localhost/password/reset/(トークン)?email=(メールアドレス)
                'url' => route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->email,
                ]),
                // キーcountの値には、パスワード設定画面へのURLの有効期限(単位は分)がセット
                'count' => config(
                    'auth.passwords.' .
                    config('auth.defaults.passwords') .
                    '.expire'
                ),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
