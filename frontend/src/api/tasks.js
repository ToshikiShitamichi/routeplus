import api from '../lib/axios';

export async function fetchTasks() {
    const res = await api.get('/api/tasks');
    return res.data;
}

export async function fetchTask(id) {
    const res = await api.get(`/api/tasks/${id}`);
    return res.data;
}

export async function updateTaskStatus(id, status) {
    const res = await api.patch(`/api/tasks/${id}/status`, { status });
    return res.data;
}

export async function submitTask(id, { githubUrl, deployUrl, productName }) {
    const res = await api.post(`/api/tasks/${id}/submit`, {
        github_url: githubUrl,
        deploy_url: deployUrl,
        product_name: productName,
    });
    return res.data;
}

export function groupByCategory(tasks) {
    const map = {};
    tasks.forEach((task) => {
        const cat = task.category ?? '未分類';
        if (!map[cat]) map[cat] = { category: cat, tasks: [] };
        map[cat].tasks.push(task);
    });
    return Object.values(map);
}

// 提出済み課題一覧取得
export async function fetchSubmissions() {
    const res = await api.get('/api/submissions');
    return res.data;
}