import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { fetchTasks, groupByCategory } from '../../api/tasks';
import { fetchMyGroups } from '../../api/groups';
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
    const [groups, setGroups] = useState([]);
    const [activeTab, setActiveTab] = useState('all'); // 'all' | group.id
    const [roadmap, setRoadmap] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [selectedTask, setSelectedTask] = useState(null);

    useEffect(() => {
        const load = async () => {
            try {
                const [taskData, groupData] = await Promise.all([
                    fetchTasks(null),
                    fetchMyGroups(),
                ]);
                setTasks(taskData);
                setGroups(groupData);
                setRoadmap(groupByCategory(taskData));
            } catch (err) {
                console.error(err);
                setError('データの取得に失敗しました');
            } finally {
                setLoading(false);
            }
        };
        load();
    }, []);

    useEffect(() => {
        if (loading) return;
        const loadByTab = async () => {
            try {
                const groupId = activeTab === 'all' ? null : activeTab;
                const taskData = await fetchTasks(groupId);
                setTasks(taskData);
                setRoadmap(groupByCategory(taskData));
            } catch (err) {
                console.error(err);
            }
        };
        loadByTab();
    }, [activeTab]);

    const handleSubmitted = (updatedTask) => {
        setTasks((prev) => {
            const next = prev.map((t) => t.id === updatedTask.id ? updatedTask : t);
            setRoadmap(groupByCategory(next));
            return next;
        });
    };

    const handleLogout = async () => {
        await logout();
        navigate('/');
    };

    const openTask = (task) => {
        const latest = tasks.find((t) => t.id === task.id) ?? task;
        setSelectedTask(latest);
    };

    const statusLabel = { todo: '未着手', in_progress: '進行中', done: '完了' };

    // タブに応じた表示データ（現状は全課題、後のフェーズでグループ別に絞り込む）
    const displayRoadmap = roadmap;

    return (
        <div className={styles.shell}>
            <aside className={styles.sidebar}>
                <div className={styles.brand}>Route+</div>
                <nav className={styles.nav}>
                    <div
                        className={`${styles.navItem} ${styles.active}`}
                        onClick={() => navigate('/dashboard')}
                    >
                        ダッシュボード
                    </div>
                    <div
                        className={styles.navItem}
                        onClick={() => navigate('/submissions')}
                    >
                        提出済み課題
                    </div>
                    <div className={styles.navItem}>設定（仮）</div>
                </nav>
            </aside>

            <main className={styles.main}>
                {/* ── ヘッダー ── */}
                <div className={styles.pageHeader}>
                    <div>
                        <h1 className={styles.pageTitle}>ダッシュボード</h1>
                        <p className={styles.userName}>
                            こんにちは、{user?.name ?? 'ゲスト'} さん
                        </p>
                    </div>
                    <div style={{ display: 'flex', gap: '1rem', alignItems: 'center' }}>
                        <button
                            className={styles.logoutButton}
                            style={{ background: '#7c3aed', color: '#fff' }}
                            onClick={() => navigate('/select-pack')}
                        >
                            ＋ パックを追加
                        </button>
                        <button className={styles.logoutButton} onClick={handleLogout}>
                            ログアウト
                        </button>
                    </div>

                </div>

                {/* ── グループタブ ── */}
                {!loading && groups.length > 0 && (
                    <div className={styles.groupTabs}>
                        <button
                            className={`${styles.groupTab} ${activeTab === 'all' ? styles.activeTab : ''}`}
                            onClick={() => setActiveTab('all')}
                        >
                            すべて
                        </button>
                        {groups.map((group) => (
                            <button
                                key={group.id}
                                className={`${styles.groupTab} ${activeTab === group.id ? styles.activeTab : ''}`}
                                onClick={() => setActiveTab(group.id)}
                            >
                                {group.name}
                            </button>
                        ))}
                    </div>
                )}

                {loading && <p>読み込み中...</p>}
                {error && <p>{error}</p>}

                {!loading && !error && (
                    <>
                        {/* ── 次のおすすめ課題 ── */}
                        <section className={styles.recommendSection}>
                            <h2 className={styles.sectionTitle}>📌 次のおすすめ課題</h2>
                            <div className={styles.recommendGrid}>
                                {displayRoadmap.map((group) => {
                                    const next = getNextTask(group.tasks);
                                    const groupDone = group.tasks.filter(
                                        (t) => t.status === 'done'
                                    ).length;

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
                                                    <p className={styles.recommendComplete}>
                                                        🎉 完了！
                                                    </p>
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
                            <h2 className={styles.sectionTitle}>
                                学習ロードマップ
                                {activeTab !== 'all' && (
                                    <span className={styles.activeGroupBadge}>
                                        {groups.find(g => g.id === activeTab)?.name}
                                    </span>
                                )}
                            </h2>
                            <div className={styles.roadmapGrid}>
                                {displayRoadmap.map((group) => (
                                    <div key={group.category} className={styles.roadmapCard}>
                                        <h3 className={styles.categoryTitle}>
                                            {group.category}
                                        </h3>
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