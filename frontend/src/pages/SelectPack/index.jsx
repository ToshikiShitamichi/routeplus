import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { fetchPublicPacks, fetchMyPackIds } from '../../api/packs';
import api from '../../lib/axios';
import styles from './style.module.scss';

export default function SelectPack() {
    const navigate = useNavigate();
    const [packs, setPacks] = useState([]);
    const [selected, setSelected] = useState([]);
    const [myPackIds, setMyPackIds] = useState([]);
    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);

    useEffect(() => {
        Promise.all([fetchPublicPacks(), fetchMyPackIds()])
            .then(([packData, myIds]) => {
                setPacks(packData);
                setMyPackIds(myIds);
            })
            .finally(() => setLoading(false));
    }, []);

    const toggle = (id) => {
        if (myPackIds.includes(id)) return; // 追加済みは変更不可
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
                    <div className={styles.logo}>Route+</div>
                    <p className={styles.tagline}>学習コースを選んでください</p>
                </div>

                {loading && <p className={styles.loading}>読み込み中...</p>}

                {!loading && (
                    <>
                        <div className={styles.packGrid}>
                            {packs.map(pack => {
                                const isAdded = myPackIds.includes(pack.id);
                                const isSelected = selected.includes(pack.id);
                                return (
                                    <div
                                        key={pack.id}
                                        className={`${styles.packCard} ${isSelected ? styles.selected : ''} ${isAdded ? styles.added : ''}`}
                                        onClick={() => toggle(pack.id)}
                                        style={{ opacity: isAdded ? 0.6 : 1, cursor: isAdded ? 'default' : 'pointer' }}
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
                                        {isAdded && (
                                            <div className={styles.checkMark}>✓ 追加済み</div>
                                        )}
                                        {!isAdded && isSelected && (
                                            <div className={styles.checkMark}>✓ 選択済み</div>
                                        )}
                                    </div>
                                );
                            })}
                        </div>

                        {packs.length === 0 && (
                            <p className={styles.empty}>現在利用可能なコースがありません</p>
                        )}

                        <div className={styles.actions}>
                            <button
                                className={styles.skipButton}
                                onClick={() => navigate('/dashboard')}
                            >
                                ← ダッシュボードに戻る
                            </button>
                            <button
                                className={styles.submitButton}
                                onClick={handleSubmit}
                                disabled={submitting || selected.length === 0}
                            >
                                {submitting ? '設定中...' : `${selected.length}件追加する`}
                            </button>
                        </div>
                    </>
                )}
            </div>
        </div>
    );
}