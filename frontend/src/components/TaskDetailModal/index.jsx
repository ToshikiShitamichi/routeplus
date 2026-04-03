import { useState, useEffect } from 'react';
import { submitTask, updateTaskStatus } from '../../api/tasks';
import styles from './style.module.scss';

export default function TaskDetailModal({ task, onClose, onSubmitted }) {
    const [view, setView] = useState('detail');
    const [currentTask, setCurrentTask] = useState(task);
    const [githubUrl, setGithubUrl] = useState('');
    const [deployUrl, setDeployUrl] = useState('');
    const [productName, setProductName] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    const statusLabel = { todo: '未着手', in_progress: '進行中', done: '完了' };

    useEffect(() => {
        const startTask = async () => {
            if (task.status !== 'todo') return;
            try {
                const updated = await updateTaskStatus(task.id, 'in_progress');
                setCurrentTask(updated);
                onSubmitted(updated);
            } catch (err) {
                console.error('ステータス更新失敗', err);
            }
        };
        startTask();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);
        try {
            const result = await submitTask(currentTask.id, { githubUrl, deployUrl, productName });
            onSubmitted(result.task);
            onClose();
        } catch (err) {
            setError(err.response?.data?.errors?.github_url?.[0]
                ?? err.response?.data?.message ?? '提出に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className={styles.overlay} onClick={onClose}>
            <div className={styles.modal} onClick={(e) => e.stopPropagation()}>
                <button className={styles.closeButton} onClick={onClose}>✕</button>

                {view === 'detail' ? (
                    <>
                        <div className={styles.header}>
                            <span className={styles.category}>{currentTask.category}</span>
                            <h2 className={styles.title}>{currentTask.title}</h2>
                            <span className={`${styles.pill} ${styles[currentTask.status]}`}>
                                {statusLabel[currentTask.status]}
                            </span>
                        </div>
                        <p className={styles.description} style={{ whiteSpace: 'pre-line' }}>
                            {currentTask.description || '課題の説明はありません'}
                        </p>
                        {currentTask.status !== 'done' && (
                            <button className={styles.submitButton} onClick={() => setView('form')}>
                                提出する →
                            </button>
                        )}
                        {currentTask.status === 'done' && (
                            <p className={styles.doneMessage}>✅ この課題は完了済みです</p>
                        )}
                    </>
                ) : (
                    <>
                        <div className={styles.header}>
                            <span className={styles.category}>{currentTask.category}</span>
                            <h2 className={styles.title}>{currentTask.title} - 提出</h2>
                        </div>
                        <form onSubmit={handleSubmit}>
                            <div className={styles.field}>
                                <label className={styles.label}>
                                    プロダクト名 <span className={styles.optional}>（任意）</span>
                                </label>
                                <input className={styles.input} type="text"
                                    placeholder="例: My Portfolio App"
                                    value={productName} onChange={(e) => setProductName(e.target.value)}
                                    disabled={loading} />
                            </div>
                            <div className={styles.field}>
                                <label className={styles.label}>
                                    GitHub URL <span className={styles.required}>*</span>
                                </label>
                                <input className={styles.input} type="url"
                                    placeholder="https://github.com/your-name/repo"
                                    value={githubUrl} onChange={(e) => setGithubUrl(e.target.value)}
                                    required disabled={loading} />
                            </div>
                            <div className={styles.field}>
                                <label className={styles.label}>
                                    デプロイ URL <span className={styles.optional}>（任意）</span>
                                </label>
                                <input className={styles.input} type="url"
                                    placeholder="https://your-app.vercel.app"
                                    value={deployUrl} onChange={(e) => setDeployUrl(e.target.value)}
                                    disabled={loading} />
                            </div>
                            {error && <p className={styles.error}>{error}</p>}
                            <div className={styles.formActions}>
                                <button type="button" className={styles.backButton}
                                    onClick={() => setView('detail')} disabled={loading}>
                                    ← 戻る
                                </button>
                                <button type="submit" className={styles.submitButton} disabled={loading}>
                                    {loading ? '提出中...' : '提出する'}
                                </button>
                            </div>
                        </form>
                    </>
                )}
            </div>
        </div>
    );
}