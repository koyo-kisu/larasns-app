<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // RegistersUsersトレイスを使用
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // unique:users
            // usersテーブルの他のレコードのnameカラムに、(ユーザー登録画面から)リクエストされたnameと同じ値が無いことをチェック
            'name' => ['required', 'string', 'alpha_num', 'min:3', 'max:16', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // "Provider(サービスの提供者)"のユーザーを登録する画面を表示するアクションメソッド
    public function showProviderUserRegistrationForm(Request $request, string $provider)
    {
        // トークンを使ってGoogleからユーザー情報を再取得
        $token = $request->token;
 
        $providerUser = Socialite::driver($provider)->userFromToken($token);
 
        return view('auth.social_register', [
            'provider' => $provider,
            'email' => $providerUser->getEmail(),
            'token' => $token,
        ]);
    }

    // 「Provider(サービスの提供者)」のユーザーを登録するアクションメソッド
    public function registerProviderUser(Request $request, string $provider)
    {
        $request->validate([
            'name' => ['required', 'string', 'alpha_num', 'min:3', 'max:16', 'unique:users'],
            'token' => ['required', 'string'],
        ]);
        
        // Googleから発行済みのトークンの値が取得
        $token = $request->token;

        // Googleから発行済みのトークンを使って、GoogleのAPIに再度ユーザー情報の問い合わせを行います
        $providerUser = Socialite::driver($provider)->userFromToken($token);

        $user = User::create([
            'name' => $request->name,
            'email' => $providerUser->getEmail(),
            // パスワード登録不要とするので、一律null
            'password' => null,
        ]);

        $this->guard()->login($user, true);
        
        // ユーザー登録後にユーザーをログイン済み状態にするためにログイン処理を実行
        // ユーザー登録後の画面にリダイレクトさせています
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
