<?php

namespace Database\Seeders;

use App\Models\Roadmap;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTaskProgress;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $user = User::factory()->create([
                'name' => 'テストユーザー',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $frontend = Roadmap::create([
            'key' => 'frontend',
            'title' => 'フロントエンドロードマップ',
            'description' => 'React / UI / 状態管理 / テスト など',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $backend = Roadmap::create([
            'key' => 'backend',
            'title' => 'バックエンドロードマップ',
            'description' => 'Laravel / API / DB / 認証 など',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $infra = Roadmap::create([
            'key' => 'infra',
            'title' => 'インフラロードマップ',
            'description' => 'Docker / Linux / Deploy / CI/CD など',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $tasks = [
            [
                'roadmap_id' => $frontend->id,
                'task_code' => 'fe-1',
                'title' => 'React基礎：コンポーネント分割',
                'description' => '小さなUIに分割して再利用できるようにする',
                'difficulty' => 1,
                'sort_order' => 1,
                'is_recommended' => true,
            ],
            [
                'roadmap_id' => $frontend->id,
                'task_code' => 'fe-2',
                'title' => '状態管理：useState/useEffect演習',
                'description' => '状態管理の基礎を理解する',
                'difficulty' => 2,
                'sort_order' => 2,
                'is_recommended' => true,
            ],
            [
                'roadmap_id' => $frontend->id,
                'task_code' => 'fe-3',
                'title' => 'ルーティング：React Router 入門',
                'description' => '画面遷移の基本を学ぶ',
                'difficulty' => 2,
                'sort_order' => 3,
                'is_recommended' => true,
            ],
            [
                'roadmap_id' => $backend->id,
                'task_code' => 'be-1',
                'title' => 'Laravel基礎：MVC理解',
                'description' => 'Controller / Model / View の役割理解',
                'difficulty' => 1,
                'sort_order' => 1,
                'is_recommended' => true,
            ],
            [
                'roadmap_id' => $backend->id,
                'task_code' => 'be-2',
                'title' => 'API作成：JSONレスポンス',
                'description' => 'APIルートとJSON返却を理解する',
                'difficulty' => 2,
                'sort_order' => 2,
                'is_recommended' => true,
            ],
            [
                'roadmap_id' => $infra->id,
                'task_code' => 'in-1',
                'title' => 'Docker基礎：コンテナ操作',
                'description' => 'Sail / Docker の基本操作に慣れる',
                'difficulty' => 1,
                'sort_order' => 1,
                'is_recommended' => false,
            ],
        ];

        foreach ($tasks as $taskData) {
            $task = Task::create(array_merge($taskData, [
                'is_active' => true,
            ]));

            $status = match ($task->task_code) {
                'fe-1' => 'done',
                'fe-2' => 'in_review',
                default => 'todo',
            };

            UserTaskProgress::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'status' => $status,
                'understanding_level' => $status === 'done' ? 4 : null,
                'submitted_at' => $status !== 'todo' ? now() : null,
                'reviewed_at' => $status === 'done' ? now() : null,
                'memo' => null,
            ]);
        }
    }
}
