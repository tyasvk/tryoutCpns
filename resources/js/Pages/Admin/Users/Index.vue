<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({ users: Object, filters: Object });
const search = ref(props.filters.search || '');

watch(search, (value) => {
    router.get(route('admin.users.index'), { search: value }, { preserveState: true, replace: true });
});
</script>

<template>
    <Head title="Manajemen User" />
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black text-slate-900 uppercase italic">Peserta Tryout</h2>
                <input v-model="search" type="text" placeholder="Cari nama atau email..." class="bg-white border-slate-200 rounded-2xl text-xs p-3 w-64 shadow-sm">
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr class="text-[10px] font-black uppercase text-slate-400 tracking-widest">
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Terdaftar</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800 text-sm">{{ user.name }}</td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ user.email }}</td>
                            <td class="px-6 py-4 text-slate-400 text-xs">{{ new Date(user.created_at).toLocaleDateString('id-ID') }}</td>
                            <td class="px-6 py-4 text-center">
                                <Link :href="route('admin.users.show', user.id)" class="text-indigo-600 font-black text-[10px] uppercase hover:underline">
                                    Lihat Riwayat & Edit
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
    </AuthenticatedLayout>
</template>