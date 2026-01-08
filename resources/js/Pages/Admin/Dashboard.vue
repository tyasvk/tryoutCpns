<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    users: Array
});

const isSidebarOpen = ref(false);
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="min-h-screen bg-slate-50 flex">
        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0">
            <div class="p-6 flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center font-bold">T</div>
                <span class="text-xl font-bold tracking-tight text-white">Admin<span class="text-indigo-400">CPNS</span></span>
            </div>

            <nav class="mt-6 px-4 space-y-2">
                <Link :href="route('admin.dashboard')" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 rounded-xl text-sm font-bold">
                    <span>📊 Dashboard</span>
                </Link>
                <Link href="#" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white transition text-sm">
                    <span>📝 Kelola Soal</span>
                </Link>
                <Link href="#" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white transition text-sm">
                    <span>👥 Pengguna</span>
                </Link>
            </nav>
        </aside>

        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-white border-b border-slate-200 px-4 py-4 flex items-center justify-between">
                <button @click="isSidebarOpen = true" class="p-2 lg:hidden text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg font-bold text-slate-800 lg:ml-4">Overview</h1>
                <div class="flex items-center gap-4">
                    <span class="text-xs font-medium text-slate-500 hidden sm:block">{{ $page.props.auth.user.name }}</span>
                    <div class="w-8 h-8 rounded-full bg-slate-200 border border-slate-300"></div>
                </div>
            </header>

            <main class="p-4 sm:p-6 overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Peserta</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2">{{ stats.total_users }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">User Baru (7 Hari)</p>
                        <h3 class="text-3xl font-black text-indigo-600 mt-2">{{ stats.new_users }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Admin Aktif</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2">{{ stats.total_admin }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Pendaftar Terbaru</h3>
                        <button class="text-xs font-bold text-indigo-600">Lihat Semua</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Nama</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest hidden sm:table-cell">Email</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Role</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="user in users" :key="user.id" class="hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 text-sm">{{ user.name }}</div>
                                        <div class="text-[10px] text-slate-400 sm:hidden">{{ user.email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 hidden sm:table-cell">{{ user.email }}</td>
                                    <td class="px-6 py-4">
                                        <span :class="user.is_admin ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-600'" 
                                              class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter">
                                            {{ user.is_admin ? 'Admin' : 'Peserta' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>