import { useEffect, useState } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import api from '../../lib/axios';
import styles from '../Register/style.module.scss';

export default function RegisterAdminPage() {
    const { refreshUser } = useAuth();
    const navigate = useNavigate();
    const [searchParams] = useSearchParams();
    const token = searchParams.get('token');

    const [orgName, setOrgName] = useState('');
    const [tokenError, setTokenError] = useState('');
    const [tokenLoading, setTokenLoading] = useState(true);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);
    const [organizationName, setOrganizationName] = useState('');

    useEffect(() => {
        if (!token) {
            setTokenError('招待リンクが無効です');
            setTokenLoading(false);
            return;
        }
        api.get(`/api/invitations/verify?token=${token}`)
            .then(res => setOrgName(res.data.organization))
            .catch(() => setTokenError('招待リンクが無効または期限切れです'))
            .finally(() => setTokenLoading(false));
    }, [token]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        if (password !== passwordConfirmation) {
            setError('パスワードが一致しません');
            return;
        }
        setLoading(true);
        try {
            await api.get('/sanctum/csrf-cookie');
            await api.post('/auth/register/admin', {
                name, email, password,
                password_confirmation: passwordConfirmation,
                token,
                organization_name: organizationName,
            });
            await refreshUser();
            navigate('/admin');
        } catch (err) {
            const msg = err.response?.data?.errors
                ? Object.values(err.response.data.errors).flat()[0]
                : err.response?.data?.message ?? '登録に失敗しました';
            setError(msg);
        } finally {
            setLoading(false);
        }
    };

    if (tokenLoading) return <div className={styles.page}><p>確認中...</p></div>;

    if (tokenError) {
        return (
            <div className={styles.page}>
                <div className={styles.container}>
                    <div className={styles.logoWrap}><div className={styles.logo}>Route+</div></div>
                    <div className={styles.card}>
                        <p className={styles.errorMessage}>{tokenError}</p>
                        <button className={styles.button} onClick={() => navigate('/')}>ログインページへ</button>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className={styles.page}>
            <div className={styles.container}>
                <div className={styles.logoWrap}>
                    <div className={styles.logo}>Route+</div>
                    <p className={styles.tagline}>管理者登録</p>
                </div>
                <div className={styles.card}>
                    <h2 className={styles.title}>管理者アカウント登録</h2>
                    <form onSubmit={handleSubmit}>
                        <div className={styles.field}>
                            <label className={styles.label}>スクール名</label>
                            <input className={styles.input} type="text"
                                value={organizationName} onChange={e => setOrganizationName(e.target.value)}
                                placeholder="Route+スクール"
                                required disabled={loading} />
                        </div>
                        <div className={styles.field}>
                            <label className={styles.label}>お名前</label>
                            <input className={styles.input} type="text" placeholder="山田 太郎"
                                value={name} onChange={e => setName(e.target.value)} required disabled={loading} />
                        </div>
                        <div className={styles.field}>
                            <label className={styles.label}>メールアドレス</label>
                            <input className={styles.input} type="email" placeholder="you@example.com"
                                value={email} onChange={e => setEmail(e.target.value)} required disabled={loading} />
                        </div>
                        <div className={styles.field}>
                            <label className={styles.label}>パスワード（8文字以上）</label>
                            <input className={styles.input} type="password" placeholder="••••••••"
                                value={password} onChange={e => setPassword(e.target.value)} required disabled={loading} />
                        </div>
                        <div className={styles.field}>
                            <label className={styles.label}>パスワード（確認）</label>
                            <input className={styles.input} type="password" placeholder="••••••••"
                                value={passwordConfirmation} onChange={e => setPasswordConfirmation(e.target.value)} required disabled={loading} />
                        </div>
                        {error && <p className={styles.error}>{error}</p>}
                        <button className={styles.button} type="submit" disabled={loading}>
                            {loading ? '登録中...' : '管理者登録する'}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}