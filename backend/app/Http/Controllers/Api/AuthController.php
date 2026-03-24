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

        $invitation->increment('used_count');

        if ($invitation->group_id) {
            \App\Models\GroupMember::firstOrCreate([
                'group_id' => $invitation->group_id,
                'user_id'  => $user->id,
            ], [
                'joined_at' => now(),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => '登録が完了しました',
            'user'    => $user,
        ]);
    }

    // 招待なしの通常登録
    public function registerPublic(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:50'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'guest', // 無所属はguest
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => '登録が完了しました',
            'user'    => $user,
        ]);
    }

    // ユーザーが自分でパックを追加
    public function addPack(Request $request)
    {
        $request->validate([
            'pack_id' => ['required', 'exists:task_packs,id'],
        ]);

        $user = $request->user();

        $pack = \App\Models\TaskPack::where('id', $request->pack_id)
            ->where(function ($q) {
                $q->where('is_official', true)
                    ->orWhere('is_public', true);
            })->firstOrFail();

        // パック用のグループを自動作成 or 取得
        $group = \App\Models\Group::firstOrCreate(
            ['name' => 'pack_' . $pack->id],
            [
                'organization_id' => null,
                'created_by'      => 1, // システムユーザー
                'is_public'       => true,
            ]
        );

        // グループにパックを紐付け
        $group->taskPacks()->syncWithoutDetaching([$pack->id]);

        // ユーザーをグループに追加
        \App\Models\GroupMember::firstOrCreate([
            'group_id' => $group->id,
            'user_id'  => $user->id,
        ], ['joined_at' => now()]);

        return response()->json(['message' => 'パックを追加しました']);
    }
}
