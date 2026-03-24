import api from '../lib/axios';

// 自分の所属グループ一覧
export async function fetchMyGroups() {
    const res = await api.get('/api/groups/my');
    return res.data;
}

// 公開グループ一覧
export async function fetchPublicGroups() {
    const res = await api.get('/api/groups/public');
    return res.data;
}

// グループに参加
export async function joinGroup(groupId) {
    const res = await api.post(`/api/groups/${groupId}/join`);
    return res.data;
}