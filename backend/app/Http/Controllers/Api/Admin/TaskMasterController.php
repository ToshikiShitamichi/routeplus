<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskMaster;
use Illuminate\Http\Request;

class TaskMasterController extends Controller
{
    private function checkAdmin(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            abort(403, '管理者のみアクセスできます');
        }
    }

    // 独自課題一覧（自組織のもの）
    public function index(Request $request)
    {
        $this->checkAdmin($request);

        $tasks = TaskMaster::where('is_official', false)
            ->where('organization_id', $request->user()->organization_id)
            ->orderBy('category')
            ->orderBy('order')
            ->get();

        return response()->json($tasks);
    }

    // 独自課題作成
    public function store(Request $request)
    {
        $this->checkAdmin($request);

        $validated = $request->validate([
            'category'    => 'required|string|max:100',
            'level'       => 'required|integer|min:1',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'visibility'  => 'required|in:private,public',
        ]);

        $task = TaskMaster::create([
            ...$validated,
            'order'           => 0,
            'is_official'     => false,
            'organization_id' => $request->user()->organization_id,
            'created_by'      => $request->user()->id,
        ]);

        return response()->json($task, 201);
    }

    // 独自課題更新
    public function update(Request $request, TaskMaster $taskMaster)
    {
        $this->checkAdmin($request);

        // 他組織の課題は編集不可
        if ($taskMaster->organization_id !== $request->user()->organization_id) {
            abort(403, '編集権限がありません');
        }

        $validated = $request->validate([
            'category'    => 'sometimes|string|max:100',
            'order'       => 'sometimes|integer|min:1',
            'level'       => 'sometimes|integer|min:1',
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'visibility'  => 'sometimes|in:private,public',
        ]);

        $taskMaster->update($validated);

        return response()->json($taskMaster);
    }

    // 独自課題削除
    public function destroy(Request $request, TaskMaster $taskMaster)
    {
        $this->checkAdmin($request);

        if ($taskMaster->organization_id !== $request->user()->organization_id) {
            abort(403, '削除権限がありません');
        }

        if ($taskMaster->is_official) {
            abort(403, '公式課題は削除できません');
        }

        $taskMaster->delete();

        return response()->json(['message' => '削除しました']);
    }
}
