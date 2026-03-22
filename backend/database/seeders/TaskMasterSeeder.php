<?php
// database/seeders/TaskMasterSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskMaster;
use Illuminate\Support\Facades\DB; // ← 追加

class TaskMasterSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TaskMaster::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tasks = array_merge(
            $this->frontendTasks(),
            $this->backendTasks(),
            $this->infraTasks()
        );

        foreach ($tasks as $task) {
            TaskMaster::create($task);
        }
    }

    private function frontendTasks(): array
    {
        return [
            ['category' => 'フロントエンド', 'order' => 1, 'level' => 1, 'title' => 'Webの仕組みを理解する', 'description' => "【作るもの】調査レポート（Markdownファイル）\n【課題内容】\n- ブラウザにURLを入力してから画面が表示されるまでの流れを図解する\n- HTTP/HTTPSの違いを説明する\n- HTMLとCSS・JavaScriptの役割をそれぞれ説明する\n- サーバーとクライアントの違いを説明する\n【提出物】\n- GitHub上のREADME.mdに図と説明をまとめる"],
            ['category' => 'フロントエンド', 'order' => 2, 'level' => 1, 'title' => 'HTMLで自己紹介ページを作る', 'description' => "【作るもの】静的HTMLページ\n【機能要件】\n- 名前・出身・趣味を表示\n- 箇条書きリストでスキルを列挙\n- リンクで外部サイトに遷移\n【技術指定】\n- HTMLのみ（CSSなし）\n- h1/h2/p/ul/li/a/img タグを全て使う\n- W3C バリデーターでエラーなし"],
            ['category' => 'フロントエンド', 'order' => 3, 'level' => 1, 'title' => 'CSSでページをデザインする', 'description' => "【作るもの】CSS付き自己紹介ページ\n【機能要件】\n- フォント・色・余白を整える\n- ヘッダー・メイン・フッターのレイアウト\n- ホバー時にリンクの色が変わる\n【技術指定】\n- 外部CSSファイルで記述\n- セレクタ・プロパティ・ボックスモデルを理解して使う"],
            ['category' => 'フロントエンド', 'order' => 4, 'level' => 1, 'title' => 'Flexboxでナビゲーションバーを作る', 'description' => "【作るもの】レスポンシブなナビゲーションバー\n【機能要件】\n- ロゴ（左）とメニュー（右）を横並び\n- メニュー項目は4つ以上\n- スマホ幅ではメニューを縦並びに\n【技術指定】\n- Flexboxのみでレイアウト\n- メディアクエリで768px以下に対応"],
            ['category' => 'フロントエンド', 'order' => 5, 'level' => 1, 'title' => 'CSS Gridでポートフォリオギャラリーを作る', 'description' => "【作るもの】作品ギャラリーページ\n【機能要件】\n- 作品カードを3列グリッドで表示\n- カードにホバーで拡大エフェクト\n- スマホでは1列に変更\n【技術指定】\n- CSS Gridでレイアウト\n- transform: scale() でホバーエフェクト"],
            ['category' => 'フロントエンド', 'order' => 6, 'level' => 2, 'title' => 'JavaScriptで電卓を作る', 'description' => "【作るもの】ブラウザで動く電卓\n【機能要件】\n- 四則演算（+ - × ÷）\n- 小数点・クリア・バックスペース\n- キーボード入力にも対応\n【技術指定】\n- バニラJS（フレームワークなし）\n- eval() は使わず自前で計算ロジックを書く"],
            ['category' => 'フロントエンド', 'order' => 7, 'level' => 2, 'title' => '配列とループでクイズアプリを作る', 'description' => "【作るもの】10問クイズアプリ\n【機能要件】\n- 問題を1問ずつ表示\n- 4択の回答ボタン\n- 正解・不正解のフィードバック\n- 最後にスコアを表示\n【技術指定】\n- 問題データを配列で管理\n- forEach / map / filter を使う"],
            ['category' => 'フロントエンド', 'order' => 8, 'level' => 2, 'title' => 'LocalStorageでTodoアプリを作る', 'description' => "【作るもの】データが消えないTodoアプリ\n【機能要件】\n- Todo追加・削除・完了チェック\n- 完了/未完了フィルター\n- ページリロードしてもデータが残る\n【技術指定】\n- LocalStorage（JSON.stringify/parse）\n- イベント委任（event delegation）"],
            ['category' => 'フロントエンド', 'order' => 9, 'level' => 2, 'title' => 'Fetch APIで天気アプリを作る', 'description' => "【作るもの】リアルタイム天気表示アプリ\n【機能要件】\n- 都市名を入力して現在の天気を取得\n- 気温・天気・湿度・風速を表示\n- 読み込み中・エラー状態を表示\n【技術指定】\n- Fetch API（async/await）\n- OpenWeatherMap API（無料）\n- try/catchでエラーハンドリング"],
            ['category' => 'フロントエンド', 'order' => 10, 'level' => 2, 'title' => '非同期処理とPromiseを理解する', 'description' => "【作るもの】GitHub ユーザー検索アプリ\n【機能要件】\n- ユーザー名検索でプロフィール表示\n- リポジトリ一覧を表示\n- 入力から500ms後に検索（デバウンス）\n【技術指定】\n- Promise / async / await の使い分け\n- デバウンス関数を自作"],
            ['category' => 'フロントエンド', 'order' => 11, 'level' => 2, 'title' => 'モジュール分割でコードを整理する', 'description' => "【作るもの】モジュール化されたショッピングカート\n【機能要件】\n- 商品一覧・カート・合計金額\n- 商品追加・削除・数量変更\n【技術指定】\n- ES Modules（import/export）\n- Viteで開発環境構築"],
            ['category' => 'フロントエンド', 'order' => 12, 'level' => 2, 'title' => 'Git/GitHubでバージョン管理をする', 'description' => "【作るもの】これまでの作品をGitHubで公開\n【課題内容】\n- ローカルリポジトリ作成 → GitHubにpush\n- ブランチを切って機能追加 → マージ\n- GitHub Pagesで公開\n【技術指定】\n- git init/add/commit/push/pull\n- プルリクエストを作ってセルフマージ"],
            ['category' => 'フロントエンド', 'order' => 13, 'level' => 3, 'title' => 'Reactでカウンターアプリを作る', 'description' => "【作るもの】はじめてのReactアプリ\n【機能要件】\n- ＋／－ボタンでカウントアップ・ダウン\n- リセットボタン\n- カウントが0未満にならないよう制御\n【技術指定】\n- Vite + React\n- useState フック\n- props でコンポーネントに値を渡す"],
            ['category' => 'フロントエンド', 'order' => 14, 'level' => 3, 'title' => 'useEffectでデータ取得アプリを作る', 'description' => "【作るもの】ポケモン図鑑アプリ\n【機能要件】\n- 一覧ページ（最初の20匹）\n- クリックで詳細表示\n- ページネーション\n【技術指定】\n- useEffect でAPIフェッチ\n- PokeAPI（無料）を使用\n- コンポーネント分割"],
            ['category' => 'フロントエンド', 'order' => 15, 'level' => 3, 'title' => 'React Routerでマルチページアプリを作る', 'description' => "【作るもの】レシピサイト\n【機能要件】\n- トップ・詳細・カテゴリ別・404ページ\n【技術指定】\n- React Router v6\n- useParams / useNavigate / Link\n- ネストルーティング"],
            ['category' => 'フロントエンド', 'order' => 16, 'level' => 3, 'title' => 'フォームバリデーションを実装する', 'description' => "【作るもの】会員登録フォーム\n【機能要件】\n- 名前・メール・パスワード・確認パスワード\n- リアルタイムバリデーション\n- エラーメッセージの表示\n【技術指定】\n- React Hook Form\n- 送信成功時のトースト通知"],
            ['category' => 'フロントエンド', 'order' => 17, 'level' => 3, 'title' => 'Context APIでグローバル状態管理をする', 'description' => "【作るもの】テーマ切り替え付きメモアプリ\n【機能要件】\n- ライト/ダークモード切り替え\n- メモの追加・削除・検索\n【技術指定】\n- React Context API\n- useReducer\n- カスタムフック"],
            ['category' => 'フロントエンド', 'order' => 18, 'level' => 3, 'title' => 'SCSSでデザインシステムを作る', 'description' => "【作るもの】UIコンポーネントカタログ\n【機能要件】\n- Button / Input / Card / Badge / Modal\n【技術指定】\n- CSS Modules + SCSS\n- 変数・mixin・extend を活用\n- BEM命名規則"],
            ['category' => 'フロントエンド', 'order' => 19, 'level' => 3, 'title' => 'axiosでLaravel APIに接続する', 'description' => "【作るもの】タスク管理アプリ（API連携）\n【機能要件】\n- タスク一覧取得・追加・更新・削除\n- ログイン認証（Sanctum）\n【技術指定】\n- axios インスタンス（共通設定）\n- APIエラーのインターセプター"],
            ['category' => 'フロントエンド', 'order' => 20, 'level' => 4, 'title' => 'カスタムフックでロジックを分離する', 'description' => "【作るもの】リアルタイム検索アプリ\n【技術指定】\n- useDebounce / useFetch / useLocalStorage カスタムフック\n- コンポーネントからロジックを完全分離"],
            ['category' => 'フロントエンド', 'order' => 21, 'level' => 4, 'title' => 'パフォーマンス最適化をする', 'description' => "【作るもの】1000件の商品一覧アプリ\n【技術指定】\n- React.memo / useMemo / useCallback\n- 仮想スクロール（react-window）\n- Lighthouse 90点以上"],
            ['category' => 'フロントエンド', 'order' => 22, 'level' => 4, 'title' => 'ReactでドラッグandドロップUIを作る', 'description' => "【作るもの】カンバンボード（Trello風）\n【機能要件】\n- タスクを3列で管理\n- ドラッグで列間を移動\n【技術指定】\n- dnd-kit ライブラリ"],
            ['category' => 'フロントエンド', 'order' => 23, 'level' => 4, 'title' => 'アニメーションを実装する', 'description' => "【作るもの】アニメーション付きランディングページ\n【技術指定】\n- Framer Motion\n- Intersection Observer\n- prefers-reduced-motion 対応"],
            ['category' => 'フロントエンド', 'order' => 24, 'level' => 4, 'title' => 'TypeScriptに移行する', 'description' => "【作るもの】型安全なTodoアプリ\n【技術指定】\n- TypeScript（strict mode）\n- interface / type / generics\n- any 禁止"],
            ['category' => 'フロントエンド', 'order' => 25, 'level' => 4, 'title' => 'テストコードを書く', 'description' => "【作るもの】テスト付きフォームコンポーネント\n【技術指定】\n- Vitest + React Testing Library\n- MSW でAPIモック\n- カバレッジ80%以上"],
            ['category' => 'フロントエンド', 'order' => 26, 'level' => 5, 'title' => 'デザインカンプをコーディングする', 'description' => "【作るもの】FigmaデザインのHTML/CSS実装\n【技術指定】\n- Figma Dev Modeで数値確認\n- CSS変数でデザイントークン管理"],
            ['category' => 'フロントエンド', 'order' => 27, 'level' => 5, 'title' => 'PWAを実装する', 'description' => "【作るもの】オフライン対応Webアプリ\n【技術指定】\n- Service Worker\n- Web App Manifest\n- Lighthouse PWAスコア100点"],
            ['category' => 'フロントエンド', 'order' => 28, 'level' => 5, 'title' => 'SEO・アクセシビリティ対応をする', 'description' => "【作るもの】SEO最適化済みブログ\n【技術指定】\n- Next.js（SSG/SSR）\n- WAI-ARIAの適切な使用\n- Lighthouse 全項目90点以上"],
            ['category' => 'フロントエンド', 'order' => 29, 'level' => 5, 'title' => 'コードレビューを受ける', 'description' => "【課題内容】\n- ベストな成果物をGitHub PRにまとめる\n- レビューコメントに対応\n- 修正内容と学びをREADMEにまとめる"],
            ['category' => 'フロントエンド', 'order' => 30, 'level' => 5, 'title' => '卒業制作：ポートフォリオサイトを公開する', 'description' => "【作るもの】本番公開ポートフォリオ\n【技術指定】\n- React + TypeScript\n- Vercelにデプロイ\n- Lighthouse 全項目90点以上\n- OGP画像設定"],
        ];
    }

    private function backendTasks(): array
    {
        return [
            ['category' => 'サーバーサイド', 'order' => 1, 'level' => 1, 'title' => 'コマンドラインの基本操作をマスターする', 'description' => "【課題内容】\n- ファイル操作（ls/cd/mkdir/rm/cp/mv）\n- テキスト操作（cat/grep/sed/awk）\n- プロセス管理（ps/kill/top）\n- シェルスクリプトで自動化\n【提出物】\n- よく使うコマンド集をREADME.mdにまとめる\n- 「指定フォルダのファイルを日付別に整理する」シェルスクリプトを作成"],
            ['category' => 'サーバーサイド', 'order' => 2, 'level' => 1, 'title' => 'PHPの基礎文法を習得する', 'description' => "【作るもの】PHPで動く計算ツール\n【課題内容】\n- 変数・型・演算子\n- if/for/while/foreach\n- 関数定義と引数\n- 配列・連想配列の操作\n【技術指定】\n- PHP 8.x\n- FizzBuzzを書く"],
            ['category' => 'サーバーサイド', 'order' => 3, 'level' => 1, 'title' => 'PHPでオブジェクト指向を理解する', 'description' => "【作るもの】銀行口座クラス\n【機能要件】\n- 入金・出金・残高照会メソッド\n- 残高不足時の例外処理\n【技術指定】\n- クラス・プロパティ・メソッド\n- 継承・インターフェース\n- try/catch/finally"],
            ['category' => 'サーバーサイド', 'order' => 4, 'level' => 1, 'title' => 'MySQLの基本操作をマスターする', 'description' => "【作るもの】ユーザー管理DB\n【課題内容】\n- テーブル作成（CREATE TABLE）\n- CRUD操作（SELECT/INSERT/UPDATE/DELETE）\n- JOINで複数テーブル結合\n【技術指定】\n- DockerでMySQL環境構築\n- 外部キー制約の設定"],
            ['category' => 'サーバーサイド', 'order' => 5, 'level' => 1, 'title' => 'PHPとMySQLを連携させる', 'description' => "【作るもの】PHPで動く掲示板\n【機能要件】\n- 投稿の一覧表示・追加・削除\n- XSS対策（HTMLエスケープ）\n【技術指定】\n- PDO（プリペアドステートメント）\n- SQLインジェクション対策"],
            ['category' => 'サーバーサイド', 'order' => 6, 'level' => 2, 'title' => 'LaravelとDockerで開発環境を構築する', 'description' => "【作るもの】Laravel Sail環境\n【課題内容】\n- Docker + Laravel Sail で環境構築\n- Hello World APIを作成してPostmanで確認\n【技術指定】\n- Laravel 11.x + Sail\n- artisan route:list でルート確認"],
            ['category' => 'サーバーサイド', 'order' => 7, 'level' => 2, 'title' => 'MVC構造とルーティングを理解する', 'description' => "【作るもの】ブログAPIの骨格\n【技術指定】\n- routes/api.php でルーティング\n- Resource Controllerの使い方\n- Route::apiResource"],
            ['category' => 'サーバーサイド', 'order' => 8, 'level' => 2, 'title' => 'MigrationとEloquentでDB操作をする', 'description' => "【作るもの】投稿管理API\n【技術指定】\n- Migrationでテーブル作成\n- Eloquent ORM\n- Factoryで100件のテストデータ生成"],
            ['category' => 'サーバーサイド', 'order' => 9, 'level' => 2, 'title' => 'リレーションとeager loadingを使う', 'description' => "【作るもの】カテゴリ付きブログAPI\n【技術指定】\n- hasMany / belongsTo\n- with() でN+1問題を解消\n- whereHas() で関連テーブルを絞り込み"],
            ['category' => 'サーバーサイド', 'order' => 10, 'level' => 2, 'title' => 'バリデーションとエラーレスポンスを実装する', 'description' => "【作るもの】入力チェック付きユーザー登録API\n【技術指定】\n- FormRequestクラス\n- カスタムバリデーションルール\n- 日本語エラーメッセージ"],
            ['category' => 'サーバーサイド', 'order' => 11, 'level' => 2, 'title' => 'Sanctumで認証APIを実装する', 'description' => "【作るもの】ログイン機能付きAPI\n【技術指定】\n- Laravel Sanctum（SPA認証）\n- auth:sanctum ミドルウェア\n- Postmanで認証フローをテスト"],
            ['category' => 'サーバーサイド', 'order' => 12, 'level' => 2, 'title' => 'APIリソースでレスポンスを整形する', 'description' => "【作るもの】整形されたレスポンスのAPI\n【技術指定】\n- Laravel API Resource\n- ResourceCollection\n- whenLoaded() で条件付き読み込み"],
            ['category' => 'サーバーサイド', 'order' => 13, 'level' => 3, 'title' => 'ファイルアップロードを実装する', 'description' => "【作るもの】画像アップロードAPI\n【技術指定】\n- Laravel Storage Facade\n- Intervention Image でリサイズ\n- バリデーション（mimes/max）"],
            ['category' => 'サーバーサイド', 'order' => 14, 'level' => 3, 'title' => '検索・フィルタ・ソートAPIを作る', 'description' => "【作るもの】高機能な商品検索API\n【技術指定】\n- Eloquentスコープ\n- N+1問題の解消を確認"],
            ['category' => 'サーバーサイド', 'order' => 15, 'level' => 3, 'title' => 'Policyで権限管理を実装する', 'description' => "【作るもの】権限付き投稿管理API\n【技術指定】\n- Laravel Policy\n- Gate ファサード\n- 権限なしは403エラーを返す"],
            ['category' => 'サーバーサイド', 'order' => 16, 'level' => 3, 'title' => 'キューとジョブで非同期処理をする', 'description' => "【作るもの】メール非同期送信システム\n【技術指定】\n- Laravel Queue（database driver）\n- Mailableクラス\n- php artisan queue:work"],
            ['category' => 'サーバーサイド', 'order' => 17, 'level' => 3, 'title' => 'イベントとリスナーで疎結合設計をする', 'description' => "【作るもの】通知機能付き注文システム\n【技術指定】\n- Laravel Event / Listener\n- ShouldQueue インターフェース"],
            ['category' => 'サーバーサイド', 'order' => 18, 'level' => 3, 'title' => 'キャッシュでAPIを高速化する', 'description' => "【作るもの】キャッシュ付き商品API\n【技術指定】\n- Laravel Cache（Redis）\n- Cache::remember / forget / tags"],
            ['category' => 'サーバーサイド', 'order' => 19, 'level' => 3, 'title' => 'DBトランザクションで整合性を保つ', 'description' => "【作るもの】在庫管理付き注文API\n【技術指定】\n- DB::transaction()\n- 楽観的ロック（lockForUpdate）"],
            ['category' => 'サーバーサイド', 'order' => 20, 'level' => 3, 'title' => 'APIドキュメントを作成する', 'description' => "【作るもの】Swagger UIドキュメント\n【技術指定】\n- L5-Swagger\n- @OA アノテーション記法\n- /api/documentation でUI表示"],
            ['category' => 'サーバーサイド', 'order' => 21, 'level' => 4, 'title' => 'PHPUnitでFeatureテストを書く', 'description' => "【作るもの】テスト付きTODO API\n【技術指定】\n- PHPUnit + RefreshDatabase\n- Factory でデータ準備\n- カバレッジ80%以上"],
            ['category' => 'サーバーサイド', 'order' => 22, 'level' => 4, 'title' => 'リファクタリングしてコードを改善する', 'description' => "【課題内容】\n- Controllerを薄くする（Serviceクラス抽出）\n- 重複コードを排除\n- Controllerが200行以内"],
            ['category' => 'サーバーサイド', 'order' => 23, 'level' => 4, 'title' => 'セキュリティ対策を実装する', 'description' => "【課題内容】\n- SQLインジェクション・XSS・CSRF対策\n- レートリミット（API制限）\n- OWASP Top 10 の理解"],
            ['category' => 'サーバーサイド', 'order' => 24, 'level' => 4, 'title' => 'パフォーマンスチューニングをする', 'description' => "【課題内容】\n- スロークエリを特定して改善\n- DBインデックスを適切に設定\n- Redisでセッション・キャッシュを管理"],
            ['category' => 'サーバーサイド', 'order' => 25, 'level' => 4, 'title' => 'WebSocketでリアルタイム通信を実装する', 'description' => "【作るもの】リアルタイムチャットAPI\n【技術指定】\n- Laravel Broadcasting\n- Pusher または Soketi\n- Presence Channel"],
            ['category' => 'サーバーサイド', 'order' => 26, 'level' => 5, 'title' => '外部APIと連携するサービスを作る', 'description' => "【作るもの】決済機能付きAPIサービス\n【技術指定】\n- Stripe PHP SDK\n- Webhook署名の検証\n- 二重課金防止"],
            ['category' => 'サーバーサイド', 'order' => 27, 'level' => 5, 'title' => 'マイクロサービスの基礎を理解する', 'description' => "【作るもの】API Gateway + 2サービス構成\n【技術指定】\n- 各サービスを別々のLaravelプロジェクトで\n- サービス間のJWT認証"],
            ['category' => 'サーバーサイド', 'order' => 28, 'level' => 5, 'title' => 'GraphQL APIを実装する', 'description' => "【作るもの】GraphQL対応ブログAPI\n【技術指定】\n- Lighthouse（Laravel GraphQL）\n- DataLoader でバッチ取得"],
            ['category' => 'サーバーサイド', 'order' => 29, 'level' => 5, 'title' => 'コードレビューを受ける', 'description' => "【課題内容】\n- ベストな成果物をGitHub PRにまとめる\n- SOLID原則の遵守\n- テストカバレッジ"],
            ['category' => 'サーバーサイド', 'order' => 30, 'level' => 5, 'title' => '卒業制作：ECサイトバックエンドAPIを作る', 'description' => "【作るもの】本格的なECサイトAPI\n【技術指定】\n- 全エンドポイントにFeatureテスト\n- Swagger UIでドキュメント化\n- GitHub Actionsで自動テスト"],
        ];
    }

    private function infraTasks(): array
    {
        return [
            ['category' => 'インフラ', 'order' => 1, 'level' => 1, 'title' => 'Linuxコマンドをマスターする', 'description' => "【課題内容】\n- ファイル操作・権限管理・プロセス管理\n- パイプ・リダイレクト・環境変数\n- ネットワーク確認（ping/curl/netstat）\n- cronで定期実行\n【提出物】\n- よく使うコマンド100選をまとめたREADME\n- サーバーの死活監視シェルスクリプト"],
            ['category' => 'インフラ', 'order' => 2, 'level' => 1, 'title' => 'Dockerの基礎を理解する', 'description' => "【作るもの】Nginx + PHPのコンテナ環境\n【技術指定】\n- docker pull/run/build/ps/logs/exec\n- Dockerfile（FROM/RUN/COPY/CMD）\n- ポートマッピング・ボリュームマウント"],
            ['category' => 'インフラ', 'order' => 3, 'level' => 1, 'title' => 'Docker Composeで複数コンテナを管理する', 'description' => "【作るもの】Laravel + MySQL + Redis 環境\n【技術指定】\n- docker-compose.yml\n- サービス間通信\n- depends_on で起動順序制御"],
            ['category' => 'インフラ', 'order' => 4, 'level' => 1, 'title' => 'Nginxを設定する', 'description' => "【作るもの】Nginx + PHP-FPM 本番設定\n【技術指定】\n- nginx.conf（server/location ブロック）\n- upstream で PHP-FPM に接続\n- Gzip圧縮で転送量削減"],
            ['category' => 'インフラ', 'order' => 5, 'level' => 1, 'title' => 'Git/GitHubでチーム開発の流れを理解する', 'description' => "【課題内容】\n- ブランチ戦略（Git Flow）の実践\n- PRを作ってセルフレビュー\n- コンフリクト解消の練習\n【技術指定】\n- git branch/checkout/merge/rebase\n- conventional commits"],
            ['category' => 'インフラ', 'order' => 6, 'level' => 1, 'title' => 'VPSにLaravelを手動デプロイする', 'description' => "【作るもの】VPS上で動くLaravelアプリ\n【技術指定】\n- SSH鍵認証\n- Nginx + PHP + MySQL + Composer\n- ファイルパーミッションの設定"],
            ['category' => 'インフラ', 'order' => 7, 'level' => 2, 'title' => 'GitHub ActionsでCIパイプラインを作る', 'description' => "【作るもの】自動テスト環境\n【技術指定】\n- .github/workflows/ci.yml\n- services（MySQL）でのテスト環境\n- キャッシュで実行時間を短縮"],
            ['category' => 'インフラ', 'order' => 8, 'level' => 2, 'title' => 'GitHub ActionsでCDパイプラインを作る', 'description' => "【作るもの】自動デプロイ環境\n【技術指定】\n- SSH action でリモートデプロイ\n- Secrets で機密情報管理\n- zero-downtime deploy"],
            ['category' => 'インフラ', 'order' => 9, 'level' => 2, 'title' => 'AWSの基礎サービスを理解する', 'description' => "【課題内容】\n- AWSの主要サービスの役割を説明\n- EC2インスタンスを立てる\n- S3にファイルをアップロード\n- IAMでユーザーと権限を管理"],
            ['category' => 'インフラ', 'order' => 10, 'level' => 2, 'title' => 'EC2にLaravelをデプロイする', 'description' => "【作るもの】AWS EC2上の本番環境\n【技術指定】\n- EC2（t2.micro 無料枠）\n- Elastic IPで固定IP\n- Route53でカスタムドメイン設定"],
            ['category' => 'インフラ', 'order' => 11, 'level' => 2, 'title' => 'RDSでMySQLを本番運用する', 'description' => "【作るもの】マネージドDB環境\n【技術指定】\n- RDS MySQL 8.x\n- 自動バックアップ（7日間保持）\n- パフォーマンスインサイト"],
            ['category' => 'インフラ', 'order' => 12, 'level' => 2, 'title' => 'S3で静的ファイルを管理する', 'description' => "【作るもの】S3 + CloudFrontの画像配信\n【技術指定】\n- AWS SDK for PHP\n- CloudFrontで高速配信\n- 署名付きURLでプライベートファイル配信"],
            ['category' => 'インフラ', 'order' => 13, 'level' => 2, 'title' => 'HTTPS化とSSL証明書を設定する', 'description' => "【作るもの】SSL/TLS対応サーバー\n【技術指定】\n- Certbot（Let's Encrypt）\n- cron jobで certbot renew\n- SSL Labs でA+評価"],
            ['category' => 'インフラ', 'order' => 14, 'level' => 2, 'title' => 'CloudWatchで監視・アラートを設定する', 'description' => "【作るもの】サーバー監視システム\n【技術指定】\n- CloudWatch Agent\n- CloudWatch Alarm\n- SNS → Lambda → Slack通知"],
            ['category' => 'インフラ', 'order' => 15, 'level' => 3, 'title' => 'Terraformでインフラをコード化する', 'description' => "【作るもの】TerraformでAWS環境を自動構築\n【技術指定】\n- Terraform基本構文\n- variable / output / module\n- terraform plan で差分確認"],
            ['category' => 'インフラ', 'order' => 16, 'level' => 3, 'title' => 'Ansibleでサーバー設定を自動化する', 'description' => "【作るもの】サーバー初期設定の自動化\n【技術指定】\n- Playbook/Role/Task の構造\n- vault で機密情報暗号化"],
            ['category' => 'インフラ', 'order' => 17, 'level' => 3, 'title' => 'Dockerイメージを最適化する', 'description' => "【作るもの】軽量なLaravelコンテナ\n【技術指定】\n- Alpine Linux ベースイメージ\n- マルチステージビルド\n- Trivy でイメージスキャン"],
            ['category' => 'インフラ', 'order' => 18, 'level' => 3, 'title' => 'ECSでコンテナをAWSで動かす', 'description' => "【作るもの】ECS Fargate上のLaravelアプリ\n【技術指定】\n- ECR（コンテナレジストリ）\n- ECS タスク定義・サービス設定\n- Auto Scaling"],
            ['category' => 'インフラ', 'order' => 19, 'level' => 3, 'title' => 'Kubernetesの基礎を理解する', 'description' => "【作るもの】minikube上のLaravelアプリ\n【技術指定】\n- kubectl基本コマンド\n- YAML マニフェストファイル\n- rolling updateで無停止デプロイ"],
            ['category' => 'インフラ', 'order' => 20, 'level' => 3, 'title' => 'DBバックアップと障害復旧を実装する', 'description' => "【作るもの】自動バックアップシステム\n【技術指定】\n- mysqldump スクリプト\n- cron job\n- AWS CLIでS3アップロード"],
            ['category' => 'インフラ', 'order' => 21, 'level' => 3, 'title' => 'WAFとセキュリティ対策を実装する', 'description' => "【課題内容】\n- AWS WAFでSQLインジェクション・XSSをブロック\n- GuardDutyで不正アクセスを検知\n- CloudTrailで操作ログ記録"],
            ['category' => 'インフラ', 'order' => 22, 'level' => 3, 'title' => 'コスト最適化をする', 'description' => "【課題内容】\n- Cost Explorer で分析\n- 使っていないリソースを削除\n- Budget Alert で予算超過防止"],
            ['category' => 'インフラ', 'order' => 23, 'level' => 4, 'title' => 'マルチAZ構成で高可用性を実現する', 'description' => "【作るもの】障害に強いインフラ\n【技術指定】\n- ALB + Auto Scaling Group\n- RDS Multi-AZ\n- Route53 ヘルスチェック"],
            ['category' => 'インフラ', 'order' => 24, 'level' => 4, 'title' => 'サービスメッシュを理解する', 'description' => "【作るもの】Istio を使ったマイクロサービス環境\n【技術指定】\n- Kiali でサービス間の依存関係を可視化\n- Prometheus + Grafana\n- Jaeger でトレーシング"],
            ['category' => 'インフラ', 'order' => 25, 'level' => 4, 'title' => 'SREの基礎を実践する', 'description' => "【課題内容】\n- SLI/SLO/エラーバジェットを定義\n- インシデント対応手順書を作成\n【技術指定】\n- Prometheus でSLIを計測\n- Grafana でSLOダッシュボード作成"],
            ['category' => 'インフラ', 'order' => 26, 'level' => 4, 'title' => 'セキュリティ診断を実施する', 'description' => "【課題内容】\n- OWASP ZAPで脆弱性スキャン\n- Trivy でコンテナイメージのスキャン\n- CIS Benchmark 80%以上準拠"],
            ['category' => 'インフラ', 'order' => 27, 'level' => 4, 'title' => 'パフォーマンステストをする', 'description' => "【作るもの】負荷テスト環境\n【技術指定】\n- k6 で負荷テストシナリオ作成\n- 改善前後のスループット比較"],
            ['category' => 'インフラ', 'order' => 28, 'level' => 5, 'title' => '本番グレードのCI/CDパイプラインを構築する', 'description' => "【作るもの】完全自動化されたデプロイ環境\n【技術指定】\n- Blue/Greenデプロイ\n- カナリアリリース\n- デプロイ失敗時は自動ロールバック"],
            ['category' => 'インフラ', 'order' => 29, 'level' => 5, 'title' => 'コードレビューを受ける', 'description' => "【課題内容】\n- Terraform・Dockerfileなど成果物をGitHub PRにまとめる\n- セキュリティ・コスト・可用性の観点でレビュー"],
            ['category' => 'インフラ', 'order' => 30, 'level' => 5, 'title' => '卒業制作：本番グレードのインフラを構築する', 'description' => "【作るもの】全構成をコードで管理する本番インフラ\n【技術指定】\n- 全AWSリソースをTerraformで管理\n- ECS Fargateでコンテナ運用\n- アーキテクチャ構成図を作成"],
        ];
    }
}
