<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    // 自分が所属しているグループ一覧
    public function myGroups(Request $request)
    {
        $user = $request->user();

        $groups = $user->groups()
            ->with('organization:id,name')
            ->get()
            ->map(fn($group) => [
                'id'          => $group->id,
                'name'        => $group->name,
                'description' => $group->description,
                'is_public'   => $group->is_public,
                'joined_at'   => $group->pivot->joined_at,
            ]);

        return response()->json($groups);
    }

    // グループに参加（公開グループのみ）
    public function join(Request $request, int $id)
    {
        $user = $request->user();
        $group = Group::findOrFail($id);

        if (!$group->is_public) {
            return response()->json(['message' => '招待が必要なグループです'], 403);
        }

        GroupMember::firstOrCreate([
            'group_id' => $group->id,
            'user_id'  => $user->id,
        ], [
            'joined_at' => now(),
        ]);

        return response()->json(['message' => 'グループに参加しました']);
    }

    // 公開グループ一覧
    public function publicGroups()
    {
        $groups = Group::where('is_public', true)
            ->withCount('members')
            ->get()
            ->map(fn($group) => [
                'id'           => $group->id,
                'name'         => $group->name,
                'description'  => $group->description,
                'members_count' => $group->members_count,
            ]);

        return response()->json($groups);
    }
}
