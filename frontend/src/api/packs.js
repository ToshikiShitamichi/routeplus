import api from '../lib/axios';

// パック一覧（管理者）
export async function fetchPacks() {
    const res = await api.get('/api/admin/packs');
    return res.data;
}

// パック詳細
export async function fetchPack(id) {
    const res = await api.get(`/api/admin/packs/${id}`);
    return res.data;
}

// パック作成
export async function createPack({ name, description, isPublic, taskIds }) {
    const res = await api.post('/api/admin/packs', {
        name,
        description,
        is_public: isPublic,
        task_ids: taskIds,
    });
    return res.data;
}

// パック削除
export async function deletePack(id) {
    const res = await api.delete(`/api/admin/packs/${id}`);
    return res.data;
}

// グループにパックを割り当て
export async function assignPackToGroup(packId, groupId) {
    const res = await api.post(`/api/admin/packs/${packId}/assign`, {
        group_id: groupId,
    });
    return res.data;
}

// グループからパックを外す
export async function removePackFromGroup(packId, groupId) {
    const res = await api.delete(`/api/admin/packs/${packId}/remove`, {
        data: { group_id: groupId },
    });
    return res.data;
}

// 公開パック一覧（ユーザー向け）
export async function fetchPublicPacks() {
    const res = await api.get('/api/packs/public');
    return res.data;
}