<?php
// database/seeders/GroupMigrationSeeder.php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupMigrationSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 既存招待のラベルをグループに変換
        $invitations = Invitation::whereNotNull('label')
            ->where('label', '!=', '')
            ->get();

        // 管理者ユーザー取得
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            echo "管理者ユーザーが見つかりません\n";
            return;
        }

        foreach ($invitations as $invitation) {
            // ラベルごとにグループを作成（重複しない）
            $group = Group::firstOrCreate(
                [
                    'organization_id' => $invitation->organization_id,
                    'name'            => $invitation->label,
                ],
                [
                    'created_by'  => $admin->id,
                    'description' => null,
                    'is_public'   => false,
                ]
            );

            // 招待にgroup_idを紐付け
            $invitation->update(['group_id' => $group->id]);

            // この招待で登録したユーザーをグループメンバーに追加
            $users = User::where('invitation_id', $invitation->id)->get();
            foreach ($users as $user) {
                GroupMember::firstOrCreate([
                    'group_id' => $group->id,
                    'user_id'  => $user->id,
                ], [
                    'joined_at' => $user->created_at,
                ]);
            }
        }

        // デフォルトグループを作成（ラベルなしユーザー用）
        $defaultGroup = Group::firstOrCreate(
            [
                'organization_id' => $admin->organization_id,
                'name'            => 'デフォルトグループ',
            ],
            [
                'created_by'  => $admin->id,
                'description' => '招待ラベルなしで登録したユーザー',
                'is_public'   => false,
            ]
        );

        // organization_idがあってinvitation_idがないstudentをデフォルトグループに
        $ungroupedUsers = User::where('role', 'student')
            ->whereNotNull('organization_id')
            ->whereNull('invitation_id')
            ->get();

        foreach ($ungroupedUsers as $user) {
            GroupMember::firstOrCreate([
                'group_id' => $defaultGroup->id,
                'user_id'  => $user->id,
            ], [
                'joined_at' => $user->created_at,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "移行完了\n";
        echo "グループ数: " . Group::count() . "\n";
        echo "メンバー数: " . GroupMember::count() . "\n";
    }
}
