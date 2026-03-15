import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import styles from "./style.module.scss";

export default function LoginPage() {
    const { login, loading } = useAuth();
    const navigate = useNavigate();

    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [errorMessage, setErrorMessage] = useState("");

    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrorMessage("");

        try {
            await login(email, password);
            navigate("/dashboard");
        } catch (error) {
            console.error(error);
            if (error.response?.data?.errors?.email?.[0]) {
                setErrorMessage(error.response.data.errors.email[0]);
            } else if (error.response?.data?.message) {
                setErrorMessage(error.response.data.message);
            } else {
                setErrorMessage("ログインに失敗しました。");
            }
        }
    };

    return (
        <div className={styles.page}>
            <div className={styles.container}>

                {/* ロゴ */}
                <div className={styles.logoWrap}>
                    <div className={styles.logo}>ROUTEPLUS</div>
                    <p className={styles.tagline}>学習ロードマップで、着実に前へ。</p>
                </div>

                {/* カード */}
                <div className={styles.card}>
                    <h2 className={styles.title}>ログイン</h2>

                    <form onSubmit={handleSubmit}>
                        <div className={styles.field}>
                            <label className={styles.label}>メールアドレス</label>
                            <input
                                className={styles.input}
                                type="email"
                                placeholder="you@example.com"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                disabled={loading}
                                required
                            />
                        </div>

                        <div className={styles.field}>
                            <label className={styles.label}>パスワード</label>
                            <input
                                className={styles.input}
                                type="password"
                                placeholder="••••••••"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                disabled={loading}
                                required
                            />
                        </div>

                        {/* エラーメッセージ（ない時はスペース確保） */}
                        <div className={styles.errorWrap}>
                            {errorMessage && (
                                <p className={styles.error}>{errorMessage}</p>
                            )}
                        </div>

                        <button
                            className={styles.button}
                            type="submit"
                            disabled={loading}
                        >
                            {loading ? "ログイン中..." : "ログイン"}
                        </button>
                    </form>
                </div>

                <p className={styles.footer}>© 2025 ROUTEPLUS</p>
            </div>
        </div>
    );
}