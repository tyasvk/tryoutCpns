<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({ users: Array });

const toggleAdmin = (user) => {
    if (confirm(`Ubah status akses ${user.name}?`)) {
        router.patch(route('admin.users.toggle-admin', user.id));
    }
};

const deleteUser = (user) => {
    if (confirm(`Hapus permanen user ${user.name}?`)) {
        router.delete(route('admin.users.destroy', user.id));
    }
};
</script>

<template>
    <Head title="Kelola User" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4 max-w-2xl mx-auto w-full px-4">
                <Link :href="route('dashboard')" class="text-slate-400">←</Link>
                <h2 class="font-black text-slate-800 uppercase tracking-tighter">Data Peserta</h2>
            </div>
        </template>

        <div class="max-w-2xl mx-auto py-6 px-4 space-y-4">
            <div v-for="user in users" :key="user.id" class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex justify-between items-center group transition-all hover:border-indigo-100">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 text-sm">{{ user.name }}</span>
                        <span v-if="user.is_admin" class="text-[8px] bg-indigo-600 text-white px-1.5 py-0.5 rounded font-black uppercase">Admin</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium">{{ user.email }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="toggleAdmin(user)" class="p-2 text-xs font-black uppercase tracking-tighter transition" :class="user.is_admin ? 'text-red-400' : 'text-indigo-600'">
                        {{ user.is_admin ? 'Demote' : 'Promote' }}
                    </button>
                    <button @click="deleteUser(user)" class="p-2 text-slate-300 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>

            <div v-if="users.length === 0" class="text-center py-20 bg-slate-50 rounded-[2.5rem] text-slate-400 text-xs font-black uppercase italic">
                Belum ada data user.
            </div>
        </div>
    </AuthenticatedLayout>
</template>