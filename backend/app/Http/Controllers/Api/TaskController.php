<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private function allTasks(): array
    {
        return array_merge(
            $this->frontendTasks(),
            $this->backendTasks(),
            $this->infraTasks()
        );
    }

    public function index()
    {
        return response()->json($this->allTasks());
    }

    public function show(int $id)
    {
        $task = collect($this->allTasks())->firstWhere('id', $id);
        if (!$task) return response()->json(['message' => '課題が見つかりません'], 404);
        return response()->json($task);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', 'in:todo,in_progress,done'],
        ]);
        $task = collect($this->allTasks())->firstWhere('id', $id);
        if (!$task) return response()->json(['message' => '課題が見つかりません'], 404);
        return response()->json(array_merge($task, ['status' => $request->status]));
    }

    public function submit(Request $request, int $id)
    {
        $request->validate([
            'github_url' => ['required', 'url'],
            'deploy_url' => ['nullable', 'url'],
        ]);
        $task = collect($this->allTasks())->firstWhere('id', $id);
        if (!$task) return response()->json(['message' => '課題が見つかりません'], 404);
        return response()->json([
            'message' => '提出しました',
            'task' => array_merge($task, [
                'status'     => 'done',
                'github_url' => $request->github_url,
                'deploy_url' => $request->deploy_url,
            ]),
        ]);
    }

    private function frontendTasks(): array
    {
        return [
            [
                'id' => 1,
                'category' => 'フロントエンド',
                'order' => 1,
                'title' => 'Webの仕組みを理解する',
                'status' => 'todo',
                'description' => "【作るもの】調査レポート（Markdownファイル）\n【課題内容】\n- ブラウザにURLを入力してから画面が表示されるまでの流れを図解する\n- HTTP/HTTPSの違いを説明する\n- HTMLとCSS・JavaScriptの役割をそれぞれ説明する\n- サーバーとクライアントの違いを説明する\n【提出物】\n- GitHub上のREADME.mdに図と説明をまとめる"
            ],
            [
                'id' => 2,
                'category' => 'フロントエンド',
                'order' => 2,
                'title' => 'HTMLで自己紹介ページを作る',
                'status' => 'todo',
                'description' => "【作るもの】静的HTMLページ\n【機能要件】\n- 名前・出身・趣味を表示\n- 箇条書きリストでスキルを列挙\n- リンクで外部サイトに遷移\n【技術指定】\n- HTMLのみ（CSSなし）\n- h1/h2/p/ul/li/a/img タグを全て使う\n- W3C バリデーターでエラーなし"
            ],
            [
                'id' => 3,
                'category' => 'フロントエンド',
                'order' => 3,
                'title' => 'CSSでページをデザインする',
                'status' => 'todo',
                'description' => "【作るもの】CSS付き自己紹介ページ\n【機能要件】\n- フォント・色・余白を整える\n- ヘッダー・メイン・フッターのレイアウト\n- ホバー時にリンクの色が変わる\n【技術指定】\n- 外部CSSファイルで記述\n- セレクタ・プロパティ・ボックスモデルを理解して使う"
            ],
            [
                'id' => 4,
                'category' => 'フロントエンド',
                'order' => 4,
                'title' => 'Flexboxでナビゲーションバーを作る',
                'status' => 'todo',
                'description' => "【作るもの】レスポンシブなナビゲーションバー\n【機能要件】\n- ロゴ（左）とメニュー（右）を横並び\n- メニュー項目は4つ以上\n- スマホ幅ではメニューを縦並びに\n【技術指定】\n- Flexboxのみでレイアウト\n- メディアクエリで768px以下に対応"
            ],
            [
                'id' => 5,
                'category' => 'フロントエンド',
                'order' => 5,
                'title' => 'CSS Gridでポートフォリオギャラリーを作る',
                'status' => 'todo',
                'description' => "【作るもの】作品ギャラリーページ\n【機能要件】\n- 作品カードを3列グリッドで表示\n- カードにホバーで拡大エフェクト\n- スマホでは1列に変更\n【技術指定】\n- CSS Gridでレイアウト\n- transform: scale() でホバーエフェクト"
            ],
            [
                'id' => 6,
                'category' => 'フロントエンド',
                'order' => 6,
                'title' => 'JavaScriptで電卓を作る',
                'status' => 'todo',
                'description' => "【作るもの】ブラウザで動く電卓\n【機能要件】\n- 四則演算（+ - × ÷）\n- 小数点・クリア・バックスペース\n- キーボード入力にも対応\n【技術指定】\n- バニラJS（フレームワークなし）\n- eval() は使わず自前で計算ロジックを書く"
            ],
            [
                'id' => 7,
                'category' => 'フロントエンド',
                'order' => 7,
                'title' => '配列とループでクイズアプリを作る',
                'status' => 'todo',
                'description' => "【作るもの】10問クイズアプリ\n【機能要件】\n- 問題を1問ずつ表示\n- 4択の回答ボタン\n- 正解・不正解のフィードバック\n- 最後にスコアを表示\n【技術指定】\n- 問題データを配列で管理\n- forEach / map / filter を使う"
            ],
            [
                'id' => 8,
                'category' => 'フロントエンド',
                'order' => 8,
                'title' => 'LocalStorageでTodoアプリを作る',
                'status' => 'todo',
                'description' => "【作るもの】データが消えないTodoアプリ\n【機能要件】\n- Todo追加・削除・完了チェック\n- 完了/未完了フィルター\n- ページリロードしてもデータが残る\n【技術指定】\n- LocalStorage（JSON.stringify/parse）\n- イベント委任（event delegation）"
            ],
            [
                'id' => 9,
                'category' => 'フロントエンド',
                'order' => 9,
                'title' => 'Fetch APIで天気アプリを作る',
                'status' => 'todo',
                'description' => "【作るもの】リアルタイム天気表示アプリ\n【機能要件】\n- 都市名を入力して現在の天気を取得\n- 気温・天気・湿度・風速を表示\n- 読み込み中・エラー状態を表示\n【技術指定】\n- Fetch API（async/await）\n- OpenWeatherMap API（無料）\n- try/catchでエラーハンドリング"
            ],
            [
                'id' => 10,
                'category' => 'フロントエンド',
                'order' => 10,
                'title' => '非同期処理とPromiseを理解する',
                'status' => 'todo',
                'description' => "【作るもの】GitHub ユーザー検索アプリ\n【機能要件】\n- ユーザー名検索でプロフィール表示\n- リポジトリ一覧を表示\n- 入力から500ms後に検索（デバウンス）\n【技術指定】\n- Promise / async / await の使い分け\n- デバウンス関数を自作"
            ],
            [
                'id' => 11,
                'category' => 'フロントエンド',
                'order' => 11,
                'title' => 'モジュール分割でコードを整理する',
                'status' => 'todo',
                'description' => "【作るもの】モジュール化されたショッピングカート\n【機能要件】\n- 商品一覧・カート・合計金額\n- 商品追加・削除・数量変更\n【技術指定】\n- ES Modules（import/export）\n- 機能ごとにファイル分割\n- Viteで開発環境構築"
            ],
            [
                'id' => 12,
                'category' => 'フロントエンド',
                'order' => 12,
                'title' => 'Git/GitHubでバージョン管理をする',
                'status' => 'todo',
                'description' => "【作るもの】これまでの作品をGitHubで公開\n【課題内容】\n- ローカルリポジトリ作成 → GitHubにpush\n- ブランチを切って機能追加 → マージ\n- GitHub Pagesで公開\n【技術指定】\n- git init/add/commit/push/pull\n- プルリクエストを作ってセルフマージ"
            ],
            [
                'id' => 13,
                'category' => 'フロントエンド',
                'order' => 13,
                'title' => 'Reactでカウンターアプリを作る',
                'status' => 'todo',
                'description' => "【作るもの】はじめてのReactアプリ\n【機能要件】\n- ＋／－ボタンでカウントアップ・ダウン\n- リセットボタン\n- カウントが0未満にならないよう制御\n【技術指定】\n- Vite + React\n- useState フック\n- props でコンポーネントに値を渡す"
            ],
            [
                'id' => 14,
                'category' => 'フロントエンド',
                'order' => 14,
                'title' => 'useEffectでデータ取得アプリを作る',
                'status' => 'todo',
                'description' => "【作るもの】ポケモン図鑑アプリ\n【機能要件】\n- 一覧ページ（最初の20匹）\n- クリックで詳細表示\n- ページネーション\n【技術指定】\n- useEffect でAPIフェッチ\n- PokeAPI（無料）を使用\n- コンポーネント分割"
            ],
            [
                'id' => 15,
                'category' => 'フロントエンド',
                'order' => 15,
                'title' => 'React Routerでマルチページアプリを作る',
                'status' => 'todo',
                'description' => "【作るもの】レシピサイト\n【機能要件】\n- トップ・詳細・カテゴリ別・404ページ\n【技術指定】\n- React Router v6\n- useParams / useNavigate / Link\n- ネストルーティング"
            ],
            [
                'id' => 16,
                'category' => 'フロントエンド',
                'order' => 16,
                'title' => 'フォームバリデーションを実装する',
                'status' => 'todo',
                'description' => "【作るもの】会員登録フォーム\n【機能要件】\n- 名前・メール・パスワード・確認パスワード\n- リアルタイムバリデーション\n- エラーメッセージの表示\n【技術指定】\n- React Hook Form\n- 送信成功時のトースト通知"
            ],
            [
                'id' => 17,
                'category' => 'フロントエンド',
                'order' => 17,
                'title' => 'Context APIでグローバル状態管理をする',
                'status' => 'todo',
                'description' => "【作るもの】テーマ切り替え付きメモアプリ\n【機能要件】\n- ライト/ダークモード切り替え\n- メモの追加・削除・検索\n【技術指定】\n- React Context API\n- useReducer\n- カスタムフック"
            ],
            [
                'id' => 18,
                'category' => 'フロントエンド',
                'order' => 18,
                'title' => 'SCSSでデザインシステムを作る',
                'status' => 'todo',
                'description' => "【作るもの】UIコンポーネントカタログ\n【機能要件】\n- Button / Input / Card / Badge / Modal\n【技術指定】\n- CSS Modules + SCSS\n- 変数・mixin・extend を活用\n- BEM命名規則"
            ],
            [
                'id' => 19,
                'category' => 'フロントエンド',
                'order' => 19,
                'title' => 'axiosでLaravel APIに接続する',
                'status' => 'todo',
                'description' => "【作るもの】タスク管理アプリ（API連携）\n【機能要件】\n- タスク一覧取得・追加・更新・削除\n- ログイン認証（Sanctum）\n【技術指定】\n- axios インスタンス（共通設定）\n- APIエラーのインターセプター"
            ],
            [
                'id' => 20,
                'category' => 'フロントエンド',
                'order' => 20,
                'title' => 'カスタムフックでロジックを分離する',
                'status' => 'todo',
                'description' => "【作るもの】リアルタイム検索アプリ\n【技術指定】\n- useDebounce / useFetch / useLocalStorage カスタムフック\n- コンポーネントからロジックを完全分離"
            ],
            [
                'id' => 21,
                'category' => 'フロントエンド',
                'order' => 21,
                'title' => 'パフォーマンス最適化をする',
                'status' => 'todo',
                'description' => "【作るもの】1000件の商品一覧アプリ\n【技術指定】\n- React.memo / useMemo / useCallback\n- 仮想スクロール（react-window）\n- Lighthouse 90点以上"
            ],
            [
                'id' => 22,
                'category' => 'フロントエンド',
                'order' => 22,
                'title' => 'ReactでドラッグandドロップUIを作る',
                'status' => 'todo',
                'description' => "【作るもの】カンバンボード（Trello風）\n【機能要件】\n- タスクを3列で管理\n- ドラッグで列間を移動\n【技術指定】\n- dnd-kit ライブラリ"
            ],
            [
                'id' => 23,
                'category' => 'フロントエンド',
                'order' => 23,
                'title' => 'アニメーションを実装する',
                'status' => 'todo',
                'description' => "【作るもの】アニメーション付きランディングページ\n【技術指定】\n- Framer Motion\n- Intersection Observer\n- prefers-reduced-motion 対応"
            ],
            [
                'id' => 24,
                'category' => 'フロントエンド',
                'order' => 24,
                'title' => 'TypeScriptに移行する',
                'status' => 'todo',
                'description' => "【作るもの】型安全なTodoアプリ\n【技術指定】\n- TypeScript（strict mode）\n- interface / type / generics\n- any 禁止"
            ],
            [
                'id' => 25,
                'category' => 'フロントエンド',
                'order' => 25,
                'title' => 'テストコードを書く',
                'status' => 'todo',
                'description' => "【作るもの】テスト付きフォームコンポーネント\n【技術指定】\n- Vitest + React Testing Library\n- MSW でAPIモック\n- カバレッジ80%以上"
            ],
            [
                'id' => 26,
                'category' => 'フロントエンド',
                'order' => 26,
                'title' => 'デザインカンプをコーディングする',
                'status' => 'todo',
                'description' => "【作るもの】FigmaデザインのHTML/CSS実装\n【技術指定】\n- Figma Dev Modeで数値確認\n- CSS変数でデザイントークン管理"
            ],
            [
                'id' => 27,
                'category' => 'フロントエンド',
                'order' => 27,
                'title' => 'PWAを実装する',
                'status' => 'todo',
                'description' => "【作るもの】オフライン対応Webアプリ\n【技術指定】\n- Service Worker\n- Web App Manifest\n- Lighthouse PWAスコア100点"
            ],
            [
                'id' => 28,
                'category' => 'フロントエンド',
                'order' => 28,
                'title' => 'SEO・アクセシビリティ対応をする',
                'status' => 'todo',
                'description' => "【作るもの】SEO最適化済みブログ\n【技術指定】\n- Next.js（SSG/SSR）\n- WAI-ARIAの適切な使用\n- Lighthouse 全項目90点以上"
            ],
            [
                'id' => 29,
                'category' => 'フロントエンド',
                'order' => 29,
                'title' => 'コードレビューを受ける',
                'status' => 'todo',
                'description' => "【課題内容】\n- ベストな成果物をGitHub PRにまとめる\n- レビューコメントに対応\n- 修正内容と学びをREADMEにまとめる"
            ],
            [
                'id' => 30,
                'category' => 'フロントエンド',
                'order' => 30,
                'title' => '卒業制作：ポートフォリオサイトを公開する',
                'status' => 'todo',
                'description' => "【作るもの】本番公開ポートフォリオ\n【技術指定】\n- React + TypeScript\n- Vercelにデプロイ\n- Lighthouse 全項目90点以上\n- OGP画像設定"
            ],
        ];
    }

    private function backendTasks(): array
    {
        return [
            [
                'id' => 101,
                'category' => 'サーバーサイド',
                'order' => 1,
                'title' => 'コマンドラインの基本操作をマスターする',
                'status' => 'todo',
                'description' => "【課題内容】\n- ファイル操作（ls/cd/mkdir/rm/cp/mv）\n- テキスト操作（cat/grep/sed/awk）\n- プロセス管理（ps/kill/top）\n- シェルスクリプトで自動化\n【提出物】\n- よく使うコマンド集をREADME.mdにまとめる\n- 「指定フォルダのファイルを日付別に整理する」シェルスクリプトを作成"
            ],
            [
                'id' => 102,
                'category' => 'サーバーサイド',
                'order' => 2,
                'title' => 'PHPの基礎文法を習得する',
                'status' => 'todo',
                'description' => "【作るもの】PHPで動く計算ツール\n【課題内容】\n- 変数・型・演算子\n- if/for/while/foreach\n- 関数定義と引数\n- 配列・連想配列の操作\n【技術指定】\n- PHP 8.x\n- FizzBuzzを書く"
            ],
            [
                'id' => 103,
                'category' => 'サーバーサイド',
                'order' => 3,
                'title' => 'PHPでオブジェクト指向を理解する',
                'status' => 'todo',
                'description' => "【作るもの】銀行口座クラス\n【機能要件】\n- 入金・出金・残高照会メソッド\n- 残高不足時の例外処理\n【技術指定】\n- クラス・プロパティ・メソッド\n- 継承・インターフェース\n- try/catch/finally"
            ],
            [
                'id' => 104,
                'category' => 'サーバーサイド',
                'order' => 4,
                'title' => 'MySQLの基本操作をマスターする',
                'status' => 'todo',
                'description' => "【作るもの】ユーザー管理DB\n【課題内容】\n- テーブル作成（CREATE TABLE）\n- CRUD操作（SELECT/INSERT/UPDATE/DELETE）\n- JOINで複数テーブル結合\n【技術指定】\n- DockerでMySQL環境構築\n- 外部キー制約の設定"
            ],
            [
                'id' => 105,
                'category' => 'サーバーサイド',
                'order' => 5,
                'title' => 'PHPとMySQLを連携させる',
                'status' => 'todo',
                'description' => "【作るもの】PHPで動く掲示板\n【機能要件】\n- 投稿の一覧表示・追加・削除\n- XSS対策（HTMLエスケープ）\n【技術指定】\n- PDO（プリペアドステートメント）\n- SQLインジェクション対策"
            ],
            [
                'id' => 106,
                'category' => 'サーバーサイド',
                'order' => 6,
                'title' => 'LaravelとDockerで開発環境を構築する',
                'status' => 'todo',
                'description' => "【作るもの】Laravel Sail環境\n【課題内容】\n- Docker + Laravel Sail で環境構築\n- Hello World APIを作成してPostmanで確認\n【技術指定】\n- Laravel 11.x + Sail\n- artisan route:list でルート確認"
            ],
            [
                'id' => 107,
                'category' => 'サーバーサイド',
                'order' => 7,
                'title' => 'MVC構造とルーティングを理解する',
                'status' => 'todo',
                'description' => "【作るもの】ブログAPIの骨格\n【技術指定】\n- routes/api.php でルーティング\n- Resource Controllerの使い方\n- Route::apiResource"
            ],
            [
                'id' => 108,
                'category' => 'サーバーサイド',
                'order' => 8,
                'title' => 'MigrationとEloquentでDB操作をする',
                'status' => 'todo',
                'description' => "【作るもの】投稿管理API\n【技術指定】\n- Migrationでテーブル作成\n- Eloquent ORM\n- Factoryで100件のテストデータ生成"
            ],
            [
                'id' => 109,
                'category' => 'サーバーサイド',
                'order' => 9,
                'title' => 'リレーションとeager loadingを使う',
                'status' => 'todo',
                'description' => "【作るもの】カテゴリ付きブログAPI\n【技術指定】\n- hasMany / belongsTo\n- with() でN+1問題を解消\n- whereHas() で関連テーブルを絞り込み"
            ],
            [
                'id' => 110,
                'category' => 'サーバーサイド',
                'order' => 10,
                'title' => 'バリデーションとエラーレスポンスを実装する',
                'status' => 'todo',
                'description' => "【作るもの】入力チェック付きユーザー登録API\n【技術指定】\n- FormRequestクラス\n- カスタムバリデーションルール\n- 日本語エラーメッセージ"
            ],
            [
                'id' => 111,
                'category' => 'サーバーサイド',
                'order' => 11,
                'title' => 'Sanctumで認証APIを実装する',
                'status' => 'todo',
                'description' => "【作るもの】ログイン機能付きAPI\n【技術指定】\n- Laravel Sanctum（SPA認証）\n- auth:sanctum ミドルウェア\n- Postmanで認証フローをテスト"
            ],
            [
                'id' => 112,
                'category' => 'サーバーサイド',
                'order' => 12,
                'title' => 'APIリソースでレスポンスを整形する',
                'status' => 'todo',
                'description' => "【作るもの】整形されたレスポンスのAPI\n【技術指定】\n- Laravel API Resource\n- ResourceCollection\n- whenLoaded() で条件付き読み込み"
            ],
            [
                'id' => 113,
                'category' => 'サーバーサイド',
                'order' => 13,
                'title' => 'ファイルアップロードを実装する',
                'status' => 'todo',
                'description' => "【作るもの】画像アップロードAPI\n【技術指定】\n- Laravel Storage Facade\n- Intervention Image でリサイズ\n- バリデーション（mimes/max）"
            ],
            [
                'id' => 114,
                'category' => 'サーバーサイド',
                'order' => 14,
                'title' => '検索・フィルタ・ソートAPIを作る',
                'status' => 'todo',
                'description' => "【作るもの】高機能な商品検索API\n【技術指定】\n- Eloquentスコープ\n- N+1問題の解消を確認"
            ],
            [
                'id' => 115,
                'category' => 'サーバーサイド',
                'order' => 15,
                'title' => 'Policyで権限管理を実装する',
                'status' => 'todo',
                'description' => "【作るもの】権限付き投稿管理API\n【技術指定】\n- Laravel Policy\n- Gate ファサード\n- 権限なしは403エラーを返す"
            ],
            [
                'id' => 116,
                'category' => 'サーバーサイド',
                'order' => 16,
                'title' => 'キューとジョブで非同期処理をする',
                'status' => 'todo',
                'description' => "【作るもの】メール非同期送信システム\n【技術指定】\n- Laravel Queue（database driver）\n- Mailableクラス\n- php artisan queue:work"
            ],
            [
                'id' => 117,
                'category' => 'サーバーサイド',
                'order' => 17,
                'title' => 'イベントとリスナーで疎結合設計をする',
                'status' => 'todo',
                'description' => "【作るもの】通知機能付き注文システム\n【技術指定】\n- Laravel Event / Listener\n- ShouldQueue インターフェース"
            ],
            [
                'id' => 118,
                'category' => 'サーバーサイド',
                'order' => 18,
                'title' => 'キャッシュでAPIを高速化する',
                'status' => 'todo',
                'description' => "【作るもの】キャッシュ付き商品API\n【技術指定】\n- Laravel Cache（Redis）\n- Cache::remember / forget / tags"
            ],
            [
                'id' => 119,
                'category' => 'サーバーサイド',
                'order' => 19,
                'title' => 'DBトランザクションで整合性を保つ',
                'status' => 'todo',
                'description' => "【作るもの】在庫管理付き注文API\n【技術指定】\n- DB::transaction()\n- 楽観的ロック（lockForUpdate）"
            ],
            [
                'id' => 120,
                'category' => 'サーバーサイド',
                'order' => 20,
                'title' => 'APIドキュメントを作成する',
                'status' => 'todo',
                'description' => "【作るもの】Swagger UIドキュメント\n【技術指定】\n- L5-Swagger\n- @OA アノテーション記法\n- /api/documentation でUI表示"
            ],
            [
                'id' => 121,
                'category' => 'サーバーサイド',
                'order' => 21,
                'title' => 'PHPUnitでFeatureテストを書く',
                'status' => 'todo',
                'description' => "【作るもの】テスト付きTODO API\n【技術指定】\n- PHPUnit + RefreshDatabase\n- Factory でデータ準備\n- カバレッジ80%以上"
            ],
            [
                'id' => 122,
                'category' => 'サーバーサイド',
                'order' => 22,
                'title' => 'リファクタリングしてコードを改善する',
                'status' => 'todo',
                'description' => "【課題内容】\n- Controllerを薄くする（Serviceクラス抽出）\n- 重複コードを排除\n- Controllerが200行以内"
            ],
            [
                'id' => 123,
                'category' => 'サーバーサイド',
                'order' => 23,
                'title' => 'セキュリティ対策を実装する',
                'status' => 'todo',
                'description' => "【課題内容】\n- SQLインジェクション・XSS・CSRF対策\n- レートリミット（API制限）\n- OWASP Top 10 の理解"
            ],
            [
                'id' => 124,
                'category' => 'サーバーサイド',
                'order' => 24,
                'title' => 'パフォーマンスチューニングをする',
                'status' => 'todo',
                'description' => "【課題内容】\n- スロークエリを特定して改善\n- DBインデックスを適切に設定\n- Redisでセッション・キャッシュを管理"
            ],
            [
                'id' => 125,
                'category' => 'サーバーサイド',
                'order' => 25,
                'title' => 'WebSocketでリアルタイム通信を実装する',
                'status' => 'todo',
                'description' => "【作るもの】リアルタイムチャットAPI\n【技術指定】\n- Laravel Broadcasting\n- Pusher または Soketi\n- Presence Channel"
            ],
            [
                'id' => 126,
                'category' => 'サーバーサイド',
                'order' => 26,
                'title' => '外部APIと連携するサービスを作る',
                'status' => 'todo',
                'description' => "【作るもの】決済機能付きAPIサービス\n【技術指定】\n- Stripe PHP SDK\n- Webhook署名の検証\n- 二重課金防止"
            ],
            [
                'id' => 127,
                'category' => 'サーバーサイド',
                'order' => 27,
                'title' => 'マイクロサービスの基礎を理解する',
                'status' => 'todo',
                'description' => "【作るもの】API Gateway + 2サービス構成\n【技術指定】\n- 各サービスを別々のLaravelプロジェクトで\n- サービス間のJWT認証"
            ],
            [
                'id' => 128,
                'category' => 'サーバーサイド',
                'order' => 28,
                'title' => 'GraphQL APIを実装する',
                'status' => 'todo',
                'description' => "【作るもの】GraphQL対応ブログAPI\n【技術指定】\n- Lighthouse（Laravel GraphQL）\n- DataLoader でバッチ取得"
            ],
            [
                'id' => 129,
                'category' => 'サーバーサイド',
                'order' => 29,
                'title' => 'コードレビューを受ける',
                'status' => 'todo',
                'description' => "【課題内容】\n- ベストな成果物をGitHub PRにまとめる\n- SOLID原則の遵守\n- テストカバレッジ"
            ],
            [
                'id' => 130,
                'category' => 'サーバーサイド',
                'order' => 30,
                'title' => '卒業制作：ECサイトバックエンドAPIを作る',
                'status' => 'todo',
                'description' => "【作るもの】本格的なECサイトAPI\n【技術指定】\n- 全エンドポイントにFeatureテスト\n- Swagger UIでドキュメント化\n- GitHub Actionsで自動テスト"
            ],
        ];
    }

    private function infraTasks(): array
    {
        return [
            [
                'id' => 201,
                'category' => 'インフラ',
                'order' => 1,
                'title' => 'Linuxコマンドをマスターする',
                'status' => 'todo',
                'description' => "【課題内容】\n- ファイル操作・権限管理・プロセス管理\n- パイプ・リダイレクト・環境変数\n- ネットワーク確認（ping/curl/netstat）\n- cronで定期実行\n【提出物】\n- よく使うコマンド100選をまとめたREADME\n- サーバーの死活監視シェルスクリプト"
            ],
            [
                'id' => 202,
                'category' => 'インフラ',
                'order' => 2,
                'title' => 'Dockerの基礎を理解する',
                'status' => 'todo',
                'description' => "【作るもの】Nginx + PHPのコンテナ環境\n【技術指定】\n- docker pull/run/build/ps/logs/exec\n- Dockerfile（FROM/RUN/COPY/CMD）\n- ポートマッピング・ボリュームマウント"
            ],
            [
                'id' => 203,
                'category' => 'インフラ',
                'order' => 3,
                'title' => 'Docker Composeで複数コンテナを管理する',
                'status' => 'todo',
                'description' => "【作るもの】Laravel + MySQL + Redis 環境\n【技術指定】\n- docker-compose.yml\n- サービス間通信\n- depends_on で起動順序制御"
            ],
            [
                'id' => 204,
                'category' => 'インフラ',
                'order' => 4,
                'title' => 'Nginxを設定する',
                'status' => 'todo',
                'description' => "【作るもの】Nginx + PHP-FPM 本番設定\n【技術指定】\n- nginx.conf（server/location ブロック）\n- upstream で PHP-FPM に接続\n- Gzip圧縮で転送量削減"
            ],
            [
                'id' => 205,
                'category' => 'インフラ',
                'order' => 5,
                'title' => 'Git/GitHubでチーム開発の流れを理解する',
                'status' => 'todo',
                'description' => "【課題内容】\n- ブランチ戦略（Git Flow）の実践\n- PRを作ってセルフレビュー\n- コンフリクト解消の練習\n【技術指定】\n- git branch/checkout/merge/rebase\n- conventional commits"
            ],
            [
                'id' => 206,
                'category' => 'インフラ',
                'order' => 6,
                'title' => 'VPSにLaravelを手動デプロイする',
                'status' => 'todo',
                'description' => "【作るもの】VPS上で動くLaravelアプリ\n【技術指定】\n- SSH鍵認証\n- Nginx + PHP + MySQL + Composer\n- ファイルパーミッションの設定"
            ],
            [
                'id' => 207,
                'category' => 'インフラ',
                'order' => 7,
                'title' => 'GitHub ActionsでCIパイプラインを作る',
                'status' => 'todo',
                'description' => "【作るもの】自動テスト環境\n【技術指定】\n- .github/workflows/ci.yml\n- services（MySQL）でのテスト環境\n- キャッシュで実行時間を短縮"
            ],
            [
                'id' => 208,
                'category' => 'インフラ',
                'order' => 8,
                'title' => 'GitHub ActionsでCDパイプラインを作る',
                'status' => 'todo',
                'description' => "【作るもの】自動デプロイ環境\n【技術指定】\n- SSH action でリモートデプロイ\n- Secrets で機密情報管理\n- zero-downtime deploy"
            ],
            [
                'id' => 209,
                'category' => 'インフラ',
                'order' => 9,
                'title' => 'AWSの基礎サービスを理解する',
                'status' => 'todo',
                'description' => "【課題内容】\n- AWSの主要サービスの役割を説明\n- EC2インスタンスを立てる\n- S3にファイルをアップロード\n- IAMでユーザーと権限を管理"
            ],
            [
                'id' => 210,
                'category' => 'インフラ',
                'order' => 10,
                'title' => 'EC2にLaravelをデプロイする',
                'status' => 'todo',
                'description' => "【作るもの】AWS EC2上の本番環境\n【技術指定】\n- EC2（t2.micro 無料枠）\n- Elastic IPで固定IP\n- Route53でカスタムドメイン設定"
            ],
            [
                'id' => 211,
                'category' => 'インフラ',
                'order' => 11,
                'title' => 'RDSでMySQLを本番運用する',
                'status' => 'todo',
                'description' => "【作るもの】マネージドDB環境\n【技術指定】\n- RDS MySQL 8.x\n- 自動バックアップ（7日間保持）\n- パフォーマンスインサイト"
            ],
            [
                'id' => 212,
                'category' => 'インフラ',
                'order' => 12,
                'title' => 'S3で静的ファイルを管理する',
                'status' => 'todo',
                'description' => "【作るもの】S3 + CloudFrontの画像配信\n【技術指定】\n- AWS SDK for PHP\n- CloudFrontで高速配信\n- 署名付きURLでプライベートファイル配信"
            ],
            [
                'id' => 213,
                'category' => 'インフラ',
                'order' => 13,
                'title' => 'HTTPS化とSSL証明書を設定する',
                'status' => 'todo',
                'description' => "【作るもの】SSL/TLS対応サーバー\n【技術指定】\n- Certbot（Let's Encrypt）\n- cron jobで certbot renew\n- SSL Labs でA+評価"
            ],
            [
                'id' => 214,
                'category' => 'インフラ',
                'order' => 14,
                'title' => 'CloudWatchで監視・アラートを設定する',
                'status' => 'todo',
                'description' => "【作るもの】サーバー監視システム\n【技術指定】\n- CloudWatch Agent\n- CloudWatch Alarm\n- SNS → Lambda → Slack通知"
            ],
            [
                'id' => 215,
                'category' => 'インフラ',
                'order' => 15,
                'title' => 'Terraformでインフラをコード化する',
                'status' => 'todo',
                'description' => "【作るもの】TerraformでAWS環境を自動構築\n【技術指定】\n- Terraform基本構文\n- variable / output / module\n- terraform plan で差分確認"
            ],
            [
                'id' => 216,
                'category' => 'インフラ',
                'order' => 16,
                'title' => 'Ansibleでサーバー設定を自動化する',
                'status' => 'todo',
                'description' => "【作るもの】サーバー初期設定の自動化\n【技術指定】\n- Playbook/Role/Task の構造\n- vault で機密情報暗号化"
            ],
            [
                'id' => 217,
                'category' => 'インフラ',
                'order' => 17,
                'title' => 'Dockerイメージを最適化する',
                'status' => 'todo',
                'description' => "【作るもの】軽量なLaravelコンテナ\n【技術指定】\n- Alpine Linux ベースイメージ\n- マルチステージビルド\n- Trivy でイメージスキャン"
            ],
            [
                'id' => 218,
                'category' => 'インフラ',
                'order' => 18,
                'title' => 'ECSでコンテナをAWSで動かす',
                'status' => 'todo',
                'description' => "【作るもの】ECS Fargate上のLaravelアプリ\n【技術指定】\n- ECR（コンテナレジストリ）\n- ECS タスク定義・サービス設定\n- Auto Scaling"
            ],
            [
                'id' => 219,
                'category' => 'インフラ',
                'order' => 19,
                'title' => 'Kubernetesの基礎を理解する',
                'status' => 'todo',
                'description' => "【作るもの】minikube上のLaravelアプリ\n【技術指定】\n- kubectl基本コマンド\n- YAML マニフェストファイル\n- rolling updateで無停止デプロイ"
            ],
            [
                'id' => 220,
                'category' => 'インフラ',
                'order' => 20,
                'title' => 'DBバックアップと障害復旧を実装する',
                'status' => 'todo',
                'description' => "【作るもの】自動バックアップシステム\n【技術指定】\n- mysqldump スクリプト\n- cron job\n- AWS CLIでS3アップロード"
            ],
            [
                'id' => 221,
                'category' => 'インフラ',
                'order' => 21,
                'title' => 'WAFとセキュリティ対策を実装する',
                'status' => 'todo',
                'description' => "【課題内容】\n- AWS WAFでSQLインジェクション・XSSをブロック\n- GuardDutyで不正アクセスを検知\n- CloudTrailで操作ログ記録"
            ],
            [
                'id' => 222,
                'category' => 'インフラ',
                'order' => 22,
                'title' => 'コスト最適化をする',
                'status' => 'todo',
                'description' => "【課題内容】\n- Cost Explorer で分析\n- 使っていないリソースを削除\n- Budget Alert で予算超過防止"
            ],
            [
                'id' => 223,
                'category' => 'インフラ',
                'order' => 23,
                'title' => 'マルチAZ構成で高可用性を実現する',
                'status' => 'todo',
                'description' => "【作るもの】障害に強いインフラ\n【技術指定】\n- ALB + Auto Scaling Group\n- RDS Multi-AZ\n- Route53 ヘルスチェック"
            ],
            [
                'id' => 224,
                'category' => 'インフラ',
                'order' => 24,
                'title' => 'サービスメッシュを理解する',
                'status' => 'todo',
                'description' => "【作るもの】Istio を使ったマイクロサービス環境\n【技術指定】\n- Kiali でサービス間の依存関係を可視化\n- Prometheus + Grafana\n- Jaeger でトレーシング"
            ],
            [
                'id' => 225,
                'category' => 'インフラ',
                'order' => 25,
                'title' => 'SREの基礎を実践する',
                'status' => 'todo',
                'description' => "【課題内容】\n- SLI/SLO/エラーバジェットを定義\n- インシデント対応手順書を作成\n【技術指定】\n- Prometheus でSLIを計測\n- Grafana でSLOダッシュボード作成"
            ],
            [
                'id' => 226,
                'category' => 'インフラ',
                'order' => 26,
                'title' => 'セキュリティ診断を実施する',
                'status' => 'todo',
                'description' => "【課題内容】\n- OWASP ZAPで脆弱性スキャン\n- Trivy でコンテナイメージのスキャン\n- CIS Benchmark 80%以上準拠"
            ],
            [
                'id' => 227,
                'category' => 'インフラ',
                'order' => 27,
                'title' => 'パフォーマンステストをする',
                'status' => 'todo',
                'description' => "【作るもの】負荷テスト環境\n【技術指定】\n- k6 で負荷テストシナリオ作成\n- 改善前後のスループット比較"
            ],
            [
                'id' => 228,
                'category' => 'インフラ',
                'order' => 28,
                'title' => '本番グレードのCI/CDパイプラインを構築する',
                'status' => 'todo',
                'description' => "【作るもの】完全自動化されたデプロイ環境\n【技術指定】\n- Blue/Greenデプロイ\n- カナリアリリース\n- デプロイ失敗時は自動ロールバック"
            ],
            [
                'id' => 229,
                'category' => 'インフラ',
                'order' => 29,
                'title' => 'コードレビューを受ける',
                'status' => 'todo',
                'description' => "【課題内容】\n- Terraform・Dockerfileなど成果物をGitHub PRにまとめる\n- セキュリティ・コスト・可用性の観点でレビュー"
            ],
            [
                'id' => 230,
                'category' => 'インフラ',
                'order' => 30,
                'title' => '卒業制作：本番グレードのインフラを構築する',
                'status' => 'todo',
                'description' => "【作るもの】全構成をコードで管理する本番インフラ\n【技術指定】\n- 全AWSリソースをTerraformで管理\n- ECS Fargateでコンテナ運用\n- アーキテクチャ構成図を作成"
            ],
        ];
    }
}
