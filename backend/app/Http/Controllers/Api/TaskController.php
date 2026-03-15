<?php
// app/Http/Controllers/Api/TaskController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = [
            // フロントエンド
            ['id' => 1, 'title' => 'React基礎',   'status' => 'done',      'category' => 'フロントエンド'],
            ['id' => 2, 'title' => '状態管理',     'status' => 'done',      'category' => 'フロントエンド'],
            ['id' => 3, 'title' => 'Router',       'status' => 'in_review', 'category' => 'フロントエンド'],
            ['id' => 4, 'title' => 'API通信',      'status' => 'todo',      'category' => 'フロントエンド'],
            ['id' => 5, 'title' => 'SCSS',         'status' => 'todo',      'category' => 'フロントエンド'],

            // サーバーサイド
            ['id' => 6, 'title' => 'Laravel基礎', 'status' => 'done',      'category' => 'サーバーサイド'],
            ['id' => 7, 'title' => 'REST API',    'status' => 'done',      'category' => 'サーバーサイド'],
            ['id' => 8, 'title' => '認証',         'status' => 'in_review', 'category' => 'サーバーサイド'],
            ['id' => 9, 'title' => 'DB設計',       'status' => 'todo',      'category' => 'サーバーサイド'],

            // インフラ
            ['id' => 10, 'title' => 'Docker',     'status' => 'done',      'category' => 'インフラ'],
            ['id' => 11, 'title' => 'Sail',       'status' => 'done',      'category' => 'インフラ'],
            ['id' => 12, 'title' => 'デプロイ',   'status' => 'todo',      'category' => 'インフラ'],
        ];

        return response()->json($tasks);
    }
}
