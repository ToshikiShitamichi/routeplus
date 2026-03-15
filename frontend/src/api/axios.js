// frontend/src/api/axios.js
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

export default api;