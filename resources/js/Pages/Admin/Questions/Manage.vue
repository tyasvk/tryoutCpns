<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Gunakan properti default agar tidak undefined
const props = defineProps({
    tryout: { type: Object, default: () => ({}) },
    questions: { type: Array, default: () => [] },
    stats: { type: Object, default: () => ({ TWK: 0, TIU: 0, TKP: 0 }) }
});

const searchQuery = ref('');
const filterSubject = ref('SEMUA');

// Proteksi filter agar tidak crash jika props.questions kosong
const filteredQuestions = computed(() => {
    if (!props.questions) return [];
    
    return props.questions.filter(q => {
        const content = q.content || ''; // Proteksi jika field content kosong
        const subject = q.subject || '';
        
        const matchesSearch = content.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesSubject = filterSubject.value === 'SEMUA' || subject === filterSubject.value;
        
        return matchesSearch && matchesSubject;
    });
});

const deleteQuestion = (id) => {
    if (confirm('Hapus soal ini?')) {
        router.delete(route('admin.questions.destroy', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head :title="'Kelola Soal - ' + (tryout?.name || '...') " />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            
            <div v-if="!tryout?.id" class="p-10 text-center bg-rose-50 rounded-3xl border border-rose-100">
                <p class="text-rose-600 font-black uppercase text-xs tracking-widest">Data Paket Tidak Ditemukan</p>
            </div>

            <template v-else>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
                    <div>
                        <Link :href="route('admin.tryouts.index')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-all">← DAFTAR PAKET</Link>
                        <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter mt-1">{{ tryout.name }}</h2>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="route('admin.questions.create', tryout.id)" class="bg-slate-900 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 shadow-xl transition-all">
                            + TAMBAH SOAL
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm" v-for="(count, label) in stats" :key="label">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ label }}</p>
                        <p class="text-2xl font-black italic text-slate-900">{{ count }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">SOAL</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="q in filteredQuestions" :key="q.id" class="hover:bg-slate-50/50 transition-all">
                                <td class="p-6">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="px-2 py-1 rounded bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest">{{ q.subject }}</span>
                                        <span class="text-emerald-600 text-[8px] font-black uppercase tracking-widest">Kunci: {{ q.correct_answer }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ q.content }}</p>
                                </td>
                                <td class="p-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Link :href="route('admin.questions.preview', q.id)" class="p-2 bg-slate-100 rounded-lg hover:bg-indigo-600 hover:text-white transition-all">👁️</Link>
                                        <Link :href="route('admin.questions.edit', q.id)" class="p-2 bg-slate-100 rounded-lg hover:bg-slate-900 hover:text-white transition-all">✏️</Link>
                                        <button @click="deleteQuestion(q.id)" class="p-2 bg-slate-100 rounded-lg hover:bg-rose-600 hover:text-white transition-all">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div v-if="filteredQuestions.length === 0" class="p-20 text-center">
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Belum ada data soal</p>
                    </div>
                </div>
            </template>
        </div>
    </AuthenticatedLayout>
</template>