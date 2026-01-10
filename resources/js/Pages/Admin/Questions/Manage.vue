<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    tryout: Object,
    questions: Array
});

const deleteQuestion = (id) => {
    if (confirm('Hapus soal ini?')) {
        router.delete(route('admin.questions.destroy', id));
    }
};
</script>

<template>
    <Head :title="'Kelola Soal - ' + tryout.name" />

    <AuthenticatedLayout>
        <template #header>Kelola Soal: {{ tryout.name }}</template>

        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <Link :href="route('admin.tryouts.index')" class="text-[10px] font-black uppercase tracking-widest text-slate-400">← Kembali ke Paket</Link>
                <Link :href="route('admin.questions.create', tryout.id)" class="bg-indigo-600 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100">
                    Tambah Soal Baru
                </Link>
            </div>

            <div v-if="questions.length === 0" class="bg-white p-20 rounded-[2.5rem] border border-dashed text-center">
                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada soal di paket ini.</p>
            </div>

            <div v-else class="space-y-4">
                <div v-for="(q, index) in questions" :key="q.id" class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col md:flex-row gap-6">
                    <div class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black shrink-0">
                        {{ index + 1 }}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[8px] font-black uppercase tracking-widest">{{ q.subject }}</span>
                        </div>
                        <p class="text-sm font-bold text-slate-800 line-clamp-2 leading-relaxed mb-4">{{ q.content }}</p>
                        
                        <div class="flex gap-2">
                            <Link :href="route('admin.questions.edit', q.id)" class="text-[9px] font-black uppercase tracking-widest bg-slate-100 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all">Edit</Link>
                            <button @click="deleteQuestion(q.id)" class="text-[9px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 px-4 py-2 rounded-lg hover:bg-rose-100 transition-all">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>