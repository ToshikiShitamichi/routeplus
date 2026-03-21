<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // TaskController.php の allTasks() 内容を差し替え

    private function frontendTasks(): array
    {
        return [
            // ── LEVEL 1: Web の仕組み（1〜5）──
            [
                'id' => 1,
                'category' => 'フロントエンド',
                'order' => 1,
                'level' => 1,
                'title' => 'Webの仕組みを理解する',
                'description' => "【作るもの】調査レポート（Markdownファイル）\n【課題内容】\n- ブラウザにURLを入力してから画面が表示されるまでの流れを図解する\n- HTTP/HTTPSの違いを説明する\n- HTMLとCSS・JavaScriptの役割をそれぞれ説明する\n- サーバーとクライアントの違いを説明する\n【提出物】\n- GitHub上のREADME.mdに図と説明をまとめる",
                'status' => 'todo'
            ],

            [
                'id' => 2,
                'category' => 'フロントエンド',
                'order' => 2,
                'level' => 1,
                'title' => 'HTMLで自己紹介ページを作る',
                'description' => "【作るもの】静的HTMLページ\n【機能要件】\n- 名前・出身・趣味を表示\n- 箇条書きリストでスキルを列挙\n- リンクで外部サイトに遷移\n【技術指定】\n- HTMLのみ（CSSなし）\n- h1/h2/p/ul/li/a/img タグを全て使う\n- W3C バリデーターでエラーなし",
                'status' => 'todo'
            ],

            [
                'id' => 3,
                'category' => 'フロントエンド',
                'order' => 3,
                'level' => 1,
                'title' => 'CSSでページをデザインする',
                'description' => "【作るもの】CSS付き自己紹介ページ\n【機能要件】\n- フォント・色・余白を整える\n- ヘッダー・メイン・フッターのレイアウト\n- ホバー時にリンクの色が変わる\n【技術指定】\n- 外部CSSファイルで記述\n- セレクタ・プロパティ・ボックスモデルを理解して使う\n- コメントで各スタイルの意図を記述",
                'status' => 'todo'
            ],

            [
                'id' => 4,
                'category' => 'フロントエンド',
                'order' => 4,
                'level' => 1,
                'title' => 'Flexboxでナビゲーションバーを作る',
                'description' => "【作るもの】レスポンシブなナビゲーションバー\n【機能要件】\n- ロゴ（左）とメニュー（右）を横並び\n- メニュー項目は4つ以上\n- スマホ幅ではメニューを縦並びに\n【技術指定】\n- Flexboxのみでレイアウト\n- justify-content / align-items / flex-wrap を使う\n- メディアクエリで768px以下に対応",
                'status' => 'todo'
            ],

            [
                'id' => 5,
                'category' => 'フロントエンド',
                'order' => 5,
                'level' => 1,
                'title' => 'CSS Gridでポートフォリオギャラリーを作る',
                'description' => "【作るもの】作品ギャラリーページ\n【機能要件】\n- 作品カードを3列グリッドで表示\n- カードにホバーで拡大エフェクト\n- スマホでは1列に変更\n【技術指定】\n- CSS Gridでレイアウト\n- grid-template-columns / gap を使う\n- transform: scale() でホバーエフェクト",
                'status' => 'todo'
            ],

            // ── LEVEL 2: JavaScript 基礎（6〜12）──
            [
                'id' => 6,
                'category' => 'フロントエンド',
                'order' => 6,
                'level' => 2,
                'title' => 'JavaScriptで電卓を作る',
                'description' => "【作るもの】ブラウザで動く電卓\n【機能要件】\n- 四則演算（+ - × ÷）\n- 小数点・クリア・バックスペース\n- キーボード入力にも対応\n【技術指定】\n- バニラJS（フレームワークなし）\n- querySelector / addEventListener を使う\n- eval() は使わず自前で計算ロジックを書く",
                'status' => 'todo'
            ],

            [
                'id' => 7,
                'category' => 'フロントエンド',
                'order' => 7,
                'level' => 2,
                'title' => '配列とループでクイズアプリを作る',
                'description' => "【作るもの】10問クイズアプリ\n【機能要件】\n- 問題を1問ずつ表示\n- 4択の回答ボタン\n- 正解・不正解のフィードバック\n- 最後にスコアを表示\n【技術指定】\n- 問題データを配列で管理\n- forEach / map / filter を使う\n- DOM操作でUIを更新",
                'status' => 'todo'
            ],

            [
                'id' => 8,
                'category' => 'フロントエンド',
                'order' => 8,
                'level' => 2,
                'title' => 'LocalStorageでTodoアプリを作る',
                'description' => "【作るもの】データが消えないTodoアプリ\n【機能要件】\n- Todo追加・削除・完了チェック\n- 完了/未完了フィルター\n- ページリロードしてもデータが残る\n【技術指定】\n- LocalStorage（JSON.stringify/parse）\n- クラスを使ってTodoオブジェクトを管理\n- イベント委任（event delegation）",
                'status' => 'todo'
            ],

            [
                'id' => 9,
                'category' => 'フロントエンド',
                'order' => 9,
                'level' => 2,
                'title' => 'Fetch APIで天気アプリを作る',
                'description' => "【作るもの】リアルタイム天気表示アプリ\n【機能要件】\n- 都市名を入力して現在の天気を取得\n- 気温・天気・湿度・風速を表示\n- 読み込み中・エラー状態を表示\n【技術指定】\n- Fetch API（async/await）\n- OpenWeatherMap API（無料）\n- try/catchでエラーハンドリング",
                'status' => 'todo'
            ],

            [
                'id' => 10,
                'category' => 'フロントエンド',
                'order' => 10,
                'level' => 2,
                'title' => '非同期処理とPromiseを理解する',
                'description' => "【作るもの】GitHub ユーザー検索アプリ\n【機能要件】\n- ユーザー名検索でプロフィール表示\n- リポジトリ一覧を表示\n- 入力から500ms後に検索（デバウンス）\n【技術指定】\n- Promise / async / await の使い分け\n- Promise.all() で並列リクエスト\n- デバウンス関数を自作",
                'status' => 'todo'
            ],

            [
                'id' => 11,
                'category' => 'フロントエンド',
                'order' => 11,
                'level' => 2,
                'title' => 'モジュール分割でコードを整理する',
                'description' => "【作るもの】モジュール化されたショッピングカート\n【機能要件】\n- 商品一覧・カート・合計金額\n- 商品追加・削除・数量変更\n- 税込み計算\n【技術指定】\n- ES Modules（import/export）\n- 機能ごとにファイル分割（cart.js / products.js）\n- Viteで開発環境構築",
                'status' => 'todo'
            ],

            [
                'id' => 12,
                'category' => 'フロントエンド',
                'order' => 12,
                'level' => 2,
                'title' => 'Git/GitHubでバージョン管理をする',
                'description' => "【作るもの】これまでの作品をGitHubで公開\n【課題内容】\n- ローカルリポジトリ作成 → GitHubにpush\n- ブランチを切って機能追加 → マージ\n- GitHub Pagesで公開\n- READMEに作品の説明を書く\n【技術指定】\n- git init/add/commit/push/pull\n- ブランチ戦略（feature/develop/main）\n- プルリクエストを作ってセルフマージ",
                'status' => 'todo'
            ],

            // ── LEVEL 3: React 基礎（13〜19）──
            [
                'id' => 13,
                'category' => 'フロントエンド',
                'order' => 13,
                'level' => 3,
                'title' => 'Reactでカウンターアプリを作る',
                'description' => "【作るもの】はじめてのReactアプリ\n【機能要件】\n- ＋／－ボタンでカウントアップ・ダウン\n- リセットボタン\n- カウントが0未満にならないように制御\n【技術指定】\n- Vite + React\n- 関数コンポーネント\n- useState フック\n- props でコンポーネントに値を渡す",
                'status' => 'todo'
            ],

            [
                'id' => 14,
                'category' => 'フロントエンド',
                'order' => 14,
                'level' => 3,
                'title' => 'useEffectでデータ取得アプリを作る',
                'description' => "【作るもの】ポケモン図鑑アプリ\n【機能要件】\n- 一覧ページ（最初の20匹）\n- クリックで詳細表示（タイプ・能力値）\n- ページネーション（次の20匹を読み込む）\n【技術指定】\n- useEffect でAPIフェッチ\n- PokeAPI（無料）を使用\n- ローディング・エラー状態の管理\n- コンポーネント分割（List / Card / Detail）",
                'status' => 'todo'
            ],

            [
                'id' => 15,
                'category' => 'フロントエンド',
                'order' => 15,
                'level' => 3,
                'title' => 'React Routerでマルチページアプリを作る',
                'description' => "【作るもの】レシピサイト\n【機能要件】\n- トップ（レシピ一覧）\n- レシピ詳細ページ\n- カテゴリ別一覧ページ\n- 404ページ\n【技術指定】\n- React Router v6\n- useParams / useNavigate / Link\n- ネストルーティング\n- レシピデータはJSONファイルで管理",
                'status' => 'todo'
            ],

            [
                'id' => 16,
                'category' => 'フロントエンド',
                'order' => 16,
                'level' => 3,
                'title' => 'フォームバリデーションを実装する',
                'description' => "【作るもの】会員登録フォーム\n【機能要件】\n- 名前・メール・パスワード・確認パスワード\n- リアルタイムバリデーション\n- 送信前の最終チェック\n- エラーメッセージの表示\n【技術指定】\n- React Hook Form\n- バリデーションルール自作\n- 送信成功時のトースト通知",
                'status' => 'todo'
            ],

            [
                'id' => 17,
                'category' => 'フロントエンド',
                'order' => 17,
                'level' => 3,
                'title' => 'Context APIでグローバル状態管理をする',
                'description' => "【作るもの】テーマ切り替え付きメモアプリ\n【機能要件】\n- ライト/ダークモード切り替え\n- メモの追加・削除・検索\n- ログイン状態の管理（モック）\n【技術指定】\n- React Context API\n- useReducer で複雑な状態管理\n- カスタムフック（useTheme / useMemos）",
                'status' => 'todo'
            ],

            [
                'id' => 18,
                'category' => 'フロントエンド',
                'order' => 18,
                'level' => 3,
                'title' => 'SCSSでデザインシステムを作る',
                'description' => "【作るもの】UIコンポーネントカタログ\n【機能要件】\n- Button / Input / Card / Badge / Modal\n- カラーパレット・タイポグラフィの定義\n- コンポーネントの使用例ページ\n【技術指定】\n- CSS Modules + SCSS\n- 変数・mixin・extend を活用\n- BEM命名規則\n- レスポンシブ対応",
                'status' => 'todo'
            ],

            [
                'id' => 19,
                'category' => 'フロントエンド',
                'order' => 19,
                'level' => 3,
                'title' => 'axiosでLaravel APIに接続する',
                'description' => "【作るもの】タスク管理アプリ（API連携）\n【機能要件】\n- タスク一覧取得・追加・更新・削除\n- ログイン認証（Sanctum）\n- エラー時のメッセージ表示\n【技術指定】\n- axios インスタンス（共通設定）\n- 認証トークンの管理\n- APIエラーのインターセプター",
                'status' => 'todo'
            ],

            // ── LEVEL 4: React 応用（20〜25）──
            [
                'id' => 20,
                'category' => 'フロントエンド',
                'order' => 20,
                'level' => 4,
                'title' => 'カスタムフックでロジックを分離する',
                'description' => "【作るもの】リアルタイム検索アプリ\n【機能要件】\n- 入力と同時に候補を表示\n- デバウンス処理（300ms）\n- 検索履歴の保存\n【技術指定】\n- useDebounce / useFetch / useLocalStorage カスタムフック\n- コンポーネントからロジックを完全分離\n- 再利用可能な設計",
                'status' => 'todo'
            ],

            [
                'id' => 21,
                'category' => 'フロントエンド',
                'order' => 21,
                'level' => 4,
                'title' => 'パフォーマンス最適化をする',
                'description' => "【作るもの'=>'1000件の商品一覧アプリ\n【機能要件】\n- スクロールがスムーズ（60fps維持）\n- 検索・フィルタが高速\n- 画像遅延読み込み\n【技術指定】\n- React.memo / useMemo / useCallback\n- 仮想スクロール（react-window）\n- Chrome DevTools Performanceパネルで計測\n- Lighthouse 90点以上",
                'status' => 'todo'
            ],

            [
                'id' => 22,
                'category' => 'フロントエンド',
                'order' => 22,
                'level' => 4,
                'title' => 'ReactでドラッグandドロップUIを作る',
                'description' => "【作るもの】カンバンボード（Trello風）\n【機能要件】\n- タスクを3列（未着手/進行中/完了）で管理\n- ドラッグで列間を移動\n- タスクの追加・編集・削除\n【技術指定】\n- dnd-kit ライブラリ\n- optimistic update（即時UI反映）\n- LocalStorageで状態保存",
                'status' => 'todo'
            ],

            [
                'id' => 23,
                'category' => 'フロントエンド',
                'order' => 23,
                'level' => 4,
                'title' => 'アニメーションを実装する',
                'description' => "【作るもの】アニメーション付きランディングページ\n【機能要件】\n- スクロールで要素がフェードイン\n- ボタンホバーで波紋エフェクト\n- ページ遷移アニメーション\n【技術指定】\n- Framer Motion\n- Intersection Observer\n- CSS @keyframes との使い分け\n- prefers-reduced-motion 対応",
                'status' => 'todo'
            ],

            [
                'id' => 24,
                'category' => 'フロントエンド',
                'order' => 24,
                'level' => 4,
                'title' => 'TypeScriptに移行する',
                'description' => "【作るもの】型安全なTodoアプリ\n【機能要件】\n- TypeScriptで全ファイルを記述\n- 型エラーが0件\n- Props/State/APIレスポンスに型定義\n【技術指定】\n- TypeScript（strict mode）\n- interface / type / generics\n- any 禁止\n- ESLint + TypeScript設定",
                'status' => 'todo'
            ],

            [
                'id' => 25,
                'category' => 'フロントエンド',
                'order' => 25,
                'level' => 4,
                'title' => 'テストコードを書く',
                'description' => "【作るもの】テスト付きフォームコンポーネント\n【機能要件】\n- ユニットテスト（ロジック）\n- 統合テスト（ユーザー操作）\n- カバレッジ80%以上\n【技術指定】\n- Vitest + React Testing Library\n- userEvent でユーザー操作をシミュレート\n- MSW でAPIモック\n- GitHub Actionsで自動テスト",
                'status' => 'todo'
            ],

            // ── LEVEL 5: 卒業制作（26〜30）──
            [
                'id' => 26,
                'category' => 'フロントエンド',
                'order' => 26,
                'level' => 5,
                'title' => 'デザインカンプをコーディングする',
                'description' => "【作るもの】FigmaデザインのHTML/CSS実装\n【課題内容】\n- 提供されたFigmaデザイン通りに実装\n- ピクセルパーフェクトを目指す\n- スマホ・タブレット・PCに対応\n【技術指定】\n- Figma Dev Modeで数値確認\n- CSS変数でデザイントークン管理\n- 1pxのズレを意識した実装",
                'status' => 'todo'
            ],

            [
                'id' => 27,
                'category' => 'フロントエンド',
                'order' => 27,
                'level' => 5,
                'title' => 'PWAを実装する',
                'description' => "【作るもの】オフライン対応Webアプリ\n【機能要件】\n- スマホのホーム画面にインストール可能\n- オフラインでも動作\n- プッシュ通知\n【技術指定】\n- Service Worker\n- Web App Manifest\n- Cache API\n- Lighthouse PWAスコア100点",
                'status' => 'todo'
            ],

            [
                'id' => 28,
                'category' => 'フロントエンド',
                'order' => 28,
                'level' => 5,
                'title' => 'SEO・アクセシビリティ対応をする',
                'description' => "【作るもの】SEO最適化済みブログ\n【機能要件】\n- OGP/Twitterカード設定\n- 構造化データ（JSON-LD）\n- スクリーンリーダー対応\n- キーボード操作完全対応\n【技術指定】\n- Next.js（SSG/SSR）\n- WAI-ARIAの適切な使用\n- axeでアクセシビリティチェック\n- Lighthouse 全項目90点以上",
                'status' => 'todo'
            ],

            [
                'id' => 29,
                'category' => 'フロントエンド',
                'order' => 29,
                'level' => 5,
                'title' => 'コードレビューを受ける',
                'description' => "【課題内容】\n- これまでの成果物からベストのコードを選ぶ\n- GitHubのPRにまとめる\n- レビューコメントに対応して修正\n- 修正内容と学びをREADMEにまとめる\n【評価観点】\n- 可読性（変数名・関数名・コメント）\n- 再利用性（コンポーネント設計）\n- パフォーマンス\n- アクセシビリティ",
                'status' => 'todo'
            ],

            [
                'id' => 30,
                'category' => 'フロントエンド',
                'order' => 30,
                'level' => 5,
                'title' => '卒業制作：ポートフォリオサイトを公開する',
                'description' => "【作るもの】本番公開ポートフォリオ\n【機能要件】\n- トップ・スキル・制作物・お問い合わせ\n- 制作物は実際に動くデモリンクつき\n- お問い合わせフォーム（メール送信）\n- Google Analytics 設置\n【技術指定】\n- React + TypeScript\n- Vercelにデプロイ（独自ドメイン推奨）\n- Lighthouse 全項目90点以上\n- OGP画像設定",
                'status' => 'todo'
            ],
        ];
    }

    private function backendTasks(): array
    {
        return [
            // ── LEVEL 1: プログラミング基礎（1〜5）──
            [
                'id' => 101,
                'category' => 'サーバーサイド',
                'order' => 1,
                'level' => 1,
                'title' => 'コマンドラインの基本操作をマスターする',
                'description' => "【課題内容】\n- ファイル操作（ls/cd/mkdir/rm/cp/mv）\n- テキスト操作（cat/grep/sed/awk）\n- プロセス管理（ps/kill/top）\n- シェルスクリプトで自動化\n【提出物】\n- よく使うコマンド集をREADME.mdにまとめる\n- 「指定フォルダのファイルを日付別に整理する」シェルスクリプトを作成",
                'status' => 'todo'
            ],

            [
                'id' => 102,
                'category' => 'サーバーサイド',
                'order' => 2,
                'level' => 1,
                'title' => 'PHPの基礎文法を習得する',
                'description' => "【作るもの】PHPで動く計算ツール\n【課題内容】\n- 変数・型・演算子\n- if/for/while/foreach\n- 関数定義と引数\n- 配列・連想配列の操作\n【技術指定】\n- PHP 8.x\n- コマンドラインで実行（php xxx.php）\n- FizzBuzzを書く\n- 配列の並び替え・検索・集計",
                'status' => 'todo'
            ],

            [
                'id' => 103,
                'category' => 'サーバーサイド',
                'order' => 3,
                'level' => 1,
                'title' => 'PHPでオブジェクト指向を理解する',
                'description' => "【作るもの】銀行口座クラス\n【機能要件】\n- 入金・出金・残高照会メソッド\n- 残高不足時の例外処理\n- 取引履歴の管理\n【技術指定】\n- クラス・プロパティ・メソッド\n- コンストラクタ・デストラクタ\n- 継承・インターフェース\n- try/catch/finally",
                'status' => 'todo'
            ],

            [
                'id' => 104,
                'category' => 'サーバーサイド',
                'order' => 4,
                'level' => 1,
                'title' => 'MySQLの基本操作をマスターする',
                'description' => "【作るもの】ユーザー管理DB\n【課題内容】\n- テーブル作成（CREATE TABLE）\n- CRUD操作（SELECT/INSERT/UPDATE/DELETE）\n- JOINで複数テーブル結合\n- インデックスで検索高速化\n【技術指定】\n- MySQL 8.x\n- DockerでMySQL環境構築\n- phpMyAdminで操作確認\n- 外部キー制約の設定",
                'status' => 'todo'
            ],

            [
                'id' => 105,
                'category' => 'サーバーサイド',
                'order' => 5,
                'level' => 1,
                'title' => 'PHPとMySQLを連携させる',
                'description' => "【作るもの】PHPで動く掲示板\n【機能要件】\n- 投稿の一覧表示・追加・削除\n- ページネーション\n- XSS対策（HTMLエスケープ）\n【技術指定】\n- PDO（プリペアドステートメント）\n- SQLインジェクション対策\n- パスワードのハッシュ化\n- セッションでログイン状態管理",
                'status' => 'todo'
            ],

            // ── LEVEL 2: Laravel 基礎（6〜12）──
            [
                'id' => 106,
                'category' => 'サーバーサイド',
                'order' => 6,
                'level' => 2,
                'title' => 'LaravelとDockerで開発環境を構築する',
                'description' => "【作るもの】Laravel Sail環境\n【課題内容】\n- Docker + Laravel Sail で環境構築\n- .envの設定\n- php artisan コマンドの理解\n- Hello World APIを作成してPostmanで確認\n【技術指定】\n- Laravel 11.x + Sail\n- docker-compose.yml の読み方\n- artisan route:list でルート確認",
                'status' => 'todo'
            ],

            [
                'id' => 107,
                'category' => 'サーバーサイド',
                'order' => 7,
                'level' => 2,
                'title' => 'MVC構造とルーティングを理解する',
                'description' => "【作るもの】ブログAPIの骨格\n【機能要件】\n- GET /api/posts → 記事一覧\n- GET /api/posts/{id} → 記事詳細\n- 各ルートが適切なControllerに繋がる\n【技術指定】\n- routes/api.php でルーティング\n- Resource Controllerの使い方\n- Route::apiResource\n- artisan route:list で全ルート確認",
                'status' => 'todo'
            ],

            [
                'id' => 108,
                'category' => 'サーバーサイド',
                'order' => 8,
                'level' => 2,
                'title' => 'MigrationとEloquentでDB操作をする',
                'description' => "【作るもの】投稿管理API\n【機能要件】\n- postsテーブル（id/title/body/created_at）\n- 投稿のCRUD API\n- Seederでテストデータ投入\n【技術指定】\n- Migrationでテーブル作成\n- Eloquent ORM（create/find/update/delete）\n- Factoryで100件のテストデータ生成\n- tinkerで動作確認",
                'status' => 'todo'
            ],

            [
                'id' => 109,
                'category' => 'サーバーサイド',
                'order' => 9,
                'level' => 2,
                'title' => 'リレーションと eager loading を使う',
                'description' => "【作るもの】カテゴリ付きブログAPI\n【機能要件】\n- 投稿とカテゴリのリレーション\n- カテゴリ別記事一覧\n- コメント機能（投稿に複数のコメント）\n【技術指定】\n- hasMany / belongsTo / hasOne\n- with() で N+1問題を解消\n- whereHas() で関連テーブルを絞り込み",
                'status' => 'todo'
            ],

            [
                'id' => 110,
                'category' => 'サーバーサイド',
                'order' => 10,
                'level' => 2,
                'title' => 'バリデーションとエラーレスポンスを実装する',
                'description' => "【作るもの】入力チェック付きユーザー登録API\n【機能要件】\n- 名前（必須・最大50文字）\n- メール（必須・形式・重複不可）\n- パスワード（8文字以上）\n- エラー時は422と日本語メッセージを返す\n【技術指定】\n- FormRequestクラス\n- カスタムバリデーションルール\n- ja.phpで日本語化",
                'status' => 'todo'
            ],

            [
                'id' => 111,
                'category' => 'サーバーサイド',
                'order' => 11,
                'level' => 2,
                'title' => 'Sanctumで認証APIを実装する',
                'description' => "【作るもの】ログイン機能付きAPI\n【機能要件】\n- 会員登録・ログイン・ログアウト\n- 認証が必要なルートの保護\n- ログインユーザーの情報取得\n【技術指定】\n- Laravel Sanctum（SPA認証）\n- auth:sanctum ミドルウェア\n- セッションCookieとCSRFトークン\n- Postmanで認証フローをテスト",
                'status' => 'todo'
            ],

            [
                'id' => 112,
                'category' => 'サーバーサイド',
                'order' => 12,
                'level' => 2,
                'title' => 'APIリソースでレスポンスを整形する',
                'description' => "【作るもの】整形されたレスポンスのAPI\n【機能要件】\n- 不要なフィールドを除外\n- 関連データをネストして返す\n- ページネーション情報を含める\n【技術指定】\n- Laravel API Resource\n- ResourceCollection\n- whenLoaded() で条件付き読み込み\n- meta情報（total/per_page）の付与",
                'status' => 'todo'
            ],

            // ── LEVEL 3: Laravel 応用（13〜20）──
            [
                'id' => 113,
                'category' => 'サーバーサイド',
                'order' => 13,
                'level' => 3,
                'title' => 'ファイルアップロードを実装する',
                'description' => "【作るもの】画像アップロードAPI\n【機能要件】\n- JPEG/PNG（最大2MB）のアップロード\n- サムネイル自動生成（200×200px）\n- ファイル名をハッシュ化して保存\n- アップロード済みURLを返す\n【技術指定】\n- Laravel Storage Facade\n- Intervention Image でリサイズ\n- バリデーション（mimes/max）\n- S3への保存（オプション）",
                'status' => 'todo'
            ],

            [
                'id' => 114,
                'category' => 'サーバーサイド',
                'order' => 14,
                'level' => 3,
                'title' => '検索・フィルタ・ソートAPIを作る',
                'description' => "【作るもの】高機能な商品検索API\n【機能要件】\n- キーワード検索（名前・説明文）\n- 価格帯フィルタ・カテゴリフィルタ\n- 複数カラムでのソート\n- カーソルページネーション\n【技術指定】\n- Eloquentスコープ（local scope）\n- クエリビルダのチェーン\n- N+1問題の解消を確認\n- query logで実行SQLを確認",
                'status' => 'todo'
            ],

            [
                'id' => 115,
                'category' => 'サーバーサイド',
                'order' => 15,
                'level' => 3,
                'title' => 'Policyで権限管理を実装する',
                'description' => "【作るもの】権限付き投稿管理API\n【機能要件】\n- 自分の投稿のみ編集・削除可\n- 管理者は全投稿を操作可\n- 権限なしは403エラーを返す\n【技術指定】\n- Laravel Policy\n- Gate ファサード\n- ミドルウェアでのロールチェック\n- Seederで管理者ユーザー作成",
                'status' => 'todo'
            ],

            [
                'id' => 116,
                'category' => 'サーバーサイド',
                'order' => 16,
                'level' => 3,
                'title' => 'キューとジョブで非同期処理をする',
                'description' => "【作るもの】メール非同期送信システム\n【機能要件】\n- 会員登録後にウェルカムメール送信\n- 失敗時は3回リトライ\n- 5分後に遅延実行\n- 失敗ジョブをDBに記録\n【技術指定】\n- Laravel Queue（database driver）\n- Mailableクラス（Mailtrap使用）\n- php artisan queue:work\n- Horizon（キュー監視）",
                'status' => 'todo'
            ],

            [
                'id' => 117,
                'category' => 'サーバーサイド',
                'order' => 17,
                'level' => 3,
                'title' => 'イベントとリスナーで疎結合設計をする',
                'description' => "【作るもの】通知機能付き注文システム\n【機能要件】\n- 注文完了でメール・Slack通知\n- イベント発火でリスナーが動く\n- リスナーはキューで非同期処理\n【技術指定】\n- Laravel Event / Listener\n- ShouldQueue インターフェース\n- Observer パターンとの使い分け\n- イベントのユニットテスト",
                'status' => 'todo'
            ],

            [
                'id' => 118,
                'category' => 'サーバーサイド',
                'order' => 18,
                'level' => 3,
                'title' => 'キャッシュでAPIを高速化する',
                'description' => "【作るもの】キャッシュ付き商品APIの実装\n【機能要件】\n- 商品一覧を5分間キャッシュ\n- 更新・削除時にキャッシュ自動クリア\n- レスポンスタイムを計測して比較\n【技術指定】\n- Laravel Cache（Redis）\n- Cache::remember / forget / tags\n- cache:clear コマンド\n- Telescope でクエリ数を確認",
                'status' => 'todo'
            ],

            [
                'id' => 119,
                'category' => 'サーバーサイド',
                'order' => 19,
                'level' => 3,
                'title' => 'DBトランザクションで整合性を保つ',
                'description' => "【作るもの】在庫管理付き注文API\n【機能要件】\n- 注文時に在庫を減らす\n- 在庫不足・決済失敗時はロールバック\n- 同時注文でも在庫が正しく管理される\n【技術指定】\n- DB::transaction()\n- 楽観的ロック（lockForUpdate）\n- 例外発生時の自動ロールバック\n- 並行処理のテスト",
                'status' => 'todo'
            ],

            [
                'id' => 120,
                'category' => 'サーバーサイド',
                'order' => 20,
                'level' => 3,
                'title' => 'APIドキュメントを作成する',
                'description' => "【作るもの】Swagger UIドキュメント\n【課題内容】\n- 全APIエンドポイントを文書化\n- リクエスト・レスポンスの例を記載\n- 認証フローを説明\n- Swagger UIから実際にAPIを叩けること\n【技術指定】\n- L5-Swagger（darkaonline）\n- @OA アノテーション記法\n- Bearer Token認証の設定\n- /api/documentation でUI表示",
                'status' => 'todo'
            ],

            // ── LEVEL 4: 品質向上（21〜25）──
            [
                'id' => 121,
                'category' => 'サーバーサイド',
                'order' => 21,
                'level' => 4,
                'title' => 'PHPUnitでFeatureテストを書く',
                'description' => "【作るもの】テスト付きTODO API\n【機能要件】\n- 全エンドポイントのFeatureテスト\n- 正常系・異常系の両方\n- 認証が必要なルートのテスト\n【技術指定】\n- PHPUnit + RefreshDatabase\n- Factory / Seeder でデータ準備\n- assertStatus / assertJson\n- カバレッジ80%以上",
                'status' => 'todo'
            ],

            [
                'id' => 122,
                'category' => 'サーバーサイド',
                'order' => 22,
                'level' => 4,
                'title' => 'リファクタリングしてコードを改善する',
                'description' => "【課題内容】\n- これまでのコードを見直してリファクタリング\n- Controller を薄くする（Serviceクラス抽出）\n- 重複コードを排除\n- 命名規則を統一\n【評価観点】\n- Controllerが200行以内\n- ビジネスロジックがServiceに分離\n- コメントがなくても読めるコード",
                'status' => 'todo'
            ],

            [
                'id' => 123,
                'category' => 'サーバーサイド',
                'order' => 23,
                'level' => 4,
                'title' => 'セキュリティ対策を実装する',
                'description' => "【課題内容】\n- SQLインジェクション対策の確認\n- XSS対策（出力のエスケープ）\n- CSRF対策\n- レートリミット（API制限）\n- 脆弱性スキャンツールでチェック\n【技術指定】\n- throttle ミドルウェア\n- OWASP Top 10 の理解\n- セキュリティヘッダーの設定\n- Laravelの組み込み対策の確認",
                'status' => 'todo'
            ],

            [
                'id' => 124,
                'category' => 'サーバーサイド',
                'order' => 24,
                'level' => 4,
                'title' => 'パフォーマンスチューニングをする',
                'description' => "【課題内容】\n- スロークエリを特定して改善\n- DBインデックスを適切に設定\n- N+1問題を全て解消\n- Redisでセッション・キャッシュを管理\n【技術指定】\n- Laravel Telescope\n- EXPLAIN でクエリ実行計画を確認\n- 改善前後のレスポンスタイムを比較\n- JMeter / k6 で負荷テスト",
                'status' => 'todo'
            ],

            [
                'id' => 125,
                'category' => 'サーバーサイド',
                'order' => 25,
                'level' => 4,
                'title' => 'WebSocketでリアルタイム通信を実装する',
                'description' => "【作るもの】リアルタイムチャットAPI\n【機能要件】\n- メッセージの即時配信\n- 入力中のインジケーター\n- オンラインユーザー一覧\n【技術指定】\n- Laravel Broadcasting\n- Pusher または Soketi（OSS版）\n- Echo（フロントエンド側）\n- Presence Channel",
                'status' => 'todo'
            ],

            // ── LEVEL 5: 卒業制作（26〜30）──
            [
                'id' => 126,
                'category' => 'サーバーサイド',
                'order' => 26,
                'level' => 5,
                'title' => '外部APIと連携するサービスを作る',
                'description' => "【作るもの】決済機能付きAPIサービス\n【機能要件】\n- Stripe決済API連携\n- 支払い処理・返金処理\n- Webhookで入金確認\n- 請求書の自動発行\n【技術指定】\n- Stripe PHP SDK\n- Webhook署名の検証\n- 冪等性キーでの二重課金防止\n- テストモードで動作確認",
                'status' => 'todo'
            ],

            [
                'id' => 127,
                'category' => 'サーバーサイド',
                'order' => 27,
                'level' => 5,
                'title' => 'マイクロサービスの基礎を理解する',
                'description' => "【作るもの】API Gateway + 2サービス構成\n【機能要件】\n- UserService（認証・ユーザー管理）\n- PostService（投稿管理）\n- API Gatewayが振り分け\n- サービス間通信（HTTP）\n【技術指定】\n- 各サービスを別々のLaravelプロジェクトで\n- Dockerで複数サービスを起動\n- サービス間のJWT認証\n- 障害時のフォールバック",
                'status' => 'todo'
            ],

            [
                'id' => 128,
                'category' => 'サーバーサイド',
                'order' => 28,
                'level' => 5,
                'title' => 'GraphQL APIを実装する',
                'description' => "【作るもの】GraphQL対応ブログAPI\n【機能要件】\n- Query（記事・ユーザー取得）\n- Mutation（記事作成・更新・削除）\n- Subscription（リアルタイム更新）\n- N+1問題の解消\n【技術指定】\n- Lighthouse（Laravel GraphQL）\n- DataLoader でバッチ取得\n- GraphQL Playground で動作確認\n- REST APIとの使い分けを理解",
                'status' => 'todo'
            ],

            [
                'id' => 129,
                'category' => 'サーバーサイド',
                'order' => 29,
                'level' => 5,
                'title' => 'コードレビューを受ける',
                'description' => "【課題内容】\n- ベストな成果物をGitHub PRにまとめる\n- レビューコメントに対応\n- 修正内容と学びをREADMEにまとめる\n【評価観点】\n- SOLID原則の遵守\n- 適切な例外処理\n- セキュリティ対策\n- テストカバレッジ",
                'status' => 'todo'
            ],

            [
                'id' => 130,
                'category' => 'サーバーサイド',
                'order' => 30,
                'level' => 5,
                'title' => '卒業制作：ECサイトバックエンドAPIを作る',
                'description' => "【作るもの】本格的なECサイトAPI\n【機能要件】\n- 商品管理・カート・注文・決済\n- 在庫管理（トランザクション）\n- 管理者・一般ユーザーの権限分離\n- 注文履歴・ステータス管理\n【技術指定】\n- 全エンドポイントにFeatureテスト\n- Swagger UIでドキュメント化\n- Redisでキャッシュ\n- GitHub Actionsで自動テスト",
                'status' => 'todo'
            ],
        ];
    }

    private function infraTasks(): array
    {
        return [
            // ── LEVEL 1: 基礎（1〜6）──
            [
                'id' => 201,
                'category' => 'インフラ',
                'order' => 1,
                'level' => 1,
                'title' => 'Linuxコマンドをマスターする',
                'description' => "【課題内容】\n- ファイル操作・権限管理・プロセス管理\n- パイプ・リダイレクト・環境変数\n- ネットワーク確認（ping/curl/netstat）\n- cronで定期実行\n【提出物】\n- よく使うコマンド100選をまとめたREADME\n- サーバーの死活監視シェルスクリプト",
                'status' => 'todo'
            ],

            [
                'id' => 202,
                'category' => 'インフラ',
                'order' => 2,
                'level' => 1,
                'title' => 'Dockerの基礎を理解する',
                'description' => "【作るもの】Nginx + PHPのコンテナ環境\n【課題内容】\n- イメージ・コンテナ・ボリュームの概念理解\n- Dockerfileを書いてイメージをビルド\n- コンテナ内でPHPが動くことを確認\n【技術指定】\n- docker pull/run/build/ps/logs/exec\n- Dockerfile（FROM/RUN/COPY/CMD）\n- ポートマッピング・ボリュームマウント",
                'status' => 'todo'
            ],

            [
                'id' => 203,
                'category' => 'インフラ',
                'order' => 3,
                'level' => 1,
                'title' => 'Docker Composeで複数コンテナを管理する',
                'description' => "【作るもの】Laravel + MySQL + Redis 環境\n【機能要件】\n- docker-compose up で全サービスが起動\n- Laravel から MySQL に接続できる\n- Redis がキャッシュとして使える\n- phpMyAdmin でDB確認できる\n【技術指定】\n- docker-compose.yml（version/services/networks/volumes）\n- サービス間通信（コンテナ名で名前解決）\n- depends_on でサービスの起動順序制御",
                'status' => 'todo'
            ],

            [
                'id' => 204,
                'category' => 'インフラ',
                'order' => 4,
                'level' => 1,
                'title' => 'Nginxを設定する',
                'description' => "【作るもの】Nginx + PHP-FPM 本番設定\n【機能要件】\n- NginxがLaravelにリクエストを転送\n- 静的ファイルはNginxが直接配信\n- アクセスログ・エラーログ設定\n- Gzip圧縮で転送量削減\n【技術指定】\n- nginx.conf（server/location ブロック）\n- upstream で PHP-FPM に接続\n- try_files でSPAルーティング対応\n- レスポンスヘッダーの設定",
                'status' => 'todo'
            ],

            [
                'id' => 205,
                'category' => 'インフラ',
                'order' => 5,
                'level' => 1,
                'title' => 'Git/GitHubでチーム開発の流れを理解する',
                'description' => "【課題内容】\n- ブランチ戦略（Git Flow）の理解と実践\n- PRを作ってセルフレビュー\n- コンフリクト解消の練習\n- タグでリリース管理\n【技術指定】\n- git branch/checkout/merge/rebase\n- GitHub Actions で簡単なワークフロー作成\n- .gitignore の適切な設定\n- conventional commits でコミットメッセージ統一",
                'status' => 'todo'
            ],

            [
                'id' => 206,
                'category' => 'インフラ',
                'order' => 6,
                'level' => 1,
                'title' => 'VPSにLaravelを手動デプロイする',
                'description' => "【作るもの】VPS上で動くLaravelアプリ\n【課題内容】\n- さくらVPS/ConoHaなど安価なVPSを使用\n- SSHでサーバーに接続\n- Nginx + PHP + MySQL + Composerをインストール\n- Laravelアプリをデプロイして動かす\n【技術指定】\n- SSH鍵認証（パスワード認証を無効化）\n- ファイルパーミッションの設定\n- .envの本番設定\n- php artisan optimize",
                'status' => 'todo'
            ],

            // ── LEVEL 2: CI/CD・AWS 基礎（7〜14）──
            [
                'id' => 207,
                'category' => 'インフラ',
                'order' => 7,
                'level' => 2,
                'title' => 'GitHub ActionsでCIパイプラインを作る',
                'description' => "【作るもの】自動テスト環境\n【機能要件】\n- PRのたびにPHPUnitが自動実行\n- テスト失敗時はマージをブロック\n- コードスタイルチェック（PHP CS Fixer）\n- テスト結果をPRコメントに投稿\n【技術指定】\n- .github/workflows/ci.yml\n- services（MySQL）でのテスト環境\n- キャッシュで実行時間を短縮\n- matrix strategy で複数バージョンテスト",
                'status' => 'todo'
            ],

            [
                'id' => 208,
                'category' => 'インフラ',
                'order' => 8,
                'level' => 2,
                'title' => 'GitHub ActionsでCDパイプラインを作る',
                'description' => "【作るもの】自動デプロイ環境\n【機能要件】\n- mainへのマージで自動デプロイ\n- デプロイ前にテストを実行\n- デプロイ失敗時はSlack通知\n- ロールバック手順を整備\n【技術指定】\n- SSH action でリモートデプロイ\n- Secrets で機密情報管理\n- zero-downtime deploy\n- デプロイ履歴の管理",
                'status' => 'todo'
            ],

            [
                'id' => 209,
                'category' => 'インフラ',
                'order' => 9,
                'level' => 2,
                'title' => 'AWSの基礎サービスを理解する',
                'description' => "【課題内容】\n- AWSの主要サービスの役割を説明できる\n- 無料枠でEC2インスタンスを立てる\n- S3にファイルをアップロード・公開\n- IAMでユーザーと権限を管理\n【技術指定】\n- AWS Console の操作\n- CLI（aws configure）の設定\n- セキュリティグループの設定\n- コスト管理（Budget Alert設定）",
                'status' => 'todo'
            ],

            [
                'id' => 210,
                'category' => 'インフラ',
                'order' => 10,
                'level' => 2,
                'title' => 'EC2にLaravelをデプロイする',
                'description' => "【作るもの】AWS EC2上の本番環境\n【機能要件】\n- EC2でLaravelが動く\n- Elastic IPで固定IPを設定\n- Route53でカスタムドメイン設定\n- ElastiCacheでRedisを使う\n【技術指定】\n- EC2（t2.micro 無料枠）\n- IAMロールでS3アクセス\n- システムマネージャーでSSH不要運用\n- CloudWatch でCPU監視",
                'status' => 'todo'
            ],

            [
                'id' => 211,
                'category' => 'インフラ',
                'order' => 11,
                'level' => 2,
                'title' => 'RDSでMySQLを本番運用する',
                'description' => "【作るもの】マネージドDB環境\n【機能要件】\n- RDS MySQLにLaravelから接続\n- 自動バックアップ（7日間保持）\n- スナップショットから復元\n- パラメータグループで文字コード設定\n【技術指定】\n- RDS MySQL 8.x\n- セキュリティグループでEC2からのみ接続許可\n- 暗号化（保存時・転送時）\n- パフォーマンスインサイト",
                'status' => 'todo'
            ],

            [
                'id' => 212,
                'category' => 'インフラ',
                'order' => 12,
                'level' => 2,
                'title' => 'S3で静的ファイルを管理する',
                'description' => "【作るもの】S3 + CloudFrontの画像配信\n【機能要件】\n- Laravelの画像アップロードをS3に保存\n- CloudFrontで高速配信\n- バケットポリシーで適切に公開制御\n- 署名付きURLでプライベートファイル配信\n【技術指定】\n- AWS SDK for PHP\n- IAMロールで認証（アクセスキー不使用）\n- ライフサイクルポリシーで古いファイル削除\n- CORS設定",
                'status' => 'todo'
            ],

            [
                'id' => 213,
                'category' => 'インフラ',
                'order' => 13,
                'level' => 2,
                'title' => 'HTTPS化とSSL証明書を設定する',
                'description' => "【作るもの】SSL/TLS対応サーバー\n【機能要件】\n- Let's EncryptでSSL証明書取得\n- HTTP → HTTPS自動リダイレクト\n- 証明書の90日自動更新\n- セキュリティヘッダー設定\n【技術指定】\n- Certbot（Let's Encrypt）\n- nginx SSL設定（TLS 1.2以上）\n- cron jobで certbot renew\n- SSL Labs でA+評価",
                'status' => 'todo'
            ],

            [
                'id' => 214,
                'category' => 'インフラ',
                'order' => 14,
                'level' => 2,
                'title' => 'CloudWatchで監視・アラートを設定する',
                'description' => "【作るもの】サーバー監視システム\n【機能要件】\n- CPU/メモリ/ディスク使用率の監視\n- 閾値超過時にSlackメール通知\n- Laravelのエラーログ収集\n- ダッシュボードで可視化\n【技術指定】\n- CloudWatch Agent（カスタムメトリクス）\n- CloudWatch Alarm\n- SNS → Lambda → Slack通知\n- ロググループの保持期間設定",
                'status' => 'todo'
            ],

            // ── LEVEL 3: 応用（15〜22）──
            [
                'id' => 215,
                'category' => 'インフラ',
                'order' => 15,
                'level' => 3,
                'title' => 'Terraformでインフラをコード化する',
                'description' => "【作るもの】TerraformでAWS環境を自動構築\n【機能要件】\n- VPC/EC2/RDS/S3をコードで管理\n- terraform applyで環境再現\n- 本番・ステージング環境の切り替え\n- stateファイルをS3で管理\n【技術指定】\n- Terraform基本構文\n- AWSプロバイダー\n- variable / output / module\n- terraform plan で差分確認",
                'status' => 'todo'
            ],

            [
                'id' => 216,
                'category' => 'インフラ',
                'order' => 16,
                'level' => 3,
                'title' => 'Ansibleでサーバー設定を自動化する',
                'description' => "【作るもの】サーバー初期設定の自動化\n【機能要件】\n- 新規VPSに必要なソフトウェアを自動インストール\n- Nginx/PHP/MySQL設定を自動適用\n- べき等性（何度実行しても同じ結果）\n【技術指定】\n- Playbook/Role/Task の構造\n- inventory ファイルでサーバー管理\n- vault で機密情報暗号化\n- 実行結果のレポート",
                'status' => 'todo'
            ],

            [
                'id' => 217,
                'category' => 'インフラ',
                'order' => 17,
                'level' => 3,
                'title' => 'Dockerイメージを最適化する',
                'description' => "【作るもの】軽量なLaravelコンテナ\n【機能要件】\n- イメージサイズを100MB以下に\n- マルチステージビルドで最終イメージを軽量化\n- セキュリティスキャンでCRITICAL脆弱性なし\n【技術指定】\n- Alpine Linux ベースイメージ\n- マルチステージビルド\n- .dockerignore の最適化\n- Trivy でイメージスキャン",
                'status' => 'todo'
            ],

            [
                'id' => 218,
                'category' => 'インフラ',
                'order' => 18,
                'level' => 3,
                'title' => 'ECSでコンテナをAWSで動かす',
                'description' => "【作るもの】ECS Fargate上のLaravelアプリ\n【機能要件】\n- DockerイメージをECRにpush\n- ECS FargateでLaravelを起動\n- ALBでHTTPS対応\n- 自動スケーリング設定\n【技術指定】\n- ECR（コンテナレジストリ）\n- ECS タスク定義・サービス設定\n- ALB Target Group\n- Auto Scaling（CPU 70%で増やす）",
                'status' => 'todo'
            ],

            [
                'id' => 219,
                'category' => 'インフラ',
                'order' => 19,
                'level' => 3,
                'title' => 'Kubernetesの基礎を理解する',
                'description' => "【作るもの】minikube上のLaravelアプリ\n【機能要件】\n- PodでLaravelが動く\n- Serviceでアクセスできる\n- Deploymentで3台に増やせる\n- ConfigMap/Secretsで設定管理\n【技術指定】\n- minikubeでローカル検証\n- kubectl基本コマンド\n- YAML マニフェストファイル\n- rolling updateで無停止デプロイ",
                'status' => 'todo'
            ],

            [
                'id' => 220,
                'category' => 'インフラ',
                'order' => 20,
                'level' => 3,
                'title' => 'DBバックアップと障害復旧を実装する',
                'description' => "【作るもの】自動バックアップシステム\n【機能要件】\n- 毎日0時にDBを自動バックアップ\n- S3に30日分保持（古いものは自動削除）\n- バックアップから15分以内に復元できる\n- 完了・失敗をSlack通知\n【技術指定】\n- mysqldump スクリプト\n- cron job\n- AWS CLI でS3アップロード\n- 復元手順書の作成",
                'status' => 'todo'
            ],

            [
                'id' => 221,
                'category' => 'インフラ',
                'order' => 21,
                'level' => 3,
                'title' => 'WAFとセキュリティ対策を実装する',
                'description' => "【課題内容】\n- AWS WAFでSQLインジェクション・XSSをブロック\n- Security Hubで脆弱性を検出\n- GuardDutyで不正アクセスを検知\n- 不正IPのブロック設定\n【技術指定】\n- AWS WAF マネージドルール\n- Security Hub（CIS Benchmark）\n- CloudTrailで操作ログ記録\n- AWS Config で設定変更を追跡",
                'status' => 'todo'
            ],

            [
                'id' => 222,
                'category' => 'インフラ',
                'order' => 22,
                'level' => 3,
                'title' => 'コスト最適化をする',
                'description' => "【課題内容】\n- 現在のAWS利用料を分析\n- 使っていないリソースを削除\n- リザーブドインスタンスで40%削減\n- Savings Plansの検討\n【技術指定】\n- Cost Explorer で分析\n- Trusted Advisor の推奨に対応\n- Budget Alert で予算超過防止\n- 削減前後のコスト比較レポート",
                'status' => 'todo'
            ],

            // ── LEVEL 4: 高度な構成（23〜27）──
            [
                'id' => 223,
                'category' => 'インフラ',
                'order' => 23,
                'level' => 4,
                'title' => 'マルチAZ構成で高可用性を実現する',
                'description' => "【作るもの】障害に強いインフラ\n【機能要件】\n- AZ障害時に自動フェイルオーバー\n- RDS Multi-AZ でDBを冗長化\n- ALBが複数AZに分散\n- 障害テスト（片方のAZを落とす）\n【技術指定】\n- ALB + Auto Scaling Group\n- RDS Multi-AZ\n- Route53 ヘルスチェック\n- 障害時のRTOを30分以内に",
                'status' => 'todo'
            ],

            [
                'id' => 224,
                'category' => 'インフラ',
                'order' => 24,
                'level' => 4,
                'title' => 'サービスメッシュを理解する',
                'description' => "【作るもの】Istio を使ったマイクロサービス環境\n【機能要件】\n- サービス間通信の可視化\n- トラフィック制御（カナリアリリース）\n- 相互TLS認証\n- サーキットブレーカー設定\n【技術指定】\n- Istio + Kubernetes\n- Kiali でサービス間の依存関係を可視化\n- Prometheus + Grafana でメトリクス収集\n- Jaeger でトレーシング",
                'status' => 'todo'
            ],

            [
                'id' => 225,
                'category' => 'インフラ',
                'order' => 25,
                'level' => 4,
                'title' => 'SREの基礎を実践する',
                'description' => "【課題内容】\n- SLI/SLO/エラーバジェットを定義\n- サービスの可用性99.9%を目標に設定\n- インシデント対応手順書を作成\n- ポストモーテム（障害報告書）を書く\n【技術指定】\n- Prometheus でSLIを計測\n- Grafana でSLOダッシュボード作成\n- アラートルールの設定\n- PagerDuty連携",
                'status' => 'todo'
            ],

            [
                'id' => 226,
                'category' => 'インフラ',
                'order' => 26,
                'level' => 4,
                'title' => 'セキュリティ診断を実施する',
                'description' => "【課題内容】\n- OWASP ZAPで脆弱性スキャン\n- Trivy でコンテナイメージのスキャン\n- AWS Security Hub のチェック全通過\n- ペネトレーションテストの基礎\n【技術指定】\n- OWASP ZAP（自動スキャン）\n- Trivy（イメージスキャン）\n- CIS Benchmark 80%以上準拠\n- 発見した脆弱性の修正と再確認",
                'status' => 'todo'
            ],

            [
                'id' => 227,
                'category' => 'インフラ',
                'order' => 27,
                'level' => 4,
                'title' => 'パフォーマンステストをする',
                'description' => "【作るもの】負荷テスト環境\n【機能要件】\n- 1000 req/秒に耐えられる構成\n- ボトルネックを特定して改善\n- オートスケールの動作確認\n【技術指定】\n- k6 で負荷テストシナリオ作成\n- CloudWatch でリソース使用率確認\n- 改善前後のスループット比較\n- テスト結果レポートを作成",
                'status' => 'todo'
            ],

            // ── LEVEL 5: 卒業制作（28〜30）──
            [
                'id' => 228,
                'category' => 'インフラ',
                'order' => 28,
                'level' => 5,
                'title' => '本番グレードのCI/CDパイプラインを構築する',
                'description' => "【作るもの】完全自動化されたデプロイ環境\n【機能要件】\n- PRマージで自動テスト → Staging自動デプロイ\n- Staging承認後に本番自動デプロイ\n- デプロイ失敗時は自動ロールバック\n- 全プロセスをSlack通知\n【技術指定】\n- GitHub Actions（環境別ワークフロー）\n- Blue/Greenデプロイ\n- カナリアリリース\n- デプロイ履歴の管理",
                'status' => 'todo'
            ],

            [
                'id' => 229,
                'category' => 'インフラ',
                'order' => 29,
                'level' => 5,
                'title' => 'コードレビューを受ける',
                'description' => "【課題内容】\n- Terraform・Dockerfileなど成果物をGitHub PRにまとめる\n- レビューコメントに対応\n- 修正内容と学びをREADMEにまとめる\n【評価観点】\n- セキュリティの考慮\n- コストの最適化\n- 可用性・スケーラビリティ\n- コードの再利用性（モジュール化）",
                'status' => 'todo'
            ],

            [
                'id' => 230,
                'category' => 'インフラ',
                'order' => 30,
                'level' => 5,
                'title' => '卒業制作：本番グレードのインフラを構築する',
                'description' => "【作るもの】全構成をコードで管理する本番インフラ\n【機能要件】\n- マルチAZ・オートスケール・CDN対応\n- WAF・GuardDuty・Security Hub設定済み\n- 完全自動化されたCI/CDパイプライン\n- 監視・アラート・バックアップ全て設定済み\n【技術指定】\n- 全AWSリソースをTerraformで管理\n- ECS Fargateでコンテナ運用\n- Prometheus + Grafanaで監視\n- アーキテクチャ構成図を作成",
                'status' => 'todo'
            ],
        ];
    }

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

    // ステータス更新（todo → in_progress など）
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', 'in:todo,in_progress,done'],
        ]);

        $task = collect($this->allTasks())->firstWhere('id', $id);
        if (!$task) return response()->json(['message' => '課題が見つかりません'], 404);

        // DB化後: Task::find($id)->update(['status' => $request->status]);
        return response()->json(
            array_merge($task, ['status' => $request->status])
        );
    }

    // 課題提出
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
}
