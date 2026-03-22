import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

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