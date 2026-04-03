import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import api from '../../lib/axios';
import styles from './style.module.scss';

export default function OperatorDashboard() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();
    const [inviteResult, setInviteResult] = useState(null);
    const [copied, setCopied] = useState(false);
    const [loading, setLoading] = useState(false);
    const [admins, setAdmins] = useState([]);
    const [tab, setTab] = useState('invite'); // 'invite' | 'tasks'
    const [officialTasks, setOfficialTasks] = useState([]);
    const [taskForm, setTaskForm] = useState({
        category: '', level: 1, title: '', description: ''
    });
    const [taskError, setTaskError] = useState('');
    const [editingTask, setEditingTask] = useState(null);
    const [isNewCategory, setIsNewCategory] = useState(false);

    useEffect(() => {
        api.get('/api/operator/admins')
            .then(res => setAdmins(res.data))
            .catch(console.error);
    }, []);

    useEffect(() => {
        if (tab === 'tasks') {
            api.get('/api/operator/tasks')
                .then(res => setOfficialTasks(res.data))
                .catch(console.error);
        }
    }, [tab]);

    const handleCreateInvite = async () => {
        setLoading(true);
        try {
            const res = await api.post('/api/operator/invitations', {});
            setInviteResult(res.data);
        } catch (e) {
            alert('招待URL発行に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    const handleCopy = (url) => {
        navigator.clipboard.writeText(url);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    const handleLogout = async () => {
        await logout();
        navigate('/');
    };

    const handleTaskFormChange = (e) => {
        const { name, value } = e.target;
        setTaskForm(prev => ({ ...prev, [name]: value }));
    };

    const handleCreateTask = async () => {
        setTaskError('');
        if (!taskForm.title.trim() || !taskForm.category.trim() || !taskForm.description.trim()) {
            setTaskError('カテゴリ・タイトル・説明は必須です');
            return;
        }
        try {
            await api.post('/api/operator/tasks', taskForm);
            setTaskForm({ category: '', level: 1, title: '', description: '', visibility: 'official' });
            const res = await api.get('/api/operator/tasks');
            setOfficialTasks(res.data);
        } catch (e) {
            setTaskError('作成に失敗しました');
        }
    };

    const handleUpdateTask = async () => {
        if (!editingTask) return;
        try {
            await api.patch(`/api/operator/tasks/${editingTask.id}`, {
                title: editingTask.title,
                description: editingTask.description,
                level: editingTask.level,
            });
            setEditingTask(null);
            const res = await api.get('/api/operator/tasks');
            setOfficialTasks(res.data);
        } catch (e) {
            setTaskError('更新に失敗しました');
        }
    };

    const handleDeleteTask = async (id) => {
        await api.delete(`/api/operator/tasks/${id}`);
        const res = await api.get('/api/operator/tasks');
        setOfficialTasks(res.data);
    };

    return (
        <div className={styles.shell}>
            <aside className={styles.sidebar}>
                <div className={styles.brand}>Route+</div>
                <div className={styles.operatorBadge}>運営</div>
                <nav style={{ marginTop: '1rem' }}>
                    {[
                        { key: 'invite', label: '管理者招待' },
                        { key: 'tasks', label: '公式課題管理' },
                    ].map(item => (
                        <div
                            key={item.key}
                            onClick={() => setTab(item.key)}
                            style={{
                                padding: '0.6rem 1rem',
                                cursor: 'pointer',
                                fontWeight: tab === item.key ? '700' : '400',
                                background: tab === item.key ? '#ede9fe' : 'transparent',
                                color: tab === item.key ? '#7c3aed' : 'inherit',
                                borderRadius: '8px',
                                margin: '2px 8px',
                            }}
                        >
                            {item.label}
                        </div>
                    ))}
                </nav>
            </aside>

            <main className={styles.main}>
                <div className={styles.pageHeader}>
                    <div>
                        <h1 className={styles.pageTitle}>
                            {tab === 'invite' ? '管理者招待URL発行' : '公式課題管理'}
                        </h1>
                        <p className={styles.userName}>{user?.name} さん（運営）</p>
                    </div>
                    <button className={styles.logoutButton} onClick={handleLogout}>
                        ログアウト
                    </button>
                </div>

                {tab === 'invite' && (
                    <section className={styles.section}>
                        <section className={styles.section}>
                            <div className={styles.card}>
                                <h2 className={styles.sectionTitle}>グループ作成用URLを発行</h2>
                                <p style={{ fontSize: '0.85rem', color: '#64748b', marginBottom: '1rem' }}>
                                    URLの有効期限は7日間です。
                                </p>
                                <div className={styles.formRow}>
                                    <button
                                        className={styles.button}
                                        onClick={handleCreateInvite}
                                        disabled={loading}
                                    >
                                        {loading ? '発行中...' : '発行'}
                                    </button>
                                </div>

                                {inviteResult && (
                                    <div className={styles.inviteResult}>
                                        <p style={{ fontSize: '0.8rem', color: '#166534', marginBottom: '0.5rem' }}>
                                            ✅ 招待URLを発行しました
                                        </p>
                                        <p className={styles.inviteUrl}>{inviteResult.invite_url}</p>
                                        <button
                                            className={styles.copyButton}
                                            onClick={() => handleCopy(inviteResult.invite_url)}
                                        >
                                            {copied ? 'コピー済み ✓' : 'URLをコピー'}
                                        </button>
                                        <p className={styles.expiry}>
                                            有効期限：{new Date(inviteResult.invitation.expires_at).toLocaleDateString('ja-JP')}
                                        </p>
                                    </div>
                                )}
                            </div>
                        </section>

                        <section className={styles.section}>
                            <div className={styles.card}>
                                <h2 className={styles.sectionTitle}>管理者一覧</h2>
                                {admins.length === 0 && (
                                    <p style={{ color: '#94a3b8', fontSize: '0.9rem' }}>まだ管理者がいません</p>
                                )}
                                <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                                    {admins.map(admin => (
                                        <li key={admin.id} style={{
                                            display: 'flex', justifyContent: 'space-between',
                                            alignItems: 'center', padding: '0.75rem 0',
                                            borderBottom: '1px solid #f1f5f9'
                                        }}>
                                            <div>
                                                <p style={{ fontWeight: '600', margin: 0 }}>{admin.name}</p>
                                                <p style={{ fontSize: '0.8rem', color: '#64748b', margin: 0 }}>{admin.email}</p>
                                            </div>
                                            <span style={{
                                                fontSize: '0.75rem', background: '#ede9fe',
                                                color: '#7c3aed', padding: '0.2rem 0.6rem', borderRadius: '999px'
                                            }}>
                                                {admin.organization ?? '組織なし'}
                                            </span>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </section>
                    </section>
                )}
                {tab === 'tasks' && (
                    <section className={styles.section}>
                        <div className={styles.card}>
                            <h2 className={styles.sectionTitle}>公式課題を作成</h2>
                            {taskError && <p style={{ color: 'red' }}>{taskError}</p>}

                            <label className={styles.optionLabel}>カテゴリ</label>
                            {!isNewCategory ? (
                                <div style={{ display: 'flex', gap: '0.5rem', marginBottom: '0.5rem' }}>
                                    <select className={styles.input} name="category" value={taskForm.category}
                                        onChange={handleTaskFormChange}>
                                        <option value="">カテゴリを選択</option>
                                        {[...new Set(officialTasks.map(t => t.category))].map(cat => (
                                            <option key={cat} value={cat}>{cat}</option>
                                        ))}
                                    </select>
                                    <button className={styles.button} style={{ whiteSpace: 'nowrap' }}
                                        onClick={() => { setIsNewCategory(true); setTaskForm(p => ({ ...p, category: '' })); }}>
                                        ＋ 新規
                                    </button>
                                </div>
                            ) : (
                                <div style={{ display: 'flex', gap: '0.5rem', marginBottom: '0.5rem' }}>
                                    <input className={styles.input} type="text" name="category"
                                        placeholder="新しいカテゴリ名" value={taskForm.category}
                                        onChange={handleTaskFormChange} />
                                    <button className={styles.button} style={{ whiteSpace: 'nowrap' }}
                                        onClick={() => { setIsNewCategory(false); setTaskForm(p => ({ ...p, category: '' })); }}>
                                        ← 選択に戻る
                                    </button>
                                </div>
                            )}

                            <label className={styles.optionLabel}>課題タイトル</label>
                            <input className={styles.input} type="text" name="title"
                                placeholder="課題タイトル" value={taskForm.title}
                                onChange={handleTaskFormChange}
                                style={{ marginBottom: '0.5rem', display: 'block', width: '100%' }} />

                            <label className={styles.optionLabel}>説明</label>
                            <div style={{ display: 'flex', gap: '12px', alignItems: 'flex-start', marginBottom: '0.5rem' }}>
                                <textarea
                                    className={styles.input}
                                    name="description"
                                    rows={8}
                                    placeholder="テンプレートを参考に記入してください"
                                    value={taskForm.description}
                                    onChange={handleTaskFormChange}
                                    style={{ flex: 1, resize: 'vertical', fontFamily: 'inherit' }}
                                />
                                <div style={{
                                    flex: 1,
                                    background: '#f8f7ff',
                                    border: '1px solid #e0deff',
                                    borderRadius: '8px',
                                    padding: '10px 14px',
                                    fontSize: '0.78rem',
                                    color: '#7c3aed',
                                    whiteSpace: 'pre-line',
                                    lineHeight: '1.7',
                                }}>
                                    {`【テンプレート】
                                        【課題内容】
                                        ここに課題の概要を書く。

                                        【要件】
                                        ・要件1
                                        ・要件2

                                        【提出物】
                                        ・GitHubリポジトリURL
                                        ・デプロイURL`}
                                </div>
                            </div>

                            <div style={{ marginBottom: '0.5rem' }}>
                                <label className={styles.optionLabel}>
                                    レベル
                                    <select className={styles.input} name="level" value={taskForm.level}
                                        onChange={handleTaskFormChange}>
                                        {[1, 2, 3, 4, 5].map(lv => <option key={lv} value={lv}>Lv.{lv}</option>)}
                                    </select>
                                </label>
                            </div>

                            <button className={styles.button} onClick={handleCreateTask}>課題を作成</button>
                        </div>

                        <div className={styles.card} style={{ marginTop: '1.5rem' }}>
                            <h2 className={styles.sectionTitle}>公式課題一覧</h2>
                            {officialTasks.length === 0 && <p style={{ color: '#94a3b8' }}>課題がありません</p>}
                            <ul style={{ listStyle: 'none', padding: 0 }}>
                                {officialTasks.map(task => (
                                    <li key={task.id} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '0.6rem 0', borderBottom: '1px solid #f1f5f9' }}>
                                        {editingTask?.id === task.id ? (
                                            <div style={{ width: '100%', display: 'flex', flexDirection: 'column', gap: '0.5rem', padding: '0.5rem 0' }}>
                                                <input className={styles.input} type="text"
                                                    placeholder="課題タイトル"
                                                    value={editingTask.title}
                                                    onChange={e => setEditingTask(p => ({ ...p, title: e.target.value }))} />
                                                <div style={{ display: 'flex', gap: '12px', alignItems: 'flex-start' }}>
                                                    <textarea
                                                        className={styles.input}
                                                        rows={8}
                                                        placeholder="説明"
                                                        value={editingTask.description || ''}
                                                        onChange={e => setEditingTask(p => ({ ...p, description: e.target.value }))}
                                                        style={{ flex: 1, resize: 'vertical', fontFamily: 'inherit' }}
                                                    />
                                                    <div style={{
                                                        flex: 1,
                                                        background: '#f8f7ff',
                                                        border: '1px solid #e0deff',
                                                        borderRadius: '8px',
                                                        padding: '10px 14px',
                                                        fontSize: '0.78rem',
                                                        color: '#7c3aed',
                                                        whiteSpace: 'pre-line',
                                                        lineHeight: '1.7',
                                                    }}>
                                                        {`【テンプレート】
                                                            【課題内容】
                                                            ここに課題の概要を書く。

                                                            【要件】
                                                            ・要件1
                                                            ・要件2

                                                            【提出物】
                                                            ・GitHubリポジトリURL
                                                            ・デプロイURL`}
                                                    </div>
                                                </div>
                                                <div style={{ display: 'flex', gap: '1rem' }}>
                                                    <label>
                                                        レベル
                                                        <select className={styles.input} name="level"
                                                            value={editingTask.level}
                                                            onChange={e => setEditingTask(p => ({ ...p, level: Number(e.target.value) }))}>
                                                            {[1, 2, 3, 4, 5].map(lv => <option key={lv} value={lv}>Lv.{lv}</option>)}
                                                        </select>
                                                    </label>
                                                </div>
                                                <div style={{ display: 'flex', gap: '0.5rem' }}>
                                                    <button className={styles.button} onClick={handleUpdateTask}>保存</button>
                                                    <button onClick={() => setEditingTask(null)}
                                                        style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#94a3b8' }}>
                                                        キャンセル
                                                    </button>
                                                </div>
                                            </div>
                                        ) : (
                                            <>
                                                <span style={{ fontSize: '0.9rem' }}>
                                                    [{task.category}] Lv.{task.level} {task.title}
                                                </span>
                                                <div style={{ display: 'flex', gap: '0.5rem' }}>
                                                    <button onClick={() => setEditingTask({ ...task })}
                                                        style={{ fontSize: '0.8rem', padding: '0.2rem 0.6rem', border: '1px solid #7c3aed', color: '#7c3aed', background: 'none', borderRadius: '4px', cursor: 'pointer' }}>
                                                        編集
                                                    </button>
                                                    <button onClick={() => handleDeleteTask(task.id)}
                                                        style={{ fontSize: '0.8rem', padding: '0.2rem 0.6rem', border: '1px solid #ef4444', color: '#ef4444', background: 'none', borderRadius: '4px', cursor: 'pointer' }}>
                                                        削除
                                                    </button>
                                                </div>
                                            </>
                                        )}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </section>
                )}
            </main>
        </div>
    );
}