<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // テスト用組織を作成
        $org = Organization::firstOrCreate(
            ['slug' => 'gs-school'],
            ['name' => 'GSスクール']
        );

        // 管理者ユーザーを作成
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'            => '管理者',
                'password'        => bcrypt('password'),
                'role'            => 'admin',
                'organization_id' => $org->id,
            ]
        );

        // 既存のTest Userを組織に所属させる
        User::where('email', 'test@example.com')->update([
            'role'            => 'student',
            'organization_id' => $org->id,
        ]);
    }
}
