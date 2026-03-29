// frontend/src/api/admin.js

import api from '../lib/axios';

// 生徒一覧
export async function fetchStudents() {
    const res = await api.get('/api/admin/students');
    return res.data;
}

// 特定生徒の進捗
export async function fetchStudentProgress(studentId) {
    const res = await api.get(`/api/admin/students/${studentId}/progress`);
    return res.data;
}

// 招待リンク発行
export async function createInvitation(label, maxUses = 0) {
    const res = await api.post('/api/admin/invitations', {
        label,
        max_uses: maxUses,
    });
    return res.data;
}

// 招待リスト取得
export async function fetchInvitations() {
    const res = await api.get('/api/admin/invitations');
    return res.data;
}

// 招待トークン検証
export async function verifyInvitation(token) {
    const res = await api.get(`/api/invitations/verify?token=${token}`);
    return res.data;
}

// 招待経由の登録
export async function registerWithInvitation({ name, email, password, password_confirmation, token }) {
    await api.get('/sanctum/csrf-cookie');
    const res = await api.post('/auth/register', {
        name,
        email,
        password,
        password_confirmation,
        token,
    });
    return res.data;
}

// オリジナル課題管理
export const fetchAdminTasks = () =>
    api.get('/api/admin/tasks').then(r => r.data);

export const createAdminTask = (data) =>
    api.post('/api/admin/tasks', data).then(r => r.data);

export const updateAdminTask = (id, data) =>
    api.patch(`/api/admin/tasks/${id}`, data).then(r => r.data);

export const deleteAdminTask = (id) =>
    api.delete(`/api/admin/tasks/${id}`).then(r => r.data);