import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { fetchSubmissions } from '../../api/tasks';
import styles from './style.module.scss';

export default function Submissions() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();
    const [submissions, setSubmissions] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        fetchSubmissions()
            .then(setSubmissions)
            .catch(() => setError('データの取得に失敗しました'))
            .finally(() => setLoading(false));
    }, []);

    const handleLogout = async () => {
        await logout();
        navigate('/');
    };

    const grouped = submissions.reduce((acc, task) => {
        if (!acc[task.category]) acc[task.category] = [];
        acc[task.category].push(task);
        return acc;
    }, {});

    const categoryOrder = Object.keys(grouped);

    return (
        <div className={styles.shell}>
            <aside className={styles.sidebar}>
                <div className={styles.brand}>Route+</div>
                <nav className={styles.nav}>
                    <div className={styles.navItem} onClick={() => navigate('/dashboard')}>
                        ダッシュボード
                    </div>
                    <div className={`${styles.navItem} ${styles.active}`}>
                        提出済み課題
                    </div>
                </nav>
            </aside>

            <main className={styles.main}>
                <div className={styles.pageHeader}>
                    <div>
                        <h1 className={styles.pageTitle}>提出済み課題</h1>
                        <p className={styles.userName}>{user?.name ?? 'ゲスト'} さんのポートフォリオ</p>
                    </div>
                    <div className={styles.headerRight}>
                        <span className={styles.totalBadge}>{submissions.length} 課題完了</span>
                        <button className={styles.portfolioButton} onClick={() => navigate(`/portfolio/${user?.id}`)}>
                            ポートフォリオを見る →
                        </button>
                        <button className={styles.logoutButton} onClick={handleLogout}>ログアウト</button>
                    </div>
                </div>

                {loading && <p>読み込み中...</p>}
                {error && <p>{error}</p>}

                {!loading && !error && submissions.length === 0 && (
                    <div className={styles.empty}>
                        <p>まだ提出済みの課題がありません</p>
                        <button className={styles.goButton} onClick={() => navigate('/dashboard')}>
                            課題を始める →
                        </button>
                    </div>
                )}

                {!loading && !error && submissions.length > 0 && (
                    <div className={styles.content}>
                        {categoryOrder.map((category) => (
                            <section key={category} className={styles.categorySection}>
                                <h2 className={styles.categoryTitle}>
                                    {category}
                                    <span className={styles.categoryCount}>
                                        {grouped[category].length} 件
                                    </span>
                                </h2>
                                <div className={styles.cardGrid}>
                                    {grouped[category].map((task) => (
                                        <div key={task.id} className={styles.card}>
                                            <div className={styles.cardHeader}>
                                                <span className={styles.levelBadge}>Lv.{task.level}</span>
                                                <span className={styles.doneBadge}>完了</span>
                                            </div>
                                            <h3 className={styles.taskTitle}>{task.title}</h3>
                                            {task.product_name && (
                                                <p className={styles.productName}>{task.product_name}</p>
                                            )}
                                            <p className={styles.submittedAt}>
                                                {task.submitted_at
                                                    ? new Date(task.submitted_at).toLocaleDateString('ja-JP')
                                                    : ''}
                                            </p>
                                            <div className={styles.links}>
                                                {task.github_url && (
                                                    <a href={task.github_url} target="_blank" rel="noopener noreferrer" className={styles.linkButton}>
                                                        GitHub
                                                    </a>
                                                )}
                                                {task.deploy_url && (
                                                    <a href={task.deploy_url} target="_blank" rel="noopener noreferrer" className={`${styles.linkButton} ${styles.deployLink}`}>
                                                        デプロイ
                                                    </a>
                                                )}
                                                {!task.github_url && !task.deploy_url && (
                                                    <span className={styles.noLink}>リンクなし</span>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </section>
                        ))}
                    </div>
                )}
            </main>
        </div>
    );
}