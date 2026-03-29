import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import api from '../../lib/axios';
import styles from './style.module.scss';

export default function Portfolio() {
    const { userId } = useParams();
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        api.get(`/api/portfolio/${userId}`)
            .then(res => setData(res.data))
            .catch(() => setError('ポートフォリオが見つかりません'))
            .finally(() => setLoading(false));
    }, [userId]);

    if (loading) return <div className={styles.loading}>読み込み中...</div>;
    if (error) return <div className={styles.error}>{error}</div>;

    const groupByCategory = (submissions) => {
        const map = {};
        submissions.forEach(s => {
            if (!map[s.category]) map[s.category] = [];
            map[s.category].push(s);
        });
        return map;
    };

    const grouped = groupByCategory(data.submissions);

    return (
        <div className={styles.page}>
            <header className={styles.header}>
                <div className={styles.brand}>ROUTEPLUS</div>
                <h1 className={styles.name}>{data.user.name} さんのポートフォリオ</h1>
                <p className={styles.count}>{data.submissions.length} 件のプロダクト</p>
            </header>

            <main className={styles.main}>
                {Object.entries(grouped).map(([category, items]) => (
                    <section key={category} className={styles.section}>
                        <h2 className={styles.category}>{category}</h2>
                        <div className={styles.grid}>
                            {items.map(item => (
                                <div key={item.id} className={styles.card}>
                                    <div className={styles.cardHeader}>
                                        <span className={styles.level}>Lv.{item.level}</span>
                                        <span className={styles.taskTitle}>{item.title}</span>
                                    </div>
                                    <h3 className={styles.productName}>
                                        {item.product_name || item.title}
                                    </h3>
                                    <div className={styles.links}>
                                        {item.deploy_url && (
                                            <a href={item.deploy_url} target="_blank"
                                                rel="noopener noreferrer" className={styles.linkDeploy}>
                                                🌐 サイトを見る
                                            </a>
                                        )}
                                        <a href={item.github_url} target="_blank"
                                            rel="noopener noreferrer" className={styles.linkGithub}>
                                            GitHub
                                        </a>
                                    </div>
                                    <p className={styles.date}>
                                        {new Date(item.submitted_at).toLocaleDateString('ja-JP')}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </section>
                ))}

                {data.submissions.length === 0 && (
                    <p className={styles.empty}>まだ提出済みのプロダクトがありません</p>
                )}
            </main>
        </div>
    );
}