<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\TaskMaster;
use App\Models\TaskPack;
use App\Models\TaskPackItem;
use Illuminate\Http\Request;

class TaskPackController extends Controller
{
    private function checkAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user->isAdmin()) abort(403, '管理者権限が必要です');
        return $user;
    }

    // ── パック一覧（自組織 + 公式 + 公開）──
    public function index(Request $request)
    {
        $admin = $this->checkAdmin($request);

        $packs = TaskPack::where(function ($q) use ($admin) {
            $q->where('organization_id', $admin->organization_id)
                ->orWhere('is_official', true)
                ->orWhere('is_public', true);
        })
            ->withCount('items')
            ->get()
            ->map(fn($pack) => [
                'id'           => $pack->id,
                'name'         => $pack->name,
                'description'  => $pack->description,
                'is_official'  => $pack->is_official,
                'is_public'    => $pack->is_public,
                'is_mine'      => $pack->organization_id === $admin->organization_id,
                'items_count'  => $pack->items_count,
                'created_by'   => $pack->creator?->name,
            ]);

        return response()->json($packs);
    }

    // ── パック詳細（課題一覧付き）──
    public function show(Request $request, int $id)
    {
        $this->checkAdmin($request);

        $pack = TaskPack::with([
            'taskMasters' => fn($q) => $q->orderBy('task_pack_items.order')
        ])->findOrFail($id);

        return response()->json([
            'id'          => $pack->id,
            'name'        => $pack->name,
            'description' => $pack->description,
            'is_official' => $pack->is_official,
            'is_public'   => $pack->is_public,
            'tasks'       => $pack->taskMasters->map(fn($task) => [
                'id'       => $task->id,
                'category' => $task->category,
                'order'    => $task->order,
                'level'    => $task->level,
                'title'    => $task->title,
                'pack_order' => $task->pivot->order,
            ]),
        ]);
    }

    // ── パック作成 ──
    public function store(Request $request)
    {
        $admin = $this->checkAdmin($request);

        $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:500'],
            'is_public'    => ['boolean'],
            'task_ids'     => ['required', 'array', 'min:1'],
            'task_ids.*'   => ['exists:task_masters,id'],
        ]);

        $pack = TaskPack::create([
            'organization_id' => $admin->organization_id,
            'created_by'      => $admin->id,
            'name'            => $request->name,
            'description'     => $request->description,
            'is_official'     => false,
            'is_public'       => $request->is_public ?? false,
        ]);

        // 課題を順番に紐付け
        foreach ($request->task_ids as $index => $taskId) {
            TaskPackItem::create([
                'task_pack_id'   => $pack->id,
                'task_master_id' => $taskId,
                'order'          => $index + 1,
            ]);
        }

        return response()->json($pack->load('items'), 201);
    }

    // ── パック更新 ──
    public function update(Request $request, int $id)
    {
        $admin = $this->checkAdmin($request);

        $pack = TaskPack::where('id', $id)
            ->where('organization_id', $admin->organization_id)
            ->firstOrFail();

        $request->validate([
            'name'        => ['sometimes', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_public'   => ['boolean'],
            'task_ids'    => ['sometimes', 'array', 'min:1'],
            'task_ids.*'  => ['exists:task_masters,id'],
        ]);

        $pack->update($request->only(['name', 'description', 'is_public']));

        // 課題を再設定
        if ($request->has('task_ids')) {
            TaskPackItem::where('task_pack_id', $pack->id)->delete();
            foreach ($request->task_ids as $index => $taskId) {
                TaskPackItem::create([
                    'task_pack_id'   => $pack->id,
                    'task_master_id' => $taskId,
                    'order'          => $index + 1,
                ]);
            }
        }

        return response()->json($pack->load('items'));
    }

    // ── パック削除 ──
    public function destroy(Request $request, int $id)
    {
        $admin = $this->checkAdmin($request);

        $pack = TaskPack::where('id', $id)
            ->where('organization_id', $admin->organization_id)
            ->firstOrFail();

        $pack->delete();

        return response()->json(['message' => '削除しました']);
    }

    // ── グループにパックを割り当て ──
    public function assignToGroup(Request $request, int $packId)
    {
        $admin = $this->checkAdmin($request);

        $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
        ]);

        $pack = TaskPack::findOrFail($packId);
        $group = Group::where('id', $request->group_id)
            ->where('organization_id', $admin->organization_id)
            ->firstOrFail();

        $group->taskPacks()->syncWithoutDetaching([$pack->id]);

        return response()->json(['message' => 'グループにパックを割り当てました']);
    }

    // ── グループからパックを外す ──
    public function removeFromGroup(Request $request, int $packId)
    {
        $admin = $this->checkAdmin($request);

        $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
        ]);

        $group = Group::where('id', $request->group_id)
            ->where('organization_id', $admin->organization_id)
            ->firstOrFail();

        $group->taskPacks()->detach($packId);

        return response()->json(['message' => 'パックを外しました']);
    }

    // ── 公開パック一覧（ユーザー向け）──
    public function publicPacks()
    {
        $packs = TaskPack::where(function ($q) {
            $q->where('is_official', true)
                ->orWhere('is_public', true);
        })
            ->withCount('items')
            ->get()
            ->map(fn($pack) => [
                'id'          => $pack->id,
                'name'        => $pack->name,
                'description' => $pack->description,
                'is_official' => $pack->is_official,
                'items_count' => $pack->items_count,
            ]);

        return response()->json($packs);
    }
}
