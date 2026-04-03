<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OperatorController extends Controller
{
    private function checkOperator(Request $request)
    {
        if ($request->user()->role !== 'operator') {
            abort(403, '運営者のみアクセスできます');
        }
    }

    // 組織一覧
    public function organizations(Request $request)
    {
        $this->checkOperator($request);
        $orgs = Organization::withCount('users')->get();
        return response()->json($orgs);
    }

    // 組織作成
    public function createOrganization(Request $request)
    {
        $this->checkOperator($request);
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:50|unique:organizations,slug',
        ]);

        $org = Organization::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return response()->json($org, 201);
    }

    // 管理者招待URL発行
    public function createAdminInvitation(Request $request)
    {
        $this->checkOperator($request);
        $request->validate([
            'label' => 'nullable|string|max:100',
        ]);

        $invitation = Invitation::create([
            'organization_id' => null,
            'invited_by'      => $request->user()->id,
            'token'           => \Illuminate\Support\Str::random(32),
            'label' => $request->label ?? now()->format('Y/m/d H:i') . ' 発行',
            'max_uses'        => 1,
            'used_count'      => 0,
            'expires_at'      => now()->addDays(7),
            'is_admin_invite' => true,
        ]);

        $url = env('FRONTEND_URL', 'http://localhost:5173') . '/register/admin?token=' . $invitation->token;

        return response()->json([
            'invitation' => $invitation,
            'invite_url' => $url,
        ], 201);
    }

    // 管理者一覧
    public function admins(Request $request)
    {
        $this->checkOperator($request);
        $admins = User::where('role', 'admin')
            ->with('organization')
            ->get()
            ->map(fn($u) => [
                'id'           => $u->id,
                'name'         => $u->name,
                'email'        => $u->email,
                'organization' => $u->organization?->name,
            ]);
        return response()->json($admins);
    }

    // 公式課題一覧
    public function officialTasks(Request $request)
    {
        $this->checkOperator($request);
        $tasks = \App\Models\TaskMaster::where('is_official', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get();
        return response()->json($tasks);
    }

    // 公式課題作成
    public function createOfficialTask(Request $request)
    {
        $this->checkOperator($request);

        $request->validate([
            'category'    => 'required|string|max:100',
            'level'       => 'required|integer|min:1',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'visibility'  => 'required|in:official,public',
        ]);

        $task = \App\Models\TaskMaster::create([
            'category'        => $request->category,
            'order'           => \App\Models\TaskMaster::where('category', $request->category)->max('order') + 1,
            'level'           => $request->level,
            'title'           => $request->title,
            'description'     => $request->description,
            'is_official'     => true,
            'visibility' => 'official',
            'organization_id' => null,
            'created_by'      => $request->user()->id,
        ]);

        return response()->json($task, 201);
    }

    // 公式課題更新
    public function updateOfficialTask(Request $request, \App\Models\TaskMaster $taskMaster)
    {
        $this->checkOperator($request);

        $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|required|string',
            'level'       => 'sometimes|integer|min:1',
        ]);

        $taskMaster->update($request->only(['title', 'description', 'level', 'visibility']));
        return response()->json($taskMaster);
    }

    // 公式課題削除
    public function deleteOfficialTask(Request $request, \App\Models\TaskMaster $taskMaster)
    {
        $this->checkOperator($request);
        $taskMaster->delete();
        return response()->json(['message' => '削除しました']);
    }
}
