import { createContext, useContext, useEffect, useState } from "react";
import { fetchUser, login as loginApi, logout as logoutApi } from "../api/auth";

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(false);
    const [authChecked, setAuthChecked] = useState(false);

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
        setLoading(true);
        try {
            await logoutApi();
            setUser(null);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        const initAuth = async () => {
            try {
                await refreshUser();
            } catch (error) {
                setUser(null);
            } finally {
                setAuthChecked(true);
            }
        };

        initAuth();
    }, []);

    return (
        <AuthContext.Provider
            value={{
                user,
                loading,
                authChecked,
                login,
                logout,
                refreshUser,
            }}
        >
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