import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";

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
            } else {
                setErrorMessage("ログインに失敗しました。");
            }
        }
    };

    return (
        <div style={{ padding: "40px" }}>
            <h1>ログイン</h1>

            <form onSubmit={handleSubmit}>
                <div style={{ marginBottom: "12px" }}>
                    <input
                        type="email"
                        placeholder="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                    />
                </div>

                <div style={{ marginBottom: "12px" }}>
                    <input
                        type="password"
                        placeholder="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                    />
                </div>

                {errorMessage && (
                    <p style={{ color: "red", marginBottom: "12px" }}>{errorMessage}</p>
                )}

                <button type="submit" disabled={loading}>
                    {loading ? "ログイン中..." : "ログイン"}
                </button>
            </form>
        </div>
    );
}