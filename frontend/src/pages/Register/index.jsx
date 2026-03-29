import { useEffect, useState } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { verifyInvitation, registerWithInvitation } from '../../api/admin';
import api from '../../lib/axios';
import styles from './style.module.scss';

export default function RegisterPage() {
    const navigate = useNavigate();
    const [searchParams] = useSearchParams();
    const token = searchParams.get('token');
    const { user } = useAuth();

    const [orgName, setOrgName] = useState('');
    const [prefillEmail, setPrefillEmail] = useState('');
    const [tokenError, setTokenError] = useState('');
    const [tokenLoading, setTokenLoading] = useState(true);
    const [mode, setMode] = useState('select'); // 'select' | 'register' | 'login'

    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);
    const [joined, setJoined] = useState(false);

    useEffect(() => {
        if (!token) {
            setTokenError('招待リンクが無効です');
            setTokenLoading(false);
            return;
        }
        verifyInvitation(token)
            .then((data) => {
                setOrgName(data.organization);
                if (data.email) {
                    setEmail(data.email);
                    setPrefillEmail(data.email);
                }
            })
            .catch(() => setTokenError('招待リンクが無効または期限切れです'))
            .finally(() => setTokenLoading(false));
    }, [token]);

    // ログイン済みユーザーが招待リンクを開いた場合
    const handleJoin = async () => {
        setLoading(true);
        setError('');
        try {
            await api.post('/api/invitations/join', { token });
            setJoined(true);
        } catch (err) {
            setError(err.response?.data?.message ?? '参加に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    const handleRegister = async (e) => {
        e.preventDefault();
        setError('');
        if (password !== passwordConfirmation) {
            setError('パスワードが一致しません');
            return;
        }
        setLoading(true);
        try {
            await registerWithInvitation({ name, email, password, password_confirmation: passwordConfirmation, token });
            navigate('/dashboard');
        } catch (err) {
            const msg = err.response?.data?.errors
                ? Object.values(err.response.data.errors).flat()[0]
                : err.response?.data?.message ?? '登録に失敗しました';
            setError(msg);
        } finally {
            setLoading(false);
        }
    };

    const handleLogin = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);
        try {
            await api.get('/sanctum/csrf-cookie');
            await api.post('/auth/login', { email, password });
            // ログイン後にグループ参加
            await api.post('/api/invitations/join', { token });
            navigate('/dashboard');
        } catch (err) {
            setError(err.response?.data?.message ?? 'ログインに失敗しました');
        } finally {
            setLoading(false);
        }
    };

    if (tokenLoading) return <div className={styles.page}><p>招待リンクを確認中...</p></div>;

    if (tokenError) {
        return (
            <div className={styles.page}>
                <div className={styles.container}>
                    <div className={styles.logoWrap}><div className={styles.logo}>ROUTEPLUS</div></div>
                    <div className={styles.card}>
                        <p className={styles.errorMessage}>{tokenError}</p>
                        <button className={styles.button} onClick={() => navigate('/')}>ログインページへ</button>
                    </div>
                </div>
            </div>
        );
    }

    // 参加完了
    if (joined) {
        return (
            <div className={styles.page}>
                <div className={styles.container}>
                    <div className={styles.logoWrap}><div className={styles.logo}>ROUTEPLUS</div></div>
                    <div className={styles.card}>
                        <p style={{ textAlign: 'center', marginBottom: '1rem' }}>✅ グループに参加しました！</p>
                        <button className={styles.button} onClick={() => navigate('/dashboard')}>
                            ダッシュボードへ
                        </button>
                    </div>
                </div>
            </div>
        );
    }

    // ログイン済みユーザーが招待リンクを開いた場合
    if (user) {
        return (
            <div className={styles.page}>
                <div className={styles.container}>
                    <div className={styles.logoWrap}>
                        <div className={styles.logo}>ROUTEPLUS</div>
                        <p className={styles.tagline}>{orgName} からの招待</p>
                    </div>
                    <div className={styles.card}>
                        <h2 className={styles.title}>グループへの参加</h2>
                        <p style={{ textAlign: 'center', marginBottom: '1.5rem', color: '#64748b' }}>
                            {user.name} さんとして参加します
                        </p>
                        {error && <p className={styles.error}>{error}</p>}
                        <button className={styles.button} onClick={handleJoin} disabled={loading}>
                            {loading ? '参加中...' : 'このグループに参加する'}
                        </button>
                    </div>
                </div>
            </div>
        );
    }

    // 未ログインの場合：新規登録 or ログインして参加
    if (mode === 'select') {
        return (
            <div className={styles.page}>
                <div className={styles.container}>
                    <div className={styles.logoWrap}>
                        <div className={styles.logo}>ROUTEPLUS</div>
                        <p className={styles.tagline}>{orgName} からの招待</p>
                    </div>
                    <div className={styles.card}>
                        <h2 className={styles.title}>参加方法を選択</h2>
                        <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', marginTop: '1.5rem' }}>
                            <button className={styles.button} onClick={() => setMode('register')}>
                                新規アカウントで登録する
                            </button>
                            <button
                                className={styles.button}
                                style={{ background: 'white', color: '#7c3aed', border: '2px solid #7c3aed' }}
                                onClick={() => setMode('login')}
                            >
                                既存アカウントでログインして参加
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    // 新規登録フォーム
    if (mode === 'register') {
        return (
            <div className={styles.page}>
                <div className={styles.container}>
                    <div className={styles.logoWrap}>
                        <div className={styles.logo}>ROUTEPLUS</div>
                        <p className={styles.tagline}>{orgName} からの招待</p>
                    </div>
                    <div className={styles.card}>
                        <h2 className={styles.title}>新規登録</h2>
                        <form onSubmit={handleRegister}>
                            <div className={styles.field}>
                                <label className={styles.label}>お名前</label>
                                <input className={styles.input} type="text" placeholder="山田 太郎"
                                    value={name} onChange={(e) => setName(e.target.value)} required disabled={loading} />
                            </div>
                            <div className={styles.field}>
                                <label className={styles.label}>メールアドレス</label>
                                <input className={styles.input} type="email" placeholder="you@example.com"
                                    value={email} onChange={(e) => setEmail(e.target.value)}
                                    required disabled={loading || !!prefillEmail} />
                            </div>
                            <div className={styles.field}>
                                <label className={styles.label}>パスワード（8文字以上）</label>
                                <input className={styles.input} type="password" placeholder="••••••••"
                                    value={password} onChange={(e) => setPassword(e.target.value)} required disabled={loading} />
                            </div>
                            <div className={styles.field}>
                                <label className={styles.label}>パスワード（確認）</label>
                                <input className={styles.input} type="password" placeholder="••••••••"
                                    value={passwordConfirmation} onChange={(e) => setPasswordConfirmation(e.target.value)}
                                    required disabled={loading} />
                            </div>
                            {error && <p className={styles.error}>{error}</p>}
                            <button className={styles.button} type="submit" disabled={loading}>
                                {loading ? '登録中...' : '登録する'}
                            </button>
                        </form>
                        <button style={{ marginTop: '1rem', background: 'none', border: 'none', color: '#7c3aed', cursor: 'pointer', width: '100%' }}
                            onClick={() => setMode('select')}>
                            ← 戻る
                        </button>
                    </div>
                </div>
            </div>
        );
    }

    // ログインして参加フォーム
    return (
        <div className={styles.page}>
            <div className={styles.container}>
                <div className={styles.logoWrap}>
                    <div className={styles.logo}>ROUTEPLUS</div>
                    <p className={styles.tagline}>{orgName} からの招待</p>
                </div>
                <div className={styles.card}>
                    <h2 className={styles.title}>ログインして参加</h2>
                    <form onSubmit={handleLogin}>
                        <div className={styles.field}>
                            <label className={styles.label}>メールアドレス</label>
                            <input className={styles.input} type="email" placeholder="you@example.com"
                                value={email} onChange={(e) => setEmail(e.target.value)} required disabled={loading} />
                        </div>
                        <div className={styles.field}>
                            <label className={styles.label}>パスワード</label>
                            <input className={styles.input} type="password" placeholder="••••••••"
                                value={password} onChange={(e) => setPassword(e.target.value)} required disabled={loading} />
                        </div>
                        {error && <p className={styles.error}>{error}</p>}
                        <button className={styles.button} type="submit" disabled={loading}>
                            {loading ? 'ログイン中...' : 'ログインしてグループに参加'}
                        </button>
                    </form>
                    <button style={{ marginTop: '1rem', background: 'none', border: 'none', color: '#7c3aed', cursor: 'pointer', width: '100%' }}
                        onClick={() => setMode('select')}>
                        ← 戻る
                    </button>
                </div>
            </div>
        </div>
    );
}