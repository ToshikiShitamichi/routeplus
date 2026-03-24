<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Invitation;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['メールアドレスまたはパスワードが正しくありません。'],
            ]);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'ログインしました',
            'user' => $request->user(),
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'ログアウトしました',
        ]);
    }
    // 招待経由のユーザー登録
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:50'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'token'    => ['required', 'string'],
        ]);

        $invitation = Invitation::where('token', $request->token)->first();

        if (!$invitation || !$invitation->isValid()) {
            return response()->json([
                'message' => '招待リンクが無効または期限切れです'
            ], 422);
        }

        $user = \App\Models\User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'role'            => 'student',
            'organization_id' => $invitation->organization_id,
            'invitation_id'   => $invitation->id,
        ]);

        // ✅ used_at の代わりに used_count を増やす
        $invitation->increment('used_count');

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => '登録が完了しました',
            'user'    => $user,
        ]);
    }
}
