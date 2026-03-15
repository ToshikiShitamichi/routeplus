// frontend/src/api/tasks.js
import api from "../lib/axios";

// タスク一覧取得
export async function fetchTasks() {
    const res = await api.get("/api/tasks");
    return res.data;
}

// ✅ 追加：カテゴリ別にグループ化する関数
// 例：[{category:'フロントエンド', tasks:[...]}, ...]
export function groupByCategory(tasks) {
    const map = {};

    tasks.forEach((task) => {
        const cat = task.category ?? '未分類';
        if (!map[cat]) {
            map[cat] = { category: cat, tasks: [] };
        }
        map[cat].tasks.push(task);
    });

    return Object.values(map);
}