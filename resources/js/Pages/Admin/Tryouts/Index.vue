<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

// Gunakan default value [] untuk mencegah loop pada data null/undefined
const props = defineProps({
    tryouts: {
        type: Array,
        default: () => []
    }
});

const deleteTryout = (id) => {
    if (confirm('Hapus paket ini secara permanen?')) {
        router.delete(route('admin.tryouts.destroy', id));
    }
};

const getTypeStyle = (type) => {
    const styles = {
        free: 'bg-emerald-50 text-emerald-600 border-emerald-100',
        paid: 'bg-amber-50 text-amber-600 border-amber-100',
        grand: 'bg-rose-50 text-rose-600 border-rose-100'
    };
    return styles[type?.toLowerCase()] || 'bg-slate-50 text-slate-500';
};
</script>

<template>
    <Head title="Inventori Tryout - CPNS Nusantara" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 pb-20">
            
            <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-6 pt-10">
                <div>
                    <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter leading-none mb-2">Package Inventory</h2>
                    <p class="text-slate-500 font-medium italic">Manajemen seluruh materi simulasi CPNS Nusantara.</p>
                </div>
                <Link :href="route('admin.tryouts.create')" class="bg-slate-900 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all">
                    + Create Package
                </Link>
            </div>

            <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Paket</th>
                                <th class="px-6 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Tipe</th>
                                <th class="px-6 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah Soal</th>
                                <th class="px-10 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="item in tryouts" :key="item.id" class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-10 py-8">
                                    <div class="text-slate-900 font-black uppercase italic tracking-tighter text-lg leading-none">{{ item.name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold mt-2">🕒 {{ item.duration }} Menit</div>
                                </td>
                                <td class="px-6 py-8 text-center">
                                    <span :class="getTypeStyle(item.type)" class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border">
                                        {{ item.type || 'Free' }}
                                    </span>
                                </td>
                                <td class="px-6 py-8 text-center">
                                    <div v-if="item.is_published" class="inline-flex items-center gap-1.5 text-emerald-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-[10px] font-black uppercase italic">Published</span>
                                    </div>
                                    <div v-else class="inline-flex items-center gap-1.5 text-slate-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                        <span class="text-[10px] font-black uppercase italic">Draft</span>
                                    </div>
                                </td>
                                <td class="px-6 py-8 text-center">
                                    <Link :href="route('admin.questions.manage', item.id)" class="inline-flex flex-col items-center">
                                        <span class="text-xl font-black text-slate-900">{{ item.questions_count ?? 0 }}</span>
                                        <span class="text-[8px] font-black text-indigo-500 uppercase underline tracking-widest">Kelola Butir</span>
                                    </Link>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Link :href="route('admin.tryouts.edit', item.id)" class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-400 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                            ✏️
                                        </Link>
                                        <button @click="deleteTryout(item.id)" class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-400 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr v-if="tryouts.length === 0">
                                <td colspan="5" class="px-10 py-20 text-center">
                                    <div class="text-4xl mb-4">📦</div>
                                    <p class="text-slate-400 font-bold italic text-sm uppercase">Belum ada paket tryout terdaftar.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>