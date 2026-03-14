<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roadmap;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTaskProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // 仮で最初のユーザーを取得
        // 後で認証導入後は auth()->id() に置き換える
        $user = User::first();

        if (! $user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        $progressSummary = [
            'total' => Task::where('is_active', true)->count(),
            'done' => UserTaskProgress::where('user_id', $user->id)->where('status', 'done')->count(),
            'inReview' => UserTaskProgress::where('user_id', $user->id)->where('status', 'in_review')->count(),
            'todo' => UserTaskProgress::where('user_id', $user->id)->where('status', 'todo')->count(),
        ];

        $roadmaps = Roadmap::query()
            ->where('is_active', true)
            ->with([
                'tasks' => function ($query) use ($user) {
                    $query->where('is_active', true)
                        ->with(['progresses' => function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        }]);
                }
            ])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($roadmap) {
                return [
                    'key' => $roadmap->key,
                    'title' => $roadmap->title,
                    'description' => $roadmap->description,
                    'tasks' => $roadmap->tasks->map(function ($task) {
                        $progress = $task->progresses->first();

                        return [
                            'id' => $task->id,
                            'task_code' => $task->task_code,
                            'title' => $task->title,
                            'status' => $progress?->status ?? 'todo',
                        ];
                    })->values(),
                ];
            })
            ->values();

        $recommendedTasks = Task::query()
            ->where('is_active', true)
            ->where('is_recommended', true)
            ->with([
                'roadmap',
                'progresses' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->get()
            ->filter(function ($task) {
                $progress = $task->progresses->first();
                return ! $progress || $progress->status !== 'done';
            })
            ->take(3)
            ->map(function ($task) {
                $progress = $task->progresses->first();

                return [
                    'id' => $task->id,
                    'task_code' => $task->task_code,
                    'title' => $task->title,
                    'status' => $progress?->status ?? 'todo',
                    'roadmap' => [
                        'key' => $task->roadmap->key,
                        'title' => $task->roadmap->title,
                    ],
                ];
            })
            ->values();

        return response()->json([
            'progress' => $progressSummary,
            'roadmaps' => $roadmaps,
            'recommendedTasks' => $recommendedTasks,
        ]);
    }
}
