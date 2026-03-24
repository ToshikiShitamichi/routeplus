import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { fetchPublicPacks } from '../../api/packs';
import api from '../../lib/axios';
import styles from './style.module.scss';

export default function SelectPack() {
    const navigate = useNavigate();
    const [packs, setPacks] = useState([]);
    const [selected, setSelected] = useState([]);
    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);

    useEffect(() => {
        fetchPublicPacks()
            .then(setPacks)
            .finally(() => setLoading(false));
    }, []);

    const toggle = (id) => {
        setSelected(prev =>
            prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]
        );
    };

    const handleSubmit = async () => {
        if (selected.length === 0) {
            navigate('/dashboard');
            return;
        }
        setSubmitting(true);
        try {
            for (const packId of selected) {
                await api.post('/api/user/packs', { pack_id: packId });
            }
            navigate('/dashboard');
        } catch (err) {
            console.error(err);
            navigate('/dashboard');
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <div className={styles.page}>
            <div className={styles.container}>
                <div className={styles.logoWrap}>
                    <div className={styles.logo}>ROUTEPLUS</div>
                    <p className={styles.tagline}>学習コースを選んでください</p>
                </div>

                {loading && <p className={styles.loading}>読み込み中...</p>}

                {!loading && (
                    <>
                        <div className={styles.packGrid}>
                            {packs.map(pack => (
                                <div
                                    key={pack.id}
                                    className={`${styles.packCard} ${selected.includes(pack.id) ? styles.selected : ''}`}
                                    onClick={() => toggle(pack.id)}
                                >
                                    <div className={styles.packHeader}>
                                        {pack.is_official && (
                                            <span className={styles.officialBadge}>公式</span>
                                        )}
                                        <span className={styles.packCount}>
                                            {pack.items_count}課題
                                        </span>
                                    </div>
                                    <h3 className={styles.packName}>{pack.name}</h3>
                                    {pack.description && (
                                        <p className={styles.packDesc}>{pack.description}</p>
                                    )}
                                    {selected.includes(pack.id) && (
                                        <div className={styles.checkMark}>✓ 選択済み</div>
                                    )}
                                </div>
                            ))}
                        </div>

                        {packs.length === 0 && (
                            <p className={styles.empty}>現在利用可能なコースがありません</p>
                        )}

                        <div className={styles.actions}>
                            <button
                                className={styles.skipButton}
                                onClick={() => navigate('/dashboard')}
                            >
                                スキップ（後で選ぶ）
                            </button>
                            <button
                                className={styles.submitButton}
                                onClick={handleSubmit}
                                disabled={submitting}
                            >
                                {submitting ? '設定中...' : `${selected.length}件のコースで始める`}
                            </button>
                        </div>
                    </>
                )}
            </div>
        </div>
    );
}