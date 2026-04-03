import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import api from '../../lib/axios';
import styles from './style.module.scss';

export default function RegisterPublic() {
    const { refreshUser } = useAuth();
    const navigate = useNavigate();
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

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
            await api.post('/auth/register/public', {
                name,
                email,
                password,
                password_confirmation: passwordConfirmation,
            });
            await refreshUser();
            navigate('/select-pack');
        } catch (err) {
            const msg = err.response?.data?.errors
                ? Object.values(err.response.data.errors).flat()[0]
                : err.response?.data?.message ?? '登録に失敗しました';
            setError(msg);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className={styles.page}>
            <div className={styles.container}>
                <div className={styles.logoWrap}>
                    <div className={styles.logo}>Route+</div>
                    <p className={styles.tagline}>新規登録</p>
                </div>

                <div className={styles.card}>
                    <h2 className={styles.title}>アカウントを作成</h2>
                    <form onSubmit={handleSubmit}>
                        <div className={styles.field}>
                            <label className={styles.label}>お名前</label>
                            <input className={styles.input} type="text"
                                placeholder="山田 太郎" value={name}
                                onChange={(e) => setName(e.target.value)}
                                required disabled={loading} />
                        </div>
                        <div className={styles.field}>
                            <label className={styles.label}>メールアドレス</label>
                            <input className={styles.input} type="email"
                                placeholder="you@example.com" value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                required disabled={loading} />
                        </div>
                        <div className={styles.field}>
                            <label className={styles.label}>パスワード（8文字以上）</label>
                            <input className={styles.input} type="password"
                                placeholder="••••••••" value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required disabled={loading} />
                        </div>
                        <div className={styles.field}>
                            <label className={styles.label}>パスワード（確認）</label>
                            <input className={styles.input} type="password"
                                placeholder="••••••••" value={passwordConfirmation}
                                onChange={(e) => setPasswordConfirmation(e.target.value)}
                                required disabled={loading} />
                        </div>

                        {error && <p className={styles.error}>{error}</p>}

                        <button className={styles.button} type="submit" disabled={loading}>
                            {loading ? '登録中...' : '次へ（コースを選ぶ）'}
                        </button>
                    </form>

                    <p className={styles.loginLink}>
                        すでにアカウントをお持ちの方は
                        <span onClick={() => navigate('/')} className={styles.link}>
                            こちら
                        </span>
                    </p>
                </div>
            </div>
        </div>
    );
}