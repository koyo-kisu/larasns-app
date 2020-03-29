<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider(string $provider)
    {
        // dirverメソッドに、外部のサービス名を渡します
        // その上で、redirectメソッドを使うことで、そのサービスの画面へリダイレクトされます
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, string $provider)
    {
        // Googleから取得したユーザー情報をプロパティとして持っています
        $providerUser = Socialite::driver($provider)->stateless()->user();
 
        // Googleから取得したユーザー情報からメールアドレスを取り出す
        // usersテーブルに存在するかを調べています
        // Googleから取得したメールアドレスと同じメールアドレスを持つユーザーモデルが代入されます
        $user = User::where('email', $providerUser->getEmail())->first();
 
        if ($user) {
            // ユーザーをログイン状態にしています
            $this->guard()->login($user, true);
            // ログイン後の画面(記事一覧画面)へ遷移する
            return $this->sendLoginResponse($request);
        }
        
        // $userがnullの場合の処理は次のパートでここに書く予定
    }
}