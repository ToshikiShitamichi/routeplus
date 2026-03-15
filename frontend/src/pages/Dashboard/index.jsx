// frontend/src/pages/Dashboard/index.jsx

import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import { fetchTasks, groupByCategory } from "../../api/tasks";
import DonutChart from "../../components/DonutChart";
import styles from "./style.module.scss";

function getNextTask(tasks) {
    return (
        tasks.find((t) => t.status === "todo") ||
        tasks.find((t) => t.status === "in_review") ||
        null
    );
}

export default function Dashboard() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();

    const [tasks, setTasks] = useState([]);
    const [roadmap, setRoadmap] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    useEffect(() => {
        const loadTasks = async () => {
            try {
                const data = await fetchTasks();
                setTasks(data);
                setRoadmap(groupByCategory(data));
            } catch (err) {
                console.error("課題一覧取得エラー", err);
                setError("課題一覧の取得に失敗しました");
            } finally {
                setLoading(false);
            }
        };
        loadTasks();
    }, []);

    const progress = {
        total:    tasks.length,
        done:     tasks.filter((t) => t.status === "done").length,
        inReview: tasks.filter((t) => t.status === "in_review").length,
        todo:     tasks.filter((t) => t.status === "todo").length,
    };

    const handleLogout = async () => {
        try {
            await logout();
            navigate("/");
        } catch (err) {
            console.error("ログアウトに失敗しました", err);
        }
    };

    const statusLabel = {
        todo:      "未着手",
        in_review: "レビュー中",
        done:      "完了",
    };

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

                {/* ── 上段：ヘッダー ＋ 統計カード ── */}
                <section className={styles.progressSection}>
                    <header className={styles.progressHeader}>
                        <div>
                            <h1 className={styles.pageTitle}>ダッシュボード</h1>
                            <p className={styles.userName}>
                                こんにちは、{user?.name ?? "ゲスト"} さん
                            </p>
                        </div>
                        <button className={styles.logoutButton} onClick={handleLogout}>
                            ログアウト
                        </button>
                    </header>

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
                            <div className={styles.statLabel}>未着手</div>
                            <div className={styles.statValue}>{progress.todo}</div>
                        </div>
                    </div>
                </section>

                {loading && <p>読み込み中...</p>}
                {error && <p>{error}</p>}

                {!loading && !error && (
                    <>
                        {/* ── 中段：次のおすすめ課題 ＋ 円グラフ ── */}
                        <section className={styles.recommendSection}>
                            <h2 className={styles.sectionTitle}>📌 次のおすすめ課題</h2>

                            <div className={styles.recommendGrid}>
                                {roadmap.map((group) => {
                                    const next = getNextTask(group.tasks);
                                    const groupDone = group.tasks.filter(
                                        (t) => t.status === "done"
                                    ).length;

                                    return (
                                        <div key={group.category} className={styles.recommendCard}>
                                            {/* 左：カテゴリ名 ＋ おすすめタスク */}
                                            <div className={styles.recommendLeft}>
                                                <span className={styles.recommendCategory}>
                                                    {group.category}
                                                </span>

                                                {next ? (
                                                    <>
                                                        <p className={styles.recommendTitle}>
                                                            {next.title}
                                                        </p>
                                                        <span className={`${styles.pill} ${styles[next.status]}`}>
                                                            {statusLabel[next.status]}
                                                        </span>
                                                    </>
                                                ) : (
                                                    <p className={styles.recommendComplete}>
                                                        🎉 完了！
                                                    </p>
                                                )}
                                            </div>

                                            {/* 右：円グラフ */}
                                            <div className={styles.recommendRight}>
                                                <DonutChart
                                                    done={groupDone}
                                                    total={group.tasks.length}
                                                />
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </section>

                        {/* ── 下段：学習ロードマップ（3列） ── */}
                        <section className={styles.roadmapSection}>
                            <h2 className={styles.sectionTitle}>学習ロードマップ</h2>

                            <div className={styles.roadmapGrid}>
                                {roadmap.map((group) => (
                                    <div key={group.category} className={styles.roadmapCard}>
                                        <h3 className={styles.categoryTitle}>
                                            {group.category}
                                        </h3>

                                        <ul className={styles.taskList}>
                                            {group.tasks.map((task) => (
                                                <li key={task.id} className={styles.taskItem}>
                                                    <span className={styles.taskTitle}>
                                                        {task.title}
                                                    </span>
                                                    <span className={`${styles.pill} ${styles[task.status]}`}>
                                                        {statusLabel[task.status] || task.status}
                                                    </span>
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                ))}
                            </div>
                        </section>
                    </>
                )}
            </main>
        </div>
    );
}