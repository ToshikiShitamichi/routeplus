import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import {
    fetchStudents, fetchStudentProgress,
    createInvitation, fetchInvitations,
    fetchAdminTasks, createAdminTask, updateAdminTask, deleteAdminTask,
} from '../../api/admin';
import {
    fetchPacks, createPack, deletePack,
    assignPackToGroup, fetchPack, reorderPackItems, removePackFromGroup,
} from '../../api/packs';
import { fetchMyGroups } from '../../api/groups';
import styles from './style.module.scss';

export default function AdminDashboard() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();

    const [tab, setTab] = useState('students');
    const [students, setStudents] = useState([]);
    const [invitations, setInvitations] = useState([]);
    const [groups, setGroups] = useState([]);
    const [packs, setPacks] = useState([]);
    const [selectedStudent, setSelectedStudent] = useState(null);
    const [studentProgress, setStudentProgress] = useState(null);
    const [loading, setLoading] = useState(true);

    // 招待フォーム
    const [label, setLabel] = useState('');
    const [maxUses, setMaxUses] = useState(0);
    const [inviteResult, setInviteResult] = useState(null);
    const [copied, setCopied] = useState(false);

    // パック作成フォーム
    const [packName, setPackName] = useState('');
    const [packDesc, setPackDesc] = useState('');
    const [packIsPublic, setPackIsPublic] = useState(false);
    const [selectedTaskIds, setSelectedTaskIds] = useState([]);
    const [allTasks, setAllTasks] = useState([]);
    const [packDetail, setPackDetail] = useState(null);
    const [reordering, setReordering] = useState(false);
    const [assignGroupIds, setAssignGroupIds] = useState({});

    // オリジナル課題管理
    const [customTasks, setCustomTasks] = useState([]);
    const [taskForm, setTaskForm] = useState({
        category: '', order: 1, level: 1, title: '', description: '', visibility: 'private'
    });
    const [editingTask, setEditingTask] = useState(null);
    const [taskError, setTaskError] = useState('');
    const [isNewCategory, setIsNewCategory] = useState(false);
    const [openCategories, setOpenCategories] = useState({});

    const toggleCategory = (cat) =>
        setOpenCategories(prev => ({ ...prev, [cat]: !prev[cat] }));

    const statusLabel = { todo: '未着手', in_progress: '進行中', done: '完了' };

    useEffect(() => {
        let done = 0;
        const checkDone = () => { if (++done >= 3) setLoading(false); };

        fetchStudents().then(setStudents).catch(console.error).finally(checkDone);
        fetchInvitations().then(setInvitations).catch(console.error).finally(checkDone);
        fetchPacks().then(setPacks).catch(console.error).finally(checkDone);

        // グループ一覧取得（パック割り当て用）
        import('../../api/admin').then(({ fetchStudents: _ }) => { });
        import('../../lib/axios').then(({ default: api }) => {
            api.get('/api/admin/groups').then(r => setGroups(r.data)).catch(console.error);
        });
    }, []);

    // 全課題を取得（パック作成用）
    useEffect(() => {
        if (tab === 'packs') {
            (async () => {
                try {
                    const { default: api } = await import('../../lib/axios');
                    const official = await api.get('/api/admin/all-tasks').then(r => r.data);
                    const custom = await fetchAdminTasks();
                    const customTagged = custom.map(t => ({ ...t, _isCustom: true }));
                    setAllTasks([...official, ...customTagged]);
                } catch (e) {
                    console.error('課題取得エラー:', e);
                }
            })();
        }
    }, [tab]);

    useEffect(() => {
        if (tab === 'tasks') {
            fetchAdminTasks().then(setCustomTasks).catch(console.error);
        }
    }, [tab]);

    const handleSelectStudent = async (student) => {
        setSelectedStudent(student);
        setStudentProgress(null);
        const data = await fetchStudentProgress(student.id);
        setStudentProgress(data);
    };

    const handleCreateInvitation = async () => {
        if (!label.trim()) return;
        const result = await createInvitation(label, maxUses);
        setInviteResult(result);
        setLabel('');
        const updated = await fetchInvitations();
        setInvitations(updated);
    };

    const handleCopy = (url) => {
        navigator.clipboard.writeText(url);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    const handleCreatePack = async () => {
        if (!packName.trim() || selectedTaskIds.length === 0) return;
        await createPack({
            name: packName,
            description: packDesc,
            isPublic: packIsPublic,
            taskIds: selectedTaskIds,
        });
        setPackName('');
        setPackDesc('');
        setSelectedTaskIds([]);
        const updated = await fetchPacks();
        setPacks(updated);
    };

    const handleDeletePack = async (id) => {
        await deletePack(id);
        const updated = await fetchPacks();
        setPacks(updated);
    };

    const handleViewPack = async (id) => {
        const data = await fetchPack(id);
        data.tasks = [...data.tasks].sort((a, b) => a.pack_order - b.pack_order);
        setPackDetail(data);
    };

    const handleMoveTask = async (taskId, direction) => {
        if (!packDetail) return;
        const tasks = [...packDetail.tasks];
        const idx = tasks.findIndex(t => t.id === taskId);
        if (direction === 'up' && idx === 0) return;
        if (direction === 'down' && idx === tasks.length - 1) return;

        const swapIdx = direction === 'up' ? idx - 1 : idx + 1;
        [tasks[idx], tasks[swapIdx]] = [tasks[swapIdx], tasks[idx]];

        const newDetail = { ...packDetail, tasks };
        setPackDetail(newDetail);

        setReordering(true);
        try {
            const items = tasks.map((t, i) => ({ id: t.pack_item_id, order: i + 1 }));
            await reorderPackItems(packDetail.id, items);
        } catch (e) {
            console.error('順番更新失敗', e);
        } finally {
            setReordering(false);
        }
    };

    const handleAssignPack = async (packId) => {
        const groupId = assignGroupIds[packId];
        if (!groupId) return;
        await assignPackToGroup(packId, Number(groupId));
        const updated = await fetchPacks();
        setPacks(updated);
    };

    const handleRemovePackFromGroup = async (packId, groupId) => {
        await removePackFromGroup(packId, groupId);
        const updated = await fetchPacks();
        setPacks(updated);
    };

    const handleTaskFormChange = (e) => {
        const { name, value } = e.target;
        if (name === 'category') {
            // カテゴリが変わったら順番を自動リセット
            const orders = customTasks
                .filter(t => t.category === value)
                .map(t => t.order);
            const next = orders.length > 0 ? Math.max(...orders) + 1 : 1;
            setTaskForm(prev => ({ ...prev, category: value, order: next }));
        } else {
            setTaskForm(prev => ({ ...prev, [name]: value }));
        }
    };

    const handleCreateTask = async () => {
        setTaskError('');
        if (!taskForm.title.trim() || !taskForm.category.trim() || !taskForm.description.trim()) {
            setTaskError('カテゴリ・タイトル・説明は必須です');
            return;
        }
        try {
            await createAdminTask(taskForm);
            setTaskForm({ category: '', order: 1, level: 1, title: '', description: '', visibility: 'private' });
            const updated = await fetchAdminTasks();
            setCustomTasks(updated);
        } catch (e) {
            setTaskError('作成に失敗しました');
        }
    };

    const handleUpdateTask = async () => {
        if (!editingTask) return;
        try {
            await updateAdminTask(editingTask.id, {
                title: editingTask.title,
                description: editingTask.description,
                level: editingTask.level,
                visibility: editingTask.visibility,
            });
            setEditingTask(null);
            const updated = await fetchAdminTasks();
            setCustomTasks(updated);
        } catch (e) {
            setTaskError('更新に失敗しました');
        }
    };

    const handleDeleteTask = async (id) => {
        await deleteAdminTask(id);
        const updated = await fetchAdminTasks();
        setCustomTasks(updated);
    };

    const toggleTaskId = (id) => {
        setSelectedTaskIds(prev =>
            prev.includes(id) ? prev.filter(t => t !== id) : [...prev, id]
        );
    };

    const handleLogout = async () => {
        await logout();
        navigate('/');
    };

    const groupByCategory = (tasks) => {
        const fixed = ['フロントエンド', 'サーバーサイド', 'インフラ'];
        // 固定カテゴリ以外（オリジナル）を末尾に追加
        const extra = [...new Set((tasks || [])
            .map(t => t.category)
            .filter(c => !fixed.includes(c))
        )];
        return [...fixed, ...extra].map(cat => ({
            category: cat,
            tasks: (tasks || []).filter(t => t.category === cat),
        })).filter(g => g.tasks.length > 0);
    };

    // オリジナル課題のカテゴリ一覧（重複排除）
    const customCategories = [...new Set(customTasks.map(t => t.category))];

    // 選択中カテゴリで使用済みの順番一覧（+末尾の次の番号）
    const usedOrders = customTasks
        .filter(t => t.category === taskForm.category)
        .map(t => t.order)
        .sort((a, b) => a - b);
    const nextOrder = usedOrders.length > 0 ? Math.max(...usedOrders) + 1 : 1;

    return (
        <div className={styles.shell}>
            <aside className={styles.sidebar}>
                <div className={styles.brand}>Route+</div>
                <div className={styles.adminBadge}>管理者</div>
                <nav className={styles.nav}>
                    {[
                        { key: 'students', label: '生徒一覧' },
                        { key: 'invitations', label: '招待管理' },
                        { key: 'packs', label: 'パック管理' },
                        { key: 'tasks', label: 'オリジナル課題' },
                    ].map(item => (
                        <div
                            key={item.key}
                            className={`${styles.navItem} ${tab === item.key ? styles.active : ''}`}
                            onClick={() => { setTab(item.key); setSelectedStudent(null); setPackDetail(null); }}
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
                            {tab === 'students'
                                ? selectedStudent ? `${selectedStudent.name} さんの進捗` : '生徒一覧'
                                : tab === 'invitations' ? '招待管理' : tab === 'packs' ? 'パック管理' : 'オリジナル課題管理'}
                        </h1>
                        <p className={styles.userName}>{user?.name} さん（管理者）</p>
                    </div>
                    <button className={styles.logoutButton} onClick={handleLogout}>ログアウト</button>
                </div>

                {loading && <p>読み込み中...</p>}

                {/* ── 生徒一覧 ── */}
                {!loading && tab === 'students' && !selectedStudent && (
                    <section className={styles.section}>
                        {students.length === 0 && <p className={styles.empty}>まだ生徒がいません</p>}
                        {students.map((group) => (
                            <div key={group.label} className={styles.groupBlock}>
                                <h2 className={styles.groupTitle}>
                                    {group.label}
                                    <span className={styles.groupCount}>{group.students.length} 人</span>
                                </h2>
                                <div className={styles.studentGrid}>
                                    {group.students.map((student) => {
                                        const percent = student.total > 0
                                            ? Math.round((student.done / student.total) * 100) : 0;
                                        return (
                                            <div key={student.id} className={styles.studentCard}
                                                onClick={() => handleSelectStudent(student)}>
                                                <div className={styles.studentName}>{student.name}</div>
                                                <div className={styles.studentEmail}>{student.email}</div>
                                                <div className={styles.progressBarWrap}>
                                                    <div className={styles.progressBar} style={{ width: `${percent}%` }} />
                                                </div>
                                                <div className={styles.studentStats}>
                                                    <span className={styles.statDone}>完了 {student.done}</span>
                                                    <span className={styles.statProgress}>進行中 {student.in_progress}</span>
                                                    <span className={styles.statTodo}>未着手 {student.todo}</span>
                                                    <span className={styles.statPercent}>{percent}%</span>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        ))}
                    </section>
                )}

                {/* ── 生徒進捗詳細 ── */}
                {!loading && tab === 'students' && selectedStudent && (
                    <section className={styles.section}>
                        <button className={styles.backButton} onClick={() => setSelectedStudent(null)}>
                            ← 生徒一覧に戻る
                        </button>
                        {!studentProgress && <p>読み込み中...</p>}
                        {studentProgress && (
                            <div className={styles.progressDetail}>
                                {groupByCategory(studentProgress.tasks).map((group) => (
                                    <div key={group.category} className={styles.categoryBlock}>
                                        <h3 className={styles.categoryTitle}>
                                            {group.category}
                                            <span className={styles.categoryCount}>
                                                {group.tasks.filter(t => t.status === 'done').length} / {group.tasks.length} 完了
                                            </span>
                                        </h3>
                                        <ul className={styles.taskList}>
                                            {group.tasks.map(task => (
                                                <li key={task.id} className={styles.taskItem}>
                                                    <span className={styles.taskTitle}>{task.title}</span>
                                                    <div className={styles.taskRight}>
                                                        {task.github_url && (
                                                            <a href={task.github_url} target="_blank"
                                                                rel="noopener noreferrer" className={styles.taskLink}>
                                                                GitHub
                                                            </a>
                                                        )}
                                                        {task.deploy_url && (
                                                            <a href={task.deploy_url} target="_blank" rel="noopener noreferrer"
                                                                className={styles.taskLink}
                                                                style={{ background: '#534AB7', color: '#fff' }}>
                                                                デプロイ
                                                            </a>
                                                        )}
                                                        <span className={`${styles.pill} ${styles[task.status]}`}>
                                                            {statusLabel[task.status]}
                                                        </span>
                                                    </div>
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                ))}
                            </div>
                        )}
                    </section>
                )}

                {/* ── 招待管理 ── */}
                {!loading && tab === 'invitations' && (
                    <section className={styles.section}>
                        <div className={styles.inviteForm}>
                            <h2 className={styles.sectionTitle}>招待リンクを発行</h2>
                            <div className={styles.inviteOptions}>
                                <label className={styles.optionLabel}>最大使用回数</label>
                                <select className={styles.optionSelect} value={maxUses}
                                    onChange={(e) => setMaxUses(Number(e.target.value))}>
                                    <option value={0}>無制限</option>
                                    <option value={10}>10人まで</option>
                                    <option value={30}>30人まで</option>
                                    <option value={50}>50人まで</option>
                                    <option value={100}>100人まで</option>
                                </select>
                            </div>
                            <div className={styles.inviteInputRow}>
                                <input className={styles.inviteInput} type="text"
                                    placeholder="グループ名"
                                    value={label} onChange={(e) => setLabel(e.target.value)} />
                                <button className={styles.inviteButton} onClick={handleCreateInvitation}>
                                    発行する
                                </button>
                            </div>
                            {inviteResult && (
                                <div className={styles.inviteResult}>
                                    <p className={styles.inviteUrl}>{inviteResult.invite_url}</p>
                                    <button className={styles.copyButton}
                                        onClick={() => handleCopy(inviteResult.invite_url)}>
                                        {copied ? 'コピー済み ✓' : 'URLをコピー'}
                                    </button>
                                    <p className={styles.inviteExpiry}>
                                        有効期限: {new Date(inviteResult.expires_at).toLocaleDateString('ja-JP')}
                                    </p>
                                </div>
                            )}
                        </div>
                        <div className={styles.inviteList}>
                            <h2 className={styles.sectionTitle}>発行済み招待</h2>
                            {invitations.length === 0 && <p className={styles.empty}>まだ招待を発行していません</p>}
                            {invitations.map(inv => (
                                <div key={inv.id} className={styles.inviteItem}>
                                    <div className={styles.inviteInfo}>
                                        <span className={`${styles.inviteBadge} ${inv.is_valid ? styles.valid : styles.invalid}`}>
                                            {inv.used_at ? '使用済み' : inv.is_valid ? '有効' : '期限切れ'}
                                        </span>
                                        <span className={styles.inviteEmail}>{inv.label}</span>
                                        <span className={styles.inviteExpiry}>
                                            期限: {new Date(inv.expires_at).toLocaleDateString('ja-JP')}
                                        </span>
                                        <span className={styles.inviteUsage}>
                                            {inv.used_count}人登録済み
                                            {inv.max_uses > 0 ? ` / ${inv.max_uses}人まで` : ' / 無制限'}
                                        </span>
                                    </div>
                                    <button className={styles.copyButton} onClick={() => handleCopy(inv.invite_url)}>
                                        コピー
                                    </button>
                                </div>
                            ))}
                        </div>
                    </section>
                )}

                {/* ── パック管理 ── */}
                {!loading && tab === 'packs' && !packDetail && (
                    <section className={styles.section}>
                        {/* パック作成フォーム */}
                        <div className={styles.packForm}>
                            <h2 className={styles.sectionTitle}>新しいパックを作成</h2>
                            <div className={styles.packInputRow}>
                                <input className={styles.inviteInput} type="text"
                                    placeholder="パック名"
                                    value={packName} onChange={(e) => setPackName(e.target.value)} />
                                <input className={styles.inviteInput} type="text"
                                    placeholder="説明（任意）"
                                    value={packDesc} onChange={(e) => setPackDesc(e.target.value)} />
                                <label className={styles.checkLabel}>
                                    <input type="checkbox" checked={packIsPublic}
                                        onChange={(e) => setPackIsPublic(e.target.checked)} />
                                    他の組織にも公開する
                                </label>
                            </div>

                            {/* 課題選択 */}
                            <div className={styles.taskSelector}>
                                <p className={styles.selectorLabel}>
                                    課題を選択（{selectedTaskIds.length}件選択中）
                                </p>
                                {groupByCategory(allTasks).map(group => (
                                    <div key={group.category} className={styles.selectorCategory}>
                                        {/* カテゴリトグルヘッダー */}
                                        <div
                                            className={styles.selectorCategoryTitle}
                                            style={{ cursor: 'pointer', userSelect: 'none', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}
                                        >
                                            <span
                                                onClick={() => toggleCategory(group.category)}
                                                style={{ flex: 1 }}
                                            >
                                                {group.category}
                                                {group.tasks.some(t => t._isCustom) && (
                                                    <span style={{ fontSize: '0.7rem', marginLeft: '0.5rem', color: '#888' }}>
                                                        ★オリジナル含む
                                                    </span>
                                                )}
                                            </span>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                                                {/* 一括選択・解除ボタン */}
                                                {(() => {
                                                    const allSelected = group.tasks.every(t => selectedTaskIds.includes(t.id));
                                                    const someSelected = group.tasks.some(t => selectedTaskIds.includes(t.id));
                                                    return (
                                                        <button
                                                            style={{
                                                                fontSize: '0.72rem',
                                                                padding: '0.15rem 0.6rem',
                                                                borderRadius: '4px',
                                                                border: '1px solid #7c3aed',
                                                                background: allSelected ? '#7c3aed' : 'white',
                                                                color: allSelected ? 'white' : '#7c3aed',
                                                                cursor: 'pointer',
                                                            }}
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                if (allSelected) {
                                                                    // 全解除
                                                                    setSelectedTaskIds(prev =>
                                                                        prev.filter(id => !group.tasks.map(t => t.id).includes(id))
                                                                    );
                                                                } else {
                                                                    // 全選択
                                                                    setSelectedTaskIds(prev =>
                                                                        [...new Set([...prev, ...group.tasks.map(t => t.id)])]
                                                                    );
                                                                }
                                                            }}
                                                        >
                                                            {allSelected ? '全解除' : '全選択'}
                                                        </button>
                                                    );
                                                })()}
                                                <span
                                                    onClick={() => toggleCategory(group.category)}
                                                    style={{ fontSize: '0.8rem', color: '#aaa' }}
                                                >
                                                    {group.tasks.filter(t => selectedTaskIds.includes(t.id)).length}/{group.tasks.length}件選択
                                                    {openCategories[group.category] ? '▲' : '▼'}
                                                </span>
                                            </div>
                                        </div>

                                        {/* カテゴリ内課題一覧（トグルで開閉） */}
                                        {openCategories[group.category] && (
                                            <div className={styles.selectorItems}>
                                                {group.tasks.map(task => (
                                                    <label key={`${task._isCustom ? 'custom' : 'official'}-${task.id}`} className={styles.selectorItem}>
                                                        <input
                                                            type="checkbox"
                                                            checked={selectedTaskIds.includes(task.id)}
                                                            onChange={() => toggleTaskId(task.id)}
                                                        />
                                                        <span>
                                                            Lv.{task.level} {task.title}
                                                            {task._isCustom && (
                                                                <span style={{ fontSize: '0.7rem', marginLeft: '0.4rem', color: '#888' }}>
                                                                    [オリジナル]
                                                                </span>
                                                            )}
                                                        </span>
                                                    </label>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>

                            <button className={styles.inviteButton} onClick={handleCreatePack}
                                disabled={!packName.trim() || selectedTaskIds.length === 0}>
                                パックを作成
                            </button>
                        </div>

                        {/* パック一覧 */}
                        <div className={styles.packList}>
                            <h2 className={styles.sectionTitle}>パック一覧</h2>
                            {packs.length === 0 && <p className={styles.empty}>まだパックがありません</p>}
                            <div className={styles.packGrid}>
                                {packs.map(pack => (
                                    <div key={pack.id} className={styles.packCard}>
                                        <div className={styles.packCardHeader}>
                                            <div className={styles.packBadges}>
                                                {pack.is_official && (
                                                    <span className={styles.officialBadge}>公式</span>
                                                )}
                                                {pack.is_public && (
                                                    <span className={styles.publicBadge}>公開中</span>
                                                )}
                                                {pack.is_mine && !pack.is_official && (
                                                    <span className={styles.mineBadge}>自組織</span>
                                                )}
                                            </div>
                                            <span className={styles.packCount}>{pack.items_count}課題</span>
                                        </div>
                                        <h3 className={styles.packName}>{pack.name}</h3>
                                        {pack.description && (
                                            <p className={styles.packDesc}>{pack.description}</p>
                                        )}
                                        <div className={styles.packActions}>
                                            <button className={styles.packViewButton}
                                                onClick={() => handleViewPack(pack.id)}>
                                                詳細
                                            </button>
                                            {pack.is_mine && !pack.is_official && (
                                                <button className={styles.packDeleteButton}
                                                    onClick={() => handleDeletePack(pack.id)}>
                                                    削除
                                                </button>
                                            )}
                                        </div>
                                        {/* 割り当て済みグループ */}
                                        {pack.assigned_groups && pack.assigned_groups.length > 0 && (
                                            <div className={styles.assignedGroups}>
                                                <p style={{ fontSize: '0.75rem', color: '#94a3b8', marginBottom: '0.4rem' }}>
                                                    割り当て済み：
                                                </p>
                                                {pack.assigned_groups.map(g => (
                                                    <div key={g.id} style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', marginBottom: '0.25rem' }}>
                                                        <span style={{ fontSize: '0.8rem', background: '#ede9fe', color: '#7c3aed', padding: '0.1rem 0.5rem', borderRadius: '999px' }}>
                                                            {g.name}
                                                        </span>
                                                        <button
                                                            style={{ fontSize: '0.75rem', color: '#ef4444', background: 'none', border: 'none', cursor: 'pointer' }}
                                                            onClick={() => handleRemovePackFromGroup(pack.id, g.id)}
                                                        >
                                                            外す
                                                        </button>
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                        {/* グループ割り当て */}
                                        <div className={styles.packAssign}>
                                            <select className={styles.optionSelect}
                                                value={assignGroupIds[pack.id] || ''}
                                                onChange={(e) => setAssignGroupIds(prev => ({ ...prev, [pack.id]: e.target.value }))}>
                                                <option value="">グループを選択</option>
                                                {groups.map(g => (
                                                    <option key={g.id} value={g.id}>{g.name}</option>
                                                ))}
                                            </select>
                                            <button className={styles.packAssignButton}
                                                onClick={() => handleAssignPack(pack.id)}>
                                                割り当て
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>
                )}

                {/* ── パック詳細 ── */}
                {!loading && tab === 'packs' && packDetail && (
                    <section className={styles.section}>
                        <button className={styles.backButton} onClick={() => setPackDetail(null)}>
                            ← パック一覧に戻る
                        </button>
                        <h2 className={styles.sectionTitle}>{packDetail.name}</h2>
                        {packDetail.description && <p>{packDetail.description}</p>}
                        {reordering && <p style={{ color: '#888', fontSize: '0.85rem' }}>順番を保存中...</p>}
                        <div className={styles.progressDetail}>
                            <ul className={styles.taskList}>
                                {packDetail.tasks.map((task, idx) => (
                                    <li key={task.id} className={styles.taskItem}>
                                        <span className={styles.taskTitle}>
                                            [{task.category}] Lv.{task.level} {task.title}
                                        </span>
                                        <div className={styles.taskRight}>
                                            <button
                                                className={styles.backButton}
                                                onClick={() => handleMoveTask(task.id, 'up')}
                                                disabled={idx === 0}
                                                style={{ padding: '0.2rem 0.6rem' }}
                                            >
                                                ▲
                                            </button>
                                            <button
                                                className={styles.backButton}
                                                onClick={() => handleMoveTask(task.id, 'down')}
                                                disabled={idx === packDetail.tasks.length - 1}
                                                style={{ padding: '0.2rem 0.6rem' }}
                                            >
                                                ▼
                                            </button>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </section>
                )}

                {/* ── オリジナル課題管理 ── */}
                {!loading && tab === 'tasks' && (
                    <section className={styles.section}>

                        {/* 作成フォーム */}
                        <div className={styles.packForm}>
                            <h2 className={styles.sectionTitle}>オリジナル課題を作成</h2>
                            {taskError && <p style={{ color: 'red' }}>{taskError}</p>}
                            <div className={styles.packInputRow}>

                                {/* カテゴリ選択 */}
                                <label className={styles.optionLabel}>カテゴリ</label>
                                {!isNewCategory ? (
                                    <div style={{ display: 'flex', gap: '0.5rem', marginBottom: '0.5rem' }}>
                                        <select
                                            className={styles.inviteInput}
                                            name="category"
                                            value={taskForm.category}
                                            onChange={handleTaskFormChange}
                                            style={{ flex: 1 }}
                                        >
                                            <option value="">カテゴリを選択</option>
                                            {customCategories.map(cat => (
                                                <option key={cat} value={cat}>{cat}</option>
                                            ))}
                                        </select>
                                        <button
                                            className={styles.inviteButton}
                                            style={{ whiteSpace: 'nowrap' }}
                                            onClick={() => {
                                                setIsNewCategory(true);
                                                setTaskForm(prev => ({ ...prev, category: '', order: 1 }));
                                            }}
                                        >
                                            ＋ 新規
                                        </button>
                                    </div>
                                ) : (
                                    <div style={{ display: 'flex', gap: '0.5rem', marginBottom: '0.5rem' }}>
                                        <input
                                            className={styles.inviteInput}
                                            type="text"
                                            name="category"
                                            placeholder="新しいカテゴリ名"
                                            value={taskForm.category}
                                            onChange={handleTaskFormChange}
                                            style={{ flex: 1 }}
                                        />
                                        <button
                                            className={styles.backButton}
                                            style={{ whiteSpace: 'nowrap' }}
                                            onClick={() => {
                                                setIsNewCategory(false);
                                                setTaskForm(prev => ({ ...prev, category: '', order: 1 }));
                                            }}
                                        >
                                            ← 選択に戻る
                                        </button>
                                    </div>
                                )}

                                {/* 課題タイトル */}
                                <label className={styles.optionLabel}>課題タイトル</label>
                                <input
                                    className={styles.inviteInput}
                                    type="text"
                                    name="title"
                                    placeholder="課題タイトル"
                                    value={taskForm.title}
                                    onChange={handleTaskFormChange}
                                />

                                {/* 説明 */}
                                <label className={styles.optionLabel}>説明</label>
                                <div style={{ display: 'flex', gap: '12px', alignItems: 'flex-start' }}>
                                    <textarea
                                        style={{ flex: 1, resize: 'vertical', fontFamily: 'inherit', minHeight: '180px' }}
                                        className={styles.inviteInput}
                                        name="description"
                                        rows={8}
                                        placeholder="テンプレートを参考に記入してください"
                                        value={taskForm.description}
                                        onChange={handleTaskFormChange}
                                    /><div style={{
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
                                    {/* レベル */}
                                    <label className={styles.optionLabel}>
                                        レベル
                                        <select
                                            className={styles.optionSelect}
                                            name="level"
                                            value={taskForm.level}
                                            onChange={handleTaskFormChange}
                                        >
                                            {[1, 2, 3, 4, 5].map(lv => (
                                                <option key={lv} value={lv}>Lv.{lv}</option>
                                            ))}
                                        </select>
                                    </label>

                                    {/* 公開設定 */}
                                    <label className={styles.optionLabel}>
                                        公開設定
                                        <select
                                            className={styles.optionSelect}
                                            name="visibility"
                                            value={taskForm.visibility}
                                            onChange={handleTaskFormChange}
                                        >
                                            <option value="private">非公開（自組織のみ）</option>
                                            <option value="public">公開（他組織も可）</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <button className={styles.inviteButton} onClick={handleCreateTask}>
                                課題を作成
                            </button>
                        </div>

                        {/* 課題一覧 */}
                        <div className={styles.packList}>
                            <h2 className={styles.sectionTitle}>オリジナル課題一覧</h2>
                            {customTasks.length === 0 && <p className={styles.empty}>まだオリジナル課題がありません</p>}
                            <ul className={styles.taskList}>
                                {customTasks.map(task => (
                                    <li key={task.id} className={styles.taskItem}>
                                        {editingTask?.id === task.id ? (
                                            <div style={{ width: '100%', display: 'flex', flexDirection: 'column', gap: '0.5rem', padding: '0.5rem 0' }}>
                                                <input className={styles.inviteInput} type="text"
                                                    placeholder="課題タイトル"
                                                    value={editingTask.title}
                                                    onChange={e => setEditingTask(prev => ({ ...prev, title: e.target.value }))} />
                                                <div style={{ display: 'flex', gap: '12px', alignItems: 'flex-start' }}>
                                                    <textarea
                                                        className={styles.inviteInput}
                                                        rows={8}
                                                        placeholder="説明"
                                                        value={editingTask.description || ''}
                                                        onChange={e => setEditingTask(prev => ({ ...prev, description: e.target.value }))}
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
                                                    <label className={styles.optionLabel}>
                                                        レベル
                                                        <select className={styles.optionSelect}
                                                            value={editingTask.level}
                                                            onChange={e => setEditingTask(prev => ({ ...prev, level: Number(e.target.value) }))}>
                                                            {[1, 2, 3, 4, 5].map(lv => <option key={lv} value={lv}>Lv.{lv}</option>)}
                                                        </select>
                                                    </label>
                                                    <label className={styles.optionLabel}>
                                                        公開設定
                                                        <select className={styles.optionSelect}
                                                            value={editingTask.visibility}
                                                            onChange={e => setEditingTask(prev => ({ ...prev, visibility: e.target.value }))}>
                                                            <option value="private">非公開</option>
                                                            <option value="public">公開</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div style={{ display: 'flex', gap: '0.5rem' }}>
                                                    <button className={styles.inviteButton} onClick={handleUpdateTask}>保存</button>
                                                    <button className={styles.backButton} onClick={() => setEditingTask(null)}>キャンセル</button>
                                                </div>
                                            </div>
                                        ) : (
                                            <>
                                                <span className={styles.taskTitle}>
                                                    [{task.category}] Lv.{task.level} {task.title}
                                                </span>
                                                <div className={styles.taskRight}>
                                                    <span className={`${styles.pill} ${task.visibility === 'public' ? styles.done : styles.in_progress}`}>
                                                        {task.visibility === 'public' ? '公開' : '非公開'}
                                                    </span>
                                                    <button className={styles.packViewButton}
                                                        onClick={() => setEditingTask({ ...task })}>
                                                        編集
                                                    </button>
                                                    <button className={styles.packDeleteButton}
                                                        onClick={() => handleDeleteTask(task.id)}>
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