import axios from "axios";

const api = axios.create({
    baseURL: "",
    withCredentials: true,
    withXSRFToken: true,
});

export default api;