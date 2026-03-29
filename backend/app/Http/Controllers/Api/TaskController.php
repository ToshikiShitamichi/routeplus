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

        // 全タスクマスターを取得
        $taskMasters = TaskMaster::where('is_official', true)
            ->orderByRaw("FIELD(category, 'フロントエンド', 'サーバーサイド', 'インフラ')")
            ->orderBy('order')
            ->get();

        // このユーザーの進捗を取得（task_master_id => progress のマップ）
        $progressMap = UserTaskProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('task_master_id');

        // マージして返す
        $tasks = $taskMasters->map(function ($task) use ($progressMap) {
            $progress = $progressMap->get($task->id);

            return [
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
            'github_url' => ['required', 'url'],
            'deploy_url' => ['nullable', 'url'],
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
                'submitted_at' => now(),
            ]
        );

        return response()->json([
            'message' => '提出しました',
            'task' => [
                'id'          => $task->id,
                'category'    => $task->category,
                'order'       => $task->order,
                'level'       => $task->level,
                'title'       => $task->title,
                'description' => $task->description,
                'status'      => $progress->status,
                'github_url'  => $progress->github_url,
                'deploy_url'  => $progress->deploy_url,
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
                ];
            });

        return response()->json($submissions);
    }
}
