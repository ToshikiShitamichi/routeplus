<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskMaster;

class TaskMasterSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TaskMaster::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tasks = [
            // ── フロントエンド ──
            ['category' => 'フロントエンド', 'order' => 1,  'level' => 1, 'title' => 'Webの仕組みを理解する'],
            ['category' => 'フロントエンド', 'order' => 2,  'level' => 1, 'title' => 'HTMLで自己紹介ページを作る'],
            ['category' => 'フロントエンド', 'order' => 3,  'level' => 1, 'title' => 'CSSでページをデザインする'],
            ['category' => 'フロントエンド', 'order' => 4,  'level' => 1, 'title' => 'Flexboxでナビゲーションバーを作る'],
            ['category' => 'フロントエンド', 'order' => 5,  'level' => 1, 'title' => 'CSS Gridでポートフォリオギャラリーを作る'],
            ['category' => 'フロントエンド', 'order' => 6,  'level' => 2, 'title' => 'JavaScriptで電卓を作る'],
            ['category' => 'フロントエンド', 'order' => 7,  'level' => 2, 'title' => '配列とループでクイズアプリを作る'],
            ['category' => 'フロントエンド', 'order' => 8,  'level' => 2, 'title' => 'LocalStorageでTodoアプリを作る'],
            ['category' => 'フロントエンド', 'order' => 9,  'level' => 2, 'title' => 'Fetch APIで天気アプリを作る'],
            ['category' => 'フロントエンド', 'order' => 10, 'level' => 2, 'title' => '非同期処理とPromiseを理解する'],
            ['category' => 'フロントエンド', 'order' => 11, 'level' => 2, 'title' => 'モジュール分割でコードを整理する'],
            ['category' => 'フロントエンド', 'order' => 12, 'level' => 2, 'title' => 'Git/GitHubでバージョン管理をする'],
            ['category' => 'フロントエンド', 'order' => 13, 'level' => 3, 'title' => 'Reactでカウンターアプリを作る'],
            ['category' => 'フロントエンド', 'order' => 14, 'level' => 3, 'title' => 'useEffectでデータ取得アプリを作る'],
            ['category' => 'フロントエンド', 'order' => 15, 'level' => 3, 'title' => 'React Routerでマルチページアプリを作る'],
            ['category' => 'フロントエンド', 'order' => 16, 'level' => 3, 'title' => 'フォームバリデーションを実装する'],
            ['category' => 'フロントエンド', 'order' => 17, 'level' => 3, 'title' => 'Context APIでグローバル状態管理をする'],
            ['category' => 'フロントエンド', 'order' => 18, 'level' => 3, 'title' => 'SCSSでデザインシステムを作る'],
            ['category' => 'フロントエンド', 'order' => 19, 'level' => 3, 'title' => 'axiosでLaravel APIに接続する'],
            ['category' => 'フロントエンド', 'order' => 20, 'level' => 4, 'title' => 'カスタムフックでロジックを分離する'],
            ['category' => 'フロントエンド', 'order' => 21, 'level' => 4, 'title' => 'パフォーマンス最適化をする'],
            ['category' => 'フロントエンド', 'order' => 22, 'level' => 4, 'title' => 'ReactでドラッグandドロップUIを作る'],
            ['category' => 'フロントエンド', 'order' => 23, 'level' => 4, 'title' => 'アニメーションを実装する'],
            ['category' => 'フロントエンド', 'order' => 24, 'level' => 4, 'title' => 'TypeScriptに移行する'],
            ['category' => 'フロントエンド', 'order' => 25, 'level' => 4, 'title' => 'テストコードを書く'],
            ['category' => 'フロントエンド', 'order' => 26, 'level' => 5, 'title' => 'デザインカンプをコーディングする'],
            ['category' => 'フロントエンド', 'order' => 27, 'level' => 5, 'title' => 'PWAを実装する'],
            ['category' => 'フロントエンド', 'order' => 28, 'level' => 5, 'title' => 'SEO・アクセシビリティ対応をする'],
            ['category' => 'フロントエンド', 'order' => 29, 'level' => 5, 'title' => 'コードレビューを受ける'],
            ['category' => 'フロントエンド', 'order' => 30, 'level' => 5, 'title' => '卒業制作：ポートフォリオサイトを公開する'],

            // ── サーバーサイド ──
            ['category' => 'サーバーサイド', 'order' => 1,  'level' => 1, 'title' => 'コマンドラインの基本操作をマスターする'],
            ['category' => 'サーバーサイド', 'order' => 2,  'level' => 1, 'title' => 'PHPの基礎文法を習得する'],
            ['category' => 'サーバーサイド', 'order' => 3,  'level' => 1, 'title' => 'PHPでオブジェクト指向を理解する'],
            ['category' => 'サーバーサイド', 'order' => 4,  'level' => 1, 'title' => 'MySQLの基本操作をマスターする'],
            ['category' => 'サーバーサイド', 'order' => 5,  'level' => 1, 'title' => 'PHPとMySQLを連携させる'],
            ['category' => 'サーバーサイド', 'order' => 6,  'level' => 2, 'title' => 'LaravelとDockerで開発環境を構築する'],
            ['category' => 'サーバーサイド', 'order' => 7,  'level' => 2, 'title' => 'MVC構造とルーティングを理解する'],
            ['category' => 'サーバーサイド', 'order' => 8,  'level' => 2, 'title' => 'MigrationとEloquentでDB操作をする'],
            ['category' => 'サーバーサイド', 'order' => 9,  'level' => 2, 'title' => 'リレーションとeager loadingを使う'],
            ['category' => 'サーバーサイド', 'order' => 10, 'level' => 2, 'title' => 'バリデーションとエラーレスポンスを実装する'],
            ['category' => 'サーバーサイド', 'order' => 11, 'level' => 2, 'title' => 'Sanctumで認証APIを実装する'],
            ['category' => 'サーバーサイド', 'order' => 12, 'level' => 2, 'title' => 'APIリソースでレスポンスを整形する'],
            ['category' => 'サーバーサイド', 'order' => 13, 'level' => 3, 'title' => 'ファイルアップロードを実装する'],
            ['category' => 'サーバーサイド', 'order' => 14, 'level' => 3, 'title' => '検索・フィルタ・ソートAPIを作る'],
            ['category' => 'サーバーサイド', 'order' => 15, 'level' => 3, 'title' => 'Policyで権限管理を実装する'],
            ['category' => 'サーバーサイド', 'order' => 16, 'level' => 3, 'title' => 'キューとジョブで非同期処理をする'],
            ['category' => 'サーバーサイド', 'order' => 17, 'level' => 3, 'title' => 'イベントとリスナーで疎結合設計をする'],
            ['category' => 'サーバーサイド', 'order' => 18, 'level' => 3, 'title' => 'キャッシュでAPIを高速化する'],
            ['category' => 'サーバーサイド', 'order' => 19, 'level' => 3, 'title' => 'DBトランザクションで整合性を保つ'],
            ['category' => 'サーバーサイド', 'order' => 20, 'level' => 3, 'title' => 'APIドキュメントを作成する'],
            ['category' => 'サーバーサイド', 'order' => 21, 'level' => 4, 'title' => 'PHPUnitでFeatureテストを書く'],
            ['category' => 'サーバーサイド', 'order' => 22, 'level' => 4, 'title' => 'リファクタリングしてコードを改善する'],
            ['category' => 'サーバーサイド', 'order' => 23, 'level' => 4, 'title' => 'セキュリティ対策を実装する'],
            ['category' => 'サーバーサイド', 'order' => 24, 'level' => 4, 'title' => 'パフォーマンスチューニングをする'],
            ['category' => 'サーバーサイド', 'order' => 25, 'level' => 4, 'title' => 'WebSocketでリアルタイム通信を実装する'],
            ['category' => 'サーバーサイド', 'order' => 26, 'level' => 5, 'title' => '外部APIと連携するサービスを作る'],
            ['category' => 'サーバーサイド', 'order' => 27, 'level' => 5, 'title' => 'マイクロサービスの基礎を理解する'],
            ['category' => 'サーバーサイド', 'order' => 28, 'level' => 5, 'title' => 'GraphQL APIを実装する'],
            ['category' => 'サーバーサイド', 'order' => 29, 'level' => 5, 'title' => 'コードレビューを受ける'],
            ['category' => 'サーバーサイド', 'order' => 30, 'level' => 5, 'title' => '卒業制作：ECサイトバックエンドAPIを作る'],

            // ── インフラ ──
            ['category' => 'インフラ', 'order' => 1,  'level' => 1, 'title' => 'Linuxコマンドをマスターする'],
            ['category' => 'インフラ', 'order' => 2,  'level' => 1, 'title' => 'Dockerの基礎を理解する'],
            ['category' => 'インフラ', 'order' => 3,  'level' => 1, 'title' => 'Docker Composeで複数コンテナを管理する'],
            ['category' => 'インフラ', 'order' => 4,  'level' => 1, 'title' => 'Nginxを設定する'],
            ['category' => 'インフラ', 'order' => 5,  'level' => 1, 'title' => 'Git/GitHubでチーム開発の流れを理解する'],
            ['category' => 'インフラ', 'order' => 6,  'level' => 1, 'title' => 'VPSにLaravelを手動デプロイする'],
            ['category' => 'インフラ', 'order' => 7,  'level' => 2, 'title' => 'GitHub ActionsでCIパイプラインを作る'],
            ['category' => 'インフラ', 'order' => 8,  'level' => 2, 'title' => 'GitHub ActionsでCDパイプラインを作る'],
            ['category' => 'インフラ', 'order' => 9,  'level' => 2, 'title' => 'AWSの基礎サービスを理解する'],
            ['category' => 'インフラ', 'order' => 10, 'level' => 2, 'title' => 'EC2にLaravelをデプロイする'],
            ['category' => 'インフラ', 'order' => 11, 'level' => 2, 'title' => 'RDSでMySQLを本番運用する'],
            ['category' => 'インフラ', 'order' => 12, 'level' => 2, 'title' => 'S3で静的ファイルを管理する'],
            ['category' => 'インフラ', 'order' => 13, 'level' => 2, 'title' => 'HTTPS化とSSL証明書を設定する'],
            ['category' => 'インフラ', 'order' => 14, 'level' => 2, 'title' => 'CloudWatchで監視・アラートを設定する'],
            ['category' => 'インフラ', 'order' => 15, 'level' => 3, 'title' => 'Terraformでインフラをコード化する'],
            ['category' => 'インフラ', 'order' => 16, 'level' => 3, 'title' => 'Ansibleでサーバー設定を自動化する'],
            ['category' => 'インフラ', 'order' => 17, 'level' => 3, 'title' => 'Dockerイメージを最適化する'],
            ['category' => 'インフラ', 'order' => 18, 'level' => 3, 'title' => 'ECSでコンテナをAWSで動かす'],
            ['category' => 'インフラ', 'order' => 19, 'level' => 3, 'title' => 'Kubernetesの基礎を理解する'],
            ['category' => 'インフラ', 'order' => 20, 'level' => 3, 'title' => 'DBバックアップと障害復旧を実装する'],
            ['category' => 'インフラ', 'order' => 21, 'level' => 3, 'title' => 'WAFとセキュリティ対策を実装する'],
            ['category' => 'インフラ', 'order' => 22, 'level' => 3, 'title' => 'コスト最適化をする'],
            ['category' => 'インフラ', 'order' => 23, 'level' => 4, 'title' => 'マルチAZ構成で高可用性を実現する'],
            ['category' => 'インフラ', 'order' => 24, 'level' => 4, 'title' => 'サービスメッシュを理解する'],
            ['category' => 'インフラ', 'order' => 25, 'level' => 4, 'title' => 'SREの基礎を実践する'],
            ['category' => 'インフラ', 'order' => 26, 'level' => 4, 'title' => 'セキュリティ診断を実施する'],
            ['category' => 'インフラ', 'order' => 27, 'level' => 4, 'title' => 'パフォーマンステストをする'],
            ['category' => 'インフラ', 'order' => 28, 'level' => 5, 'title' => '本番グレードのCI/CDパイプラインを構築する'],
            ['category' => 'インフラ', 'order' => 29, 'level' => 5, 'title' => 'コードレビューを受ける'],
            ['category' => 'インフラ', 'order' => 30, 'level' => 5, 'title' => '卒業制作：本番グレードのインフラを構築する'],

            // ── SQL/DB設計 ──
            ['category' => 'SQL/DB設計', 'order' => 1, 'level' => 1, 'title' => 'SQLの基本操作をマスターする'],
            ['category' => 'SQL/DB設計', 'order' => 2, 'level' => 1, 'title' => 'テーブル設計の基礎を理解する（正規化）'],
            ['category' => 'SQL/DB設計', 'order' => 3, 'level' => 2, 'title' => 'リレーションとJOINを使う'],
            ['category' => 'SQL/DB設計', 'order' => 4, 'level' => 2, 'title' => 'インデックスでクエリを高速化する'],
            ['category' => 'SQL/DB設計', 'order' => 5, 'level' => 3, 'title' => 'トランザクションとロックを理解する'],
            ['category' => 'SQL/DB設計', 'order' => 6, 'level' => 3, 'title' => 'ストアドプロシージャを作る'],
            ['category' => 'SQL/DB設計', 'order' => 7, 'level' => 4, 'title' => 'DB設計パターンを学ぶ'],
            ['category' => 'SQL/DB設計', 'order' => 8, 'level' => 4, 'title' => 'パフォーマンスチューニングをする'],

            // ── UI/UXデザイン ──
            ['category' => 'UI/UXデザイン', 'order' => 1, 'level' => 1, 'title' => 'UIとUXの違いを理解する'],
            ['category' => 'UI/UXデザイン', 'order' => 2, 'level' => 1, 'title' => 'Figmaの基本操作をマスターする'],
            ['category' => 'UI/UXデザイン', 'order' => 3, 'level' => 2, 'title' => 'コンポーネント設計でデザインを効率化する'],
            ['category' => 'UI/UXデザイン', 'order' => 4, 'level' => 2, 'title' => 'カラー・タイポグラフィの基礎を学ぶ'],
            ['category' => 'UI/UXデザイン', 'order' => 5, 'level' => 2, 'title' => 'ワイヤーフレームを作る'],
            ['category' => 'UI/UXデザイン', 'order' => 6, 'level' => 3, 'title' => 'プロトタイプを作る'],
            ['category' => 'UI/UXデザイン', 'order' => 7, 'level' => 3, 'title' => 'デザインカンプをコーディングする'],
            ['category' => 'UI/UXデザイン', 'order' => 8, 'level' => 4, 'title' => 'アクセシビリティを考慮したデザインをする'],

            // ── TypeScript ──
            ['category' => 'TypeScript', 'order' => 1, 'level' => 2, 'title' => 'TypeScriptの基礎を理解する（型・インターフェース）'],
            ['category' => 'TypeScript', 'order' => 2, 'level' => 2, 'title' => '関数と型注釈を使う'],
            ['category' => 'TypeScript', 'order' => 3, 'level' => 3, 'title' => 'ジェネリクスを理解する'],
            ['category' => 'TypeScript', 'order' => 4, 'level' => 3, 'title' => 'Reactコンポーネントを型付けする'],
            ['category' => 'TypeScript', 'order' => 5, 'level' => 3, 'title' => 'APIレスポンスを型定義する'],
            ['category' => 'TypeScript', 'order' => 6, 'level' => 4, 'title' => 'ユーティリティ型を使う'],
            ['category' => 'TypeScript', 'order' => 7, 'level' => 4, 'title' => '既存JSプロジェクトをTSに移行する'],

            // ── Python基礎 ──
            ['category' => 'Python基礎', 'order' => 1, 'level' => 1, 'title' => 'Python環境構築をする'],
            ['category' => 'Python基礎', 'order' => 2, 'level' => 1, 'title' => 'Pythonの基礎文法を習得する'],
            ['category' => 'Python基礎', 'order' => 3, 'level' => 1, 'title' => 'リスト・辞書・タプルを使う'],
            ['category' => 'Python基礎', 'order' => 4, 'level' => 2, 'title' => 'クラスとオブジェクト指向を理解する'],
            ['category' => 'Python基礎', 'order' => 5, 'level' => 2, 'title' => 'ファイル操作をする'],
            ['category' => 'Python基礎', 'order' => 6, 'level' => 2, 'title' => '外部ライブラリを使う（requests等）'],
            ['category' => 'Python基礎', 'order' => 7, 'level' => 3, 'title' => 'データ処理をする（pandas入門）'],

            // ── Django ──
            ['category' => 'Django', 'order' => 1, 'level' => 2, 'title' => 'Djangoで開発環境を構築する'],
            ['category' => 'Django', 'order' => 2, 'level' => 2, 'title' => 'MVTパターンを理解する'],
            ['category' => 'Django', 'order' => 3, 'level' => 2, 'title' => 'モデルとマイグレーションを使う'],
            ['category' => 'Django', 'order' => 4, 'level' => 3, 'title' => 'ビューとテンプレートを作る'],
            ['category' => 'Django', 'order' => 5, 'level' => 3, 'title' => 'Formバリデーションを実装する'],
            ['category' => 'Django', 'order' => 6, 'level' => 3, 'title' => 'Django REST Frameworkを使う'],
            ['category' => 'Django', 'order' => 7, 'level' => 4, 'title' => '認証機能を実装する'],

            // ── Java基礎 ──
            ['category' => 'Java基礎', 'order' => 1, 'level' => 1, 'title' => 'Java環境構築をする'],
            ['category' => 'Java基礎', 'order' => 2, 'level' => 1, 'title' => 'Javaの基礎文法を習得する'],
            ['category' => 'Java基礎', 'order' => 3, 'level' => 2, 'title' => 'オブジェクト指向を理解する（クラス・継承）'],
            ['category' => 'Java基礎', 'order' => 4, 'level' => 2, 'title' => '例外処理を実装する'],
            ['category' => 'Java基礎', 'order' => 5, 'level' => 2, 'title' => 'コレクションを使う'],
            ['category' => 'Java基礎', 'order' => 6, 'level' => 3, 'title' => 'ファイルI/Oを実装する'],
            ['category' => 'Java基礎', 'order' => 7, 'level' => 3, 'title' => 'ユニットテストを書く（JUnit）'],

            // ── Spring Boot ──
            ['category' => 'Spring Boot', 'order' => 1, 'level' => 2, 'title' => 'Spring Bootで開発環境を構築する'],
            ['category' => 'Spring Boot', 'order' => 2, 'level' => 2, 'title' => 'REST APIを作る'],
            ['category' => 'Spring Boot', 'order' => 3, 'level' => 3, 'title' => 'Spring Data JPAでDB操作をする'],
            ['category' => 'Spring Boot', 'order' => 4, 'level' => 3, 'title' => 'バリデーションを実装する'],
            ['category' => 'Spring Boot', 'order' => 5, 'level' => 4, 'title' => 'Spring Securityで認証を実装する'],
            ['category' => 'Spring Boot', 'order' => 6, 'level' => 4, 'title' => 'テストを書く'],

            // ── 基本情報技術者試験 ──
            ['category' => '基本情報技術者試験', 'order' => 1, 'level' => 1, 'title' => 'コンピュータの基礎を理解する（2進数・論理回路）'],
            ['category' => '基本情報技術者試験', 'order' => 2, 'level' => 1, 'title' => 'データ構造とアルゴリズムを学ぶ'],
            ['category' => '基本情報技術者試験', 'order' => 3, 'level' => 2, 'title' => 'OSの基礎を理解する'],
            ['category' => '基本情報技術者試験', 'order' => 4, 'level' => 2, 'title' => 'ネットワークの基礎を理解する'],
            ['category' => '基本情報技術者試験', 'order' => 5, 'level' => 2, 'title' => 'セキュリティの基礎を理解する'],
            ['category' => '基本情報技術者試験', 'order' => 6, 'level' => 2, 'title' => 'データベースの基礎を理解する'],
            ['category' => '基本情報技術者試験', 'order' => 7, 'level' => 3, 'title' => 'ソフトウェア開発手法を学ぶ'],
            ['category' => '基本情報技術者試験', 'order' => 8, 'level' => 3, 'title' => '過去問演習をする'],
        ];

        foreach ($tasks as $task) {
            TaskMaster::create([
                'category'    => $task['category'],
                'order'       => $task['order'],
                'level'       => $task['level'],
                'title'       => $task['title'],
                'description' => '',
                'is_official' => true,
                'visibility'  => 'official',
                'organization_id' => null,
                'created_by'  => null,
            ]);
        }
    }
}
