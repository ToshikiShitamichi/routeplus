import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { fetchTasks, groupByCategory } from '../../api/tasks';
import DonutChart from '../../components/DonutChart';
import TaskDetailModal from '../../components/TaskDetailModal';
import styles from './style.module.scss';

function getNextTask(tasks) {
    return (
        tasks.find((t) => t.status === 'in_progress') ||
        tasks.find((t) => t.status === 'todo') ||
        null
    );
}

export default function Dashboard() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();

    const [tasks, setTasks] = useState([]);
    const [roadmap, setRoadmap] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [selectedTask, setSelectedTask] = useState(null);

    useEffect(() => {
        const loadTasks = async () => {
            try {
                const data = await fetchTasks();
                setTasks(data);
                setRoadmap(groupByCategory(data));
            } catch (err) {
                console.error('課題一覧取得エラー', err);
                setError('課題一覧の取得に失敗しました');
            } finally {
                setLoading(false);
            }
        };
        loadTasks();
    }, []);

    const handleSubmitted = (updatedTask) => {
        setTasks((prev) => {
            const next = prev.map((t) => t.id === updatedTask.id ? updatedTask : t);
            setRoadmap(groupByCategory(next));
            return next;
        });
    };

    const handleLogout = async () => {
        try {
            await logout();
            navigate('/');
        } catch (err) {
            console.error('ログアウトに失敗しました', err);
        }
    };

    const openTask = (task) => {
        // 常にtasksから最新データを取得してモーダルを開く
        const latest = tasks.find((t) => t.id === task.id) ?? task;
        setSelectedTask(latest);
    };

    const statusLabel = { todo: '未着手', in_progress: '進行中', done: '完了' };

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

                {/* ── ヘッダー ── */}
                <div className={styles.pageHeader}>
                    <div>
                        <h1 className={styles.pageTitle}>ダッシュボード</h1>
                        <p className={styles.userName}>こんにちは、{user?.name ?? 'ゲスト'} さん</p>
                    </div>
                    <button className={styles.logoutButton} onClick={handleLogout}>
                        ログアウト
                    </button>
                </div>

                {loading && <p>読み込み中...</p>}
                {error && <p>{error}</p>}

                {!loading && !error && (
                    <>
                        {/* ── 次のおすすめ課題 ── */}
                        <section className={styles.recommendSection}>
                            <h2 className={styles.sectionTitle}>📌 次のおすすめ課題</h2>
                            <div className={styles.recommendGrid}>
                                {roadmap.map((group) => {
                                    const next = getNextTask(group.tasks);
                                    const groupDone = group.tasks.filter((t) => t.status === 'done').length;

                                    return (
                                        <div key={group.category} className={styles.recommendCard}>
                                            <div className={styles.recommendLeft}>
                                                <span className={styles.recommendCategory}>
                                                    {group.category}
                                                </span>
                                                {next ? (
                                                    <>
                                                        <p
                                                            className={styles.recommendTitle}
                                                            onClick={() => openTask(next)}
                                                        >
                                                            {next.title}
                                                        </p>
                                                        <span className={`${styles.pill} ${styles[next.status]}`}>
                                                            {statusLabel[next.status]}
                                                        </span>
                                                    </>
                                                ) : (
                                                    <p className={styles.recommendComplete}>🎉 完了！</p>
                                                )}
                                            </div>
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

                        {/* ── 学習ロードマップ ── */}
                        <section className={styles.roadmapSection}>
                            <h2 className={styles.sectionTitle}>学習ロードマップ</h2>
                            <div className={styles.roadmapGrid}>
                                {roadmap.map((group) => (
                                    <div key={group.category} className={styles.roadmapCard}>
                                        <h3 className={styles.categoryTitle}>{group.category}</h3>
                                        <ul className={styles.taskList}>
                                            {group.tasks.map((task) => (
                                                <li
                                                    key={task.id}
                                                    className={styles.taskItem}
                                                    onClick={() => openTask(task)}
                                                >
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

            {selectedTask && (
                <TaskDetailModal
                    task={selectedTask}
                    onClose={() => setSelectedTask(null)}
                    onSubmitted={handleSubmitted}
                />
            )}
        </div>
    );
}