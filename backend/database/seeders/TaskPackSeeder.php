<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TaskPack;
use App\Models\TaskPackItem;
use App\Models\TaskMaster;

class TaskPackSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TaskPackItem::truncate();
        TaskPack::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $packs = [
            // ── 大分類パック（デフォルト・軽量版）──
            [
                'name' => 'フロントエンド入門',
                'description' => 'Web基礎からJavaScriptまでの入門コース（Lv.1〜2）',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Webの仕組みを理解する', 'HTMLで自己紹介ページを作る', 'CSSでページをデザインする', 'Flexboxでナビゲーションバーを作る', 'CSS Gridでポートフォリオギャラリーを作る', 'JavaScriptで電卓を作る', '配列とループでクイズアプリを作る', 'LocalStorageでTodoアプリを作る', 'Fetch APIで天気アプリを作る', '非同期処理とPromiseを理解する', 'モジュール分割でコードを整理する'],
            ],
            [
                'name' => 'サーバーサイド入門',
                'description' => 'PHP基礎からLaravel認証までの入門コース（Lv.1〜2）',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['コマンドラインの基本操作をマスターする', 'PHPの基礎文法を習得する', 'PHPでオブジェクト指向を理解する', 'MySQLの基本操作をマスターする', 'PHPとMySQLを連携させる', 'LaravelとDockerで開発環境を構築する', 'MVC構造とルーティングを理解する', 'MigrationとEloquentでDB操作をする', 'リレーションとeager loadingを使う', 'バリデーションとエラーレスポンスを実装する', 'Sanctumで認証APIを実装する'],
            ],
            [
                'name' => 'インフラ入門',
                'description' => 'LinuxからGitHub Actionsまでの入門コース（Lv.1〜2）',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Linuxコマンドをマスターする', 'Dockerの基礎を理解する', 'Docker Composeで複数コンテナを管理する', 'Nginxを設定する', 'Git/GitHubでチーム開発の流れを理解する', 'VPSにLaravelを手動デプロイする', 'GitHub ActionsでCIパイプラインを作る', 'GitHub ActionsでCDパイプラインを作る'],
            ],

            // ── 細分化パック ──
            [
                'name' => 'HTML/CSS',
                'description' => 'HTMLとCSSの基礎からFlexbox・Gridまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Webの仕組みを理解する', 'HTMLで自己紹介ページを作る', 'CSSでページをデザインする', 'Flexboxでナビゲーションバーを作る', 'CSS Gridでポートフォリオギャラリーを作る'],
            ],
            [
                'name' => 'JavaScript基礎',
                'description' => 'JavaScriptの基礎から非同期処理まで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['JavaScriptで電卓を作る', '配列とループでクイズアプリを作る', 'LocalStorageでTodoアプリを作る', 'Fetch APIで天気アプリを作る', '非同期処理とPromiseを理解する', 'モジュール分割でコードを整理する'],
            ],
            [
                'name' => 'React基礎',
                'description' => 'Reactの基礎からAPI接続まで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Reactでカウンターアプリを作る', 'useEffectでデータ取得アプリを作る', 'React Routerでマルチページアプリを作る', 'フォームバリデーションを実装する', 'Context APIでグローバル状態管理をする', 'SCSSでデザインシステムを作る', 'axiosでLaravel APIに接続する'],
            ],
            [
                'name' => 'React応用',
                'description' => 'カスタムフックからTypeScriptまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['カスタムフックでロジックを分離する', 'パフォーマンス最適化をする', 'ReactでドラッグandドロップUIを作る', 'アニメーションを実装する', 'TypeScriptに移行する', 'テストコードを書く'],
            ],
            [
                'name' => 'PHP基礎',
                'description' => 'PHPの文法からMySQL連携まで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['PHPの基礎文法を習得する', 'PHPでオブジェクト指向を理解する', 'MySQLの基本操作をマスターする', 'PHPとMySQLを連携させる'],
            ],
            [
                'name' => 'Laravel基礎',
                'description' => '環境構築からSanctum認証まで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['LaravelとDockerで開発環境を構築する', 'MVC構造とルーティングを理解する', 'MigrationとEloquentでDB操作をする', 'リレーションとeager loadingを使う', 'バリデーションとエラーレスポンスを実装する', 'Sanctumで認証APIを実装する', 'APIリソースでレスポンスを整形する'],
            ],
            [
                'name' => 'Laravel応用',
                'description' => 'ファイルアップロードからテストまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['ファイルアップロードを実装する', '検索・フィルタ・ソートAPIを作る', 'Policyで権限管理を実装する', 'キューとジョブで非同期処理をする', 'イベントとリスナーで疎結合設計をする', 'キャッシュでAPIを高速化する', 'DBトランザクションで整合性を保つ', 'APIドキュメントを作成する'],
            ],
            [
                'name' => 'GitHub基礎',
                'description' => 'バージョン管理とチーム開発の基礎',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Git/GitHubでバージョン管理をする', 'Git/GitHubでチーム開発の流れを理解する'],
            ],
            [
                'name' => 'チーム開発',
                'description' => 'コードレビューとCI/CDを含むチーム開発実践',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Git/GitHubでチーム開発の流れを理解する', 'GitHub ActionsでCIパイプラインを作る', 'GitHub ActionsでCDパイプラインを作る', 'コードレビューを受ける'],
            ],
            [
                'name' => 'Linux基礎',
                'description' => 'Linuxコマンドの基礎',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Linuxコマンドをマスターする'],
            ],
            [
                'name' => 'Docker基礎',
                'description' => 'DockerとDocker Composeの基礎',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Dockerの基礎を理解する', 'Docker Composeで複数コンテナを管理する', 'Dockerイメージを最適化する'],
            ],
            [
                'name' => 'CI/CD',
                'description' => 'GitHub ActionsでCI/CDパイプラインを構築する',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['GitHub ActionsでCIパイプラインを作る', 'GitHub ActionsでCDパイプラインを作る', '本番グレードのCI/CDパイプラインを構築する'],
            ],
            [
                'name' => 'AWS基礎',
                'description' => 'AWSの基礎サービスからデプロイまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['AWSの基礎サービスを理解する', 'EC2にLaravelをデプロイする', 'RDSでMySQLを本番運用する', 'S3で静的ファイルを管理する', 'HTTPS化とSSL証明書を設定する', 'CloudWatchで監視・アラートを設定する'],
            ],
            [
                'name' => 'AWS応用',
                'description' => 'TerraformからECS・Kubernetesまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Terraformでインフラをコード化する', 'Ansibleでサーバー設定を自動化する', 'ECSでコンテナをAWSで動かす', 'Kubernetesの基礎を理解する', 'マルチAZ構成で高可用性を実現する'],
            ],
            [
                'name' => 'SQL/DB設計',
                'description' => 'SQL基礎からDB設計パターンまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['SQLの基本操作をマスターする', 'テーブル設計の基礎を理解する（正規化）', 'リレーションとJOINを使う', 'インデックスでクエリを高速化する', 'トランザクションとロックを理解する', 'ストアドプロシージャを作る', 'DB設計パターンを学ぶ', 'パフォーマンスチューニングをする'],
            ],
            [
                'name' => 'UI/UXデザイン基礎',
                'description' => 'FigmaからデザインカンプのコーディングまでUI/UX入門',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['UIとUXの違いを理解する', 'Figmaの基本操作をマスターする', 'コンポーネント設計でデザインを効率化する', 'カラー・タイポグラフィの基礎を学ぶ', 'ワイヤーフレームを作る', 'プロトタイプを作る', 'デザインカンプをコーディングする', 'アクセシビリティを考慮したデザインをする'],
            ],
            [
                'name' => 'TypeScript',
                'description' => '型入門からReact×TypeScriptまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['TypeScriptの基礎を理解する（型・インターフェース）', '関数と型注釈を使う', 'ジェネリクスを理解する', 'Reactコンポーネントを型付けする', 'APIレスポンスを型定義する', 'ユーティリティ型を使う', '既存JSプロジェクトをTSに移行する'],
            ],
            [
                'name' => 'Python基礎',
                'description' => 'Python環境構築からデータ処理まで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Python環境構築をする', 'Pythonの基礎文法を習得する', 'リスト・辞書・タプルを使う', 'クラスとオブジェクト指向を理解する', 'ファイル操作をする', '外部ライブラリを使う（requests等）', 'データ処理をする（pandas入門）'],
            ],
            [
                'name' => 'Django',
                'description' => 'Django入門からREST APIまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Djangoで開発環境を構築する', 'MVTパターンを理解する', 'モデルとマイグレーションを使う', 'ビューとテンプレートを作る', 'Formバリデーションを実装する', 'Django REST Frameworkを使う', '認証機能を実装する'],
            ],
            [
                'name' => 'Java基礎',
                'description' => 'Java環境構築からJUnitまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Java環境構築をする', 'Javaの基礎文法を習得する', 'オブジェクト指向を理解する（クラス・継承）', '例外処理を実装する', 'コレクションを使う', 'ファイルI/Oを実装する', 'ユニットテストを書く（JUnit）'],
            ],
            [
                'name' => 'Spring Boot',
                'description' => 'Spring Boot入門からセキュリティまで',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['Spring Bootで開発環境を構築する', 'REST APIを作る', 'Spring Data JPAでDB操作をする', 'バリデーションを実装する', 'Spring Securityで認証を実装する', 'テストを書く'],
            ],
            [
                'name' => '基本情報技術者試験対策',
                'description' => '基本情報技術者試験の出題範囲を体系的に学ぶ',
                'is_official' => true,
                'is_public' => true,
                'tasks' => ['コンピュータの基礎を理解する（2進数・論理回路）', 'データ構造とアルゴリズムを学ぶ', 'OSの基礎を理解する', 'ネットワークの基礎を理解する', 'セキュリティの基礎を理解する', 'データベースの基礎を理解する', 'ソフトウェア開発手法を学ぶ', '過去問演習をする'],
            ],
        ];

        foreach ($packs as $packData) {
            $pack = TaskPack::create([
                'organization_id' => null,
                'created_by'      => null,
                'name'            => $packData['name'],
                'description'     => $packData['description'],
                'is_official'     => $packData['is_official'],
                'is_public'       => $packData['is_public'],
            ]);

            foreach ($packData['tasks'] as $order => $title) {
                $task = TaskMaster::where('title', $title)->first();
                if ($task) {
                    TaskPackItem::create([
                        'task_pack_id'   => $pack->id,
                        'task_master_id' => $task->id,
                        'order'          => $order + 1,
                    ]);
                }
            }
        }
    }
}
