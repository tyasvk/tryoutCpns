<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    users: Object,
    filters: Object,
    stats: Object
});

const search = ref(props.filters.search);

// Debounce search
watch(search, (value) => {
    router.get(route('admin.users.index'), { search: value }, { preserveState: true, replace: true });
});

const deleteUser = (id) => {
    if (confirm('Hapus user ini selamanya? Seluruh riwayat ujiannya juga akan terhapus.')) {
        router.delete(route('admin.users.destroy', id));
    }
};

const toggleAdmin = (user) => {
    router.patch(route('admin.users.update', user.id), {
        name: user.name,
        email: user.email,
        is_admin: !user.is_admin
    });
};
</script>

<template>
    <Head title="Kelola User - CPNS Nusantara" />

    <AuthenticatedLayout>
        <template #header>Manajemen Pengguna</template>

        <div class="space-y-6 pb-20">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-200">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Peserta</p>
                    <p class="text-3xl font-black text-slate-900">{{ stats.total_users }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-200">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Administrator</p>
                    <p class="text-3xl font-black text-indigo-600">{{ stats.total_admins }}</p>
                </div>
                <div class="bg-slate-900 p-6 rounded-[2rem] text-white">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Daftar Hari Ini</p>
                    <p class="text-3xl font-black text-white">{{ stats.new_today }}</p>
                </div>
            </div>

            <div class="relative">
                <input v-model="search" type="text" placeholder="Cari nama atau email peserta..." 
                    class="w-full bg-white border-slate-200 rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all shadow-sm">
                <span class="absolute right-6 top-4 opacity-20">🔍</span>
            </div>

            <div class="space-y-4">
                <div v-for="user in users.data" :key="user.id" 
                    class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-lg font-black text-slate-400">
                            {{ user.name.charAt(0) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-black uppercase tracking-tight text-slate-900 truncate">{{ user.name }}</h3>
                            <p class="text-[10px] font-bold text-slate-400 truncate">{{ user.email }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button @click="toggleAdmin(user)" 
                            :class="user.is_admin ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400'"
                            class="px-4 py-2 rounded-xl text-[8px] font-black uppercase tracking-widest transition-all">
                            {{ user.is_admin ? 'Admin' : 'Peserta' }}
                        </button>
                        
                        <div class="px-4 py-2 bg-slate-50 rounded-xl text-[8px] font-black uppercase tracking-widest text-slate-500">
                            {{ user.exams_count }} Ujian
                        </div>

                        <button @click="deleteUser(user.id)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-2 mt-8">
                <Link v-for="link in users.links" :key="link.label" 
                    :href="link.url || '#'" 
                    v-html="link.label"
                    :class="[
                        link.active ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-slate-400',
                        !link.url ? 'opacity-30' : 'hover:bg-slate-50'
                    ]"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>