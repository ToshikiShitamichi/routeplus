import styles from "./style.module.scss";

export default function Dashboard() {
    const progress = {
        total: 36,
        done: 12,
        inReview: 4,
        todo: 20,
    };
    const percent = Math.round((progress.done / progress.total) * 100);

    const roadmaps = [
        {
            key: "frontend",
            title: "フロントエンドロードマップ",
            description: "React / UI / 状態管理 / テスト など",
            tasks: [
                { id: "fe-1", title: "React基礎：コンポーネント分割", status: "おすすめ" },
                { id: "fe-2", title: "状態管理：useState/useEffect演習", status: "おすすめ" },
                { id: "fe-3", title: "ルーティング：React Router 入門", status: "おすすめ" },
            ],
        },
        {
            key: "serverside",
            title: "サーバサイドエンジニアロードマップ",
            description: "API / DB / 認証 / 設計 など",
            tasks: [
                { id: "be-1", title: "REST API設計：エンドポイント設計", status: "おすすめ" },
                { id: "be-2", title: "DB基礎：正規化とインデックス", status: "おすすめ" },
                { id: "be-3", title: "認証：JWT/セッション比較", status: "おすすめ" },
            ],
        },
        {
            key: "infra",
            title: "インフラエンジニアロードマップ",
            description: "Docker / CI/CD / 監視 など",
            tasks: [
                { id: "in-1", title: "Docker：Dockerfileとcompose基礎", status: "おすすめ" },
                { id: "in-2", title: "CI/CD：GitHub Actions入門", status: "おすすめ" },
                { id: "in-3", title: "監視：メトリクス/ログ/アラート設計", status: "おすすめ" },
            ],
        },
    ];

    return (
        <div className={styles.shell}>
            <aside className={styles.sidebar}>
                <div className={styles.brand}>ROUTEPLUS</div>

                <nav className={styles.nav}>
                    <div className={`${styles.navItem} ${styles.active}`}>ダッシュボード</div>
                    <div className={styles.navItem}>ロードマップ（仮）</div>
                    <div className={styles.navItem}>設定（仮）</div>
                </nav>
            </aside>

            <main className={styles.main}>
                <section className={styles.progressSection}>
                    <header className={styles.progressHeader}>
                        <h1 className={styles.pageTitle}>ダッシュボード</h1>
                        <div className={styles.progressMeta}>
                            <span className={styles.progressPercent}>{percent}%</span>
                            <span className={styles.progressHint}>完了 / 全課題</span>
                        </div>
                    </header>

                    <div className={styles.progressBarWrap} aria-label="progress">
                        <div className={styles.progressBar} style={{ width: `${percent}%` }} />
                    </div>

                    <div className={styles.stats}>
                        <div className={styles.stat}>
                            <div className={styles.statLabel}>総課題数</div>
                            <div className={styles.statValue}>{progress.total}</div>
                        </div>
                        <div className={styles.stat}>
                            <div className={styles.statLabel}>完了</div>
                            <div className={styles.statValue}>{progress.done}</div>
                        </div>
                        <div className={styles.stat}>
                            <div className={styles.statLabel}>レビュー中</div>
                            <div className={styles.statValue}>{progress.inReview}</div>
                        </div>
                        <div className={styles.stat}>
                            <div className={styles.statLabel}>未着手/対応中</div>
                            <div className={styles.statValue}>{progress.todo}</div>
                        </div>
                    </div>
                </section>

                <section className={styles.roadmapSection}>
                    <div className={styles.sectionTitleRow}>
                        <h2 className={styles.sectionTitle}>ロードマップ</h2>
                        <div className={styles.sectionSubTitle}>
                            3つの分岐からおすすめ課題を表示（仮データ）
                        </div>
                    </div>

                    <div className={styles.roadmapGrid}>
                        {roadmaps.map((rm) => (
                            <article key={rm.key} className={styles.roadmapCard}>
                                <div className={styles.roadmapHead}>
                                    <h3 className={styles.roadmapTitle}>{rm.title}</h3>
                                    <p className={styles.roadmapDesc}>{rm.description}</p>
                                </div>

                                <div className={styles.taskBlock}>
                                    <div className={styles.taskBlockTitle}>おすすめ課題</div>

                                    <ul className={styles.taskList}>
                                        {rm.tasks.map((t) => (
                                            <li key={t.id} className={styles.taskItem}>
                                                <span className={styles.taskTitle}>{t.title}</span>
                                                <span className={styles.pill}>{t.status}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>

                                <div className={styles.cardFooter}>
                                    <button
                                        className={styles.ghostButton}
                                        onClick={() => alert(`${rm.title}（仮）`)}
                                    >
                                        このロードマップを見る
                                    </button>
                                </div>
                            </article>
                        ))}
                    </div>
                </section>
            </main>
        </div>
    );
}