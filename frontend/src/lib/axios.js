import axios from 'axios';

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true,
    withXSRFToken: true,
});

api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            const url = error.config?.url ?? '';
            const isAuthCheck = url.includes('/api/user');
            if (!isAuthCheck && window.location.pathname !== '/') {
                window.location.href = '/';
            }
        }
        return Promise.reject(error);
    }
);

// ✅ リクエスト前にCookieからXSRF-TOKENを取得してヘッダーに付与
api.interceptors.request.use((config) => {
    const token = getCookie('XSRF-TOKEN');
    if (token) {
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token);
    }
    return config;
});

// CookieからXSRF-TOKENを取得するヘルパー関数
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

export default api;