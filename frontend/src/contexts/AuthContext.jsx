import { createContext, useContext, useState } from "react";
import { fetchUser, login as loginApi, logout as logoutApi } from "../api/auth";

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(false);

    const refreshUser = async () => {
        const userData = await fetchUser();
        setUser(userData);
        return userData;
    };

    const login = async (email, password) => {
        setLoading(true);
        try {
            await loginApi(email, password);
            const userData = await refreshUser();
            return userData;
        } finally {
            setLoading(false);
        }
    };

    const logout = async () => {
        await logoutApi();
        setUser(null);
    };

    return (
        <AuthContext.Provider value={{ user, loading, login, logout, refreshUser }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = useContext(AuthContext);

    if (!context) {
        throw new Error("useAuth must be used within AuthProvider");
    }

    return context;
};