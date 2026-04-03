<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\TaskMaster;
use App\Models\User;
use App\Models\UserTaskProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function checkAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user->isAdmin()) abort(403, '管理者権限が必要です');
        return $user;
    }

    // ── 生徒一覧（グループ別）──
    public function students(Request $request)
    {
        $admin = $this->checkAdmin($request);

        // withを使わず手動でinvitationを取得
        $students = User::where('organization_id', $admin->organization_id)
            ->where('role', 'student')
            ->get()
            ->map(function ($student) {
                $progress = UserTaskProgress::where('user_id', $student->id)->get();
                $total = TaskMaster::count();

                // invitation_idからラベルを取得
                $label = '（ラベルなし）';
                if ($student->invitation_id) {
                    $invitation = \App\Models\Invitation::find($student->invitation_id);
                    if ($invitation && $invitation->label) {
                        $label = $invitation->label;
                    }
                }

                return [
                    'id'          => $student->id,
                    'name'        => $student->name,
                    'email'       => $student->email,
                    'group_label' => $label,
                    'total'       => $total,
                    'done'        => $progress->where('status', 'done')->count(),
                    'in_progress' => $progress->where('status', 'in_progress')->count(),
                    'todo'        => $total - $progress->count(),
                    'created_at'  => $student->created_at,
                ];
            });

        // グループ別にまとめる
        $grouped = $students->groupBy('group_label')
            ->map(function ($groupStudents, $label) {
                return [
                    'label'    => $label,
                    'students' => $groupStudents->values(),
                ];
            })
            ->values();

        return response()->json($grouped);
    }

    // ── 特定生徒の進捗詳細 ──
    public function studentProgress(Request $request, int $studentId)
    {
        $admin = $this->checkAdmin($request);

        $student = User::where('id', $studentId)
            ->where('organization_id', $admin->organization_id)
            ->firstOrFail();

        $taskMasters = TaskMaster::orderByRaw(
            "FIELD(category, 'フロントエンド', 'サーバーサイド', 'インフラ')"
        )->orderBy('order')->get();

        $progressMap = UserTaskProgress::where('user_id', $studentId)
            ->get()->keyBy('task_master_id');

        $tasks = $taskMasters->map(function ($task) use ($progressMap) {
            $progress = $progressMap->get($task->id);
            return [
                'id'          => $task->id,
                'category'    => $task->category,
                'order'       => $task->order,
                'level'       => $task->level,
                'title'       => $task->title,
                'status'      => $progress ? $progress->status : 'todo',
                'github_url'  => $progress ? $progress->github_url : null,
                'deploy_url'  => $progress ? $progress->deploy_url : null,
                'submitted_at' => $progress ? $progress->submitted_at : null,
            ];
        });

        return response()->json([
            'student' => [
                'id'    => $student->id,
                'name'  => $student->name,
                'email' => $student->email,
            ],
            'tasks' => $tasks,
        ]);
    }

    // ── 招待リンク発行 ──
    public function createInvitation(Request $request)
    {
        $admin = $this->checkAdmin($request);

        $request->validate([
            'label'    => ['nullable', 'string', 'max:50'],
            'max_uses' => ['nullable', 'integer', 'min:0'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ]);

        // group_idが指定されていない場合はlabelでグループを自動作成
        $groupId = $request->group_id;
        if (!$groupId && $request->label) {
            $group = \App\Models\Group::firstOrCreate(
                [
                    'organization_id' => $admin->organization_id,
                    'name'            => $request->label,
                ],
                [
                    'created_by'  => $admin->id,
                    'is_public'   => false,
                ]
            );
            $groupId = $group->id;
        }

        $invitation = \App\Models\Invitation::create([
            'organization_id' => $admin->organization_id,
            'group_id'        => $groupId,
            'invited_by'      => $admin->id,
            'token'           => \Illuminate\Support\Str::random(32),
            'label'           => $request->label ?: '名称未設定',
            'max_uses'        => $request->max_uses ?? 0,
            'expires_at'      => now()->addDays(7),
        ]);

        return response()->json([
            'token'      => $invitation->token,
            'invite_url' => 'http://localhost:5173/register?token=' . $invitation->token,
            'label'      => $invitation->label,
            'expires_at' => $invitation->expires_at,
            'max_uses'   => $invitation->max_uses,
        ]);
    }

    // ── 招待リスト取得 ──
    public function invitations(Request $request)
    {
        $admin = $this->checkAdmin($request);

        $invitations = Invitation::where('organization_id', $admin->organization_id)
            ->with('invitedBy:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($inv) {
                return [
                    'id'          => $inv->id,
                    'token'       => $inv->token,
                    'invite_url'  => 'http://localhost:5173/register?token=' . $inv->token,
                    'label'       => $inv->label,
                    'is_valid'    => $inv->isValid(),
                    'max_uses'    => $inv->max_uses,
                    'used_count'  => $inv->used_count,
                    'expires_at'  => $inv->expires_at,
                    'invited_by'  => $inv->invitedBy->name ?? '',
                ];
            });

        return response()->json($invitations);
    }

    // ── 招待トークンの検証 ──
    public function verifyInvitation(Request $request)
    {
        $token = $request->query('token');

        $invitation = Invitation::where('token', $token)
            ->with('organization:id,name')
            ->first();

        if (!$invitation || !$invitation->isValid()) {
            return response()->json(['message' => '招待リンクが無効または期限切れです'], 422);
        }

        return response()->json([
            'organization' => $invitation->organization?->name ?? 'ROUTEPLUS',
            'label'        => $invitation->label,
        ]);
    }
    
    // ── 組織のグループ一覧 ──
    public function groups(Request $request)
    {
        $admin = $this->checkAdmin($request);

        $groups = \App\Models\Group::where('organization_id', $admin->organization_id)
            ->withCount('members')
            ->get()
            ->map(fn($g) => [
                'id'           => $g->id,
                'name'         => $g->name,
                'description'  => $g->description,
                'is_public'    => $g->is_public,
                'members_count' => $g->members_count,
            ]);

        return response()->json($groups);
    }

    // ── グループ作成 ──
    public function createGroup(Request $request)
    {
        $admin = $this->checkAdmin($request);

        $request->validate([
            'name'        => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:200'],
            'is_public'   => ['boolean'],
        ]);

        $group = \App\Models\Group::create([
            'organization_id' => $admin->organization_id,
            'created_by'      => $admin->id,
            'name'            => $request->name,
            'description'     => $request->description,
            'is_public'       => $request->is_public ?? false,
        ]);

        return response()->json($group);
    }

    public function joinWithInvitation(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $invitation = \App\Models\Invitation::where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$invitation) {
            return response()->json(['message' => '招待リンクが無効または期限切れです'], 422);
        }

        $user = $request->user();

        // すでにグループに参加しているか確認
        if ($invitation->group_id) {
            $alreadyJoined = \App\Models\GroupMember::where('group_id', $invitation->group_id)
                ->where('user_id', $user->id)
                ->exists();

            if ($alreadyJoined) {
                return response()->json(['message' => 'すでにこのグループに参加しています'], 422);
            }

            \App\Models\GroupMember::create([
                'group_id'  => $invitation->group_id,
                'user_id'   => $user->id,
                'joined_at' => now(),
            ]);
        }

        // 使用回数を更新
        $invitation->increment('used_count');

        return response()->json(['message' => 'グループに参加しました']);
    }
}
