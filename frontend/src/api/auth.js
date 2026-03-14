import api from "../lib/axios";

export const getCsrfCookie = async () => {
    await api.get("/sanctum/csrf-cookie");
};

export const login = async (email, password) => {
    await getCsrfCookie();

    const res = await api.post("/auth/login", {
        email,
        password,
    });

    return res.data;
};

export const fetchUser = async () => {
    const res = await api.get("/api/user");
    return res.data;
};

export const logout = async () => {
    const res = await api.post("/auth/logout");
    return res.data;
};