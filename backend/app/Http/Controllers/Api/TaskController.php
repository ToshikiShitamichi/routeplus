<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskMaster;
use App\Models\UserTaskProgress;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // ── タスク一覧（ユーザーの進捗とマージして返す）──
    public function index(Request $request)
    {
        $user = $request->user();
        $groupId = $request->query('group_id');

        if ($groupId) {
            $taskMasterIds = \App\Models\GroupTaskPack::where('group_id', $groupId)
                ->with('taskPack.items')
                ->get()
                ->flatMap(fn($gtp) => $gtp->taskPack ? $gtp->taskPack->items->sortBy('order')->pluck('task_master_id') : [])
                ->unique()
                ->values();

            $taskMasters = TaskMaster::whereIn('id', $taskMasterIds)
                ->get()
                ->sortBy(fn($task) => $taskMasterIds->search($task->id))
                ->values();
        } else {
            // すべてタブ：ユーザーの全グループのパックをまとめて表示
            $groupIds = \App\Models\GroupMember::where('user_id', $user->id)
                ->pluck('group_id');

            $taskMasterIds = \App\Models\GroupTaskPack::whereIn('group_id', $groupIds)
                ->with('taskPack.items')
                ->get()
                ->flatMap(fn($gtp) => $gtp->taskPack ? $gtp->taskPack->items->sortBy('order')->pluck('task_master_id') : [])
                ->unique()
                ->values();

            if ($taskMasterIds->isEmpty()) {
                // パック未割り当て：フロントエンド入門 or HTML/CSS をデフォルト表示
                $defaultPack = \App\Models\TaskPack::where('name', 'フロントエンド入門')
                    ->whereNull('organization_id')
                    ->first()
                    ?? \App\Models\TaskPack::where('name', 'HTML/CSS')
                    ->whereNull('organization_id')
                    ->first();

                if ($defaultPack) {
                    $taskMasterIds = $defaultPack->items()
                        ->orderBy('order')
                        ->pluck('task_master_id');
                }
            }

            $taskMasters = TaskMaster::whereIn('id', $taskMasterIds)
                ->get()
                ->sortBy(fn($task) => $taskMasterIds->search($task->id))
                ->values();
        }

        $progressMap = \App\Models\UserTaskProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('task_master_id');

        $tasks = $taskMasters->map(function ($task) use ($progressMap) {
            $progress = $progressMap->get($task->id);
            return [
                'id'           => $task->id,
                'category'     => $task->category,
                'order'        => $task->order,
                'level'        => $task->level,
                'title'        => $task->title,
                'description'  => $task->description,
                'status'       => $progress ? $progress->status : 'todo',
                'github_url'   => $progress ? $progress->github_url : null,
                'deploy_url'   => $progress ? $progress->deploy_url : null,
                'submitted_at' => $progress ? $progress->submitted_at : null,
            ];
        });

        return response()->json($tasks);
    }

    // ── タスク詳細 ──
    public function show(Request $request, int $id)
    {
        $user = $request->user();
        $task = TaskMaster::find($id);

        if (!$task) {
            return response()->json(['message' => '課題が見つかりません'], 404);
        }

        $progress = UserTaskProgress::where('user_id', $user->id)
            ->where('task_master_id', $id)
            ->first();

        return response()->json([
            'id'          => $task->id,
            'category'    => $task->category,
            'order'       => $task->order,
            'level'       => $task->level,
            'title'       => $task->title,
            'description' => $task->description,
            'status'      => $progress ? $progress->status : 'todo',
            'github_url'  => $progress ? $progress->github_url : null,
            'deploy_url'  => $progress ? $progress->deploy_url : null,
            'submitted_at' => $progress ? $progress->submitted_at : null,
        ]);
    }

    // ── ステータス更新 ──
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', 'in:todo,in_progress,done'],
        ]);

        $user = $request->user();
        $task = TaskMaster::find($id);

        if (!$task) {
            return response()->json(['message' => '課題が見つかりません'], 404);
        }

        // 進捗レコードがなければ作成、あれば更新
        $progress = UserTaskProgress::updateOrCreate(
            [
                'user_id'        => $user->id,
                'task_master_id' => $id,
            ],
            [
                'status' => $request->status,
            ]
        );

        return response()->json(array_merge(
            $task->toArray(),
            ['status' => $progress->status]
        ));
    }

    // ── 課題提出 ──
    public function submit(Request $request, int $id)
    {
        $request->validate([
            'github_url'   => ['required', 'url'],
            'deploy_url'   => ['nullable', 'url'],
            'product_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $task = TaskMaster::find($id);

        if (!$task) {
            return response()->json(['message' => '課題が見つかりません'], 404);
        }

        $progress = UserTaskProgress::updateOrCreate(
            [
                'user_id'        => $user->id,
                'task_master_id' => $id,
            ],
            [
                'status'       => 'done',
                'github_url'   => $request->github_url,
                'deploy_url'   => $request->deploy_url,
                'product_name' => $request->product_name,
                'submitted_at' => now(),
            ]
        );

        return response()->json([
            'message' => '提出しました',
            'task' => [
                'id'           => $task->id,
                'category'     => $task->category,
                'order'        => $task->order,
                'level'        => $task->level,
                'title'        => $task->title,
                'description'  => $task->description,
                'status'       => $progress->status,
                'github_url'   => $progress->github_url,
                'deploy_url'   => $progress->deploy_url,
                'product_name' => $progress->product_name,
                'submitted_at' => $progress->submitted_at,
            ],
        ]);
    }

    // 提出済み課題一覧（done のみ）
    public function submissions(Request $request)
    {
        $user = $request->user();

        $submissions = UserTaskProgress::where('user_id', $user->id)
            ->where('status', 'done')
            ->with('taskMaster')
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->map(function ($progress) {
                return [
                    'id'           => $progress->taskMaster->id,
                    'category'     => $progress->taskMaster->category,
                    'order'        => $progress->taskMaster->order,
                    'level'        => $progress->taskMaster->level,
                    'title'        => $progress->taskMaster->title,
                    'description'  => $progress->taskMaster->description,
                    'status'       => $progress->status,
                    'github_url'   => $progress->github_url,
                    'deploy_url'   => $progress->deploy_url,
                    'submitted_at' => $progress->submitted_at,
                    'product_name' => $progress->product_name,
                ];
            });

        return response()->json($submissions);
    }

    public function portfolio(int $userId)
    {
        $submissions = UserTaskProgress::where('user_id', $userId)
            ->where('status', 'done')
            ->whereNotNull('deploy_url')
            ->with('taskMaster')
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->map(function ($progress) {
                return [
                    'id'           => $progress->taskMaster->id,
                    'category'     => $progress->taskMaster->category,
                    'level'        => $progress->taskMaster->level,
                    'title'        => $progress->taskMaster->title,
                    'product_name' => $progress->product_name ?? $progress->taskMaster->title,
                    'github_url'   => $progress->github_url,
                    'deploy_url'   => $progress->deploy_url,
                    'submitted_at' => $progress->submitted_at,
                ];
            });

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'ユーザーが見つかりません'], 404);
        }

        return response()->json([
            'user' => ['name' => $user->name],
            'submissions' => $submissions,
        ]);
    }

    public function allTasksForAdmin(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin') abort(403);

        $taskMasters = TaskMaster::where('is_official', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get();

        return response()->json($taskMasters);
    }
}
