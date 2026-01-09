<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    exams: Object
});

// Helper untuk memformat tanggal
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Riwayat Ujian Saya" />

    <AuthenticatedLayout>
        <div class="mb-10">
            <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter leading-none mb-2">Riwayat Nilai</h2>
            <p class="text-slate-500 font-medium text-sm lg:text-base">Review hasil perjuanganmu dan pelajari setiap kesalahan.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] lg:rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Paket Tryout</th>
                            <th class="px-6 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Skor Detail</th>
                            <th class="px-6 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total</th>
                            <th class="px-10 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Waktu Selesai</th>
                            <th class="px-10 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-bold text-sm">
                        <tr v-for="exam in exams.data" :key="exam.id" class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-10 py-8">
                                <div class="text-slate-900 uppercase italic tracking-tighter text-base">{{ exam.tryout.name }}</div>
                                <div class="text-[10px] text-slate-400 font-black uppercase mt-1">Status: Selesai</div>
                            </td>
                            <td class="px-6 py-8 text-center">
                                <div class="flex justify-center gap-3">
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 uppercase mb-0.5">TWK</p>
                                        <p class="text-xs text-slate-700">{{ exam.score_twk }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 uppercase mb-0.5">TIU</p>
                                        <p class="text-xs text-slate-700">{{ exam.score_tiu }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 uppercase mb-0.5">TKP</p>
                                        <p class="text-xs text-slate-700">{{ exam.score_tkp }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-8 text-center">
                                <span class="text-xl font-black text-indigo-600">{{ exam.total_score }}</span>
                            </td>
                            <td class="px-10 py-8 text-right text-slate-400 text-xs">
                                {{ formatDate(exam.created_at) }}
                            </td>
                            <td class="px-10 py-8 text-right">
                                <Link :href="route('exam.result', exam.id)" class="inline-block bg-slate-900 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-slate-100">
                                    Review
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="lg:hidden divide-y divide-slate-100">
                <div v-for="exam in exams.data" :key="exam.id" class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-black text-slate-900 uppercase italic leading-tight">{{ exam.tryout.name }}</h3>
                            <p class="text-[10px] text-slate-400 font-bold mt-1">{{ formatDate(exam.created_at) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[8px] text-slate-400 uppercase">Total Skor</p>
                            <p class="text-xl font-black text-indigo-600">{{ exam.total_score }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-6 bg-slate-50 p-3 rounded-2xl text-center">
                        <div>
                            <p class="text-[8px] text-slate-400 uppercase">TWK</p>
                            <p class="text-xs font-bold">{{ exam.score_twk }}</p>
                        </div>
                        <div>
                            <p class="text-[8px] text-slate-400 uppercase">TIU</p>
                            <p class="text-xs font-bold">{{ exam.score_tiu }}</p>
                        </div>
                        <div>
                            <p class="text-[8px] text-slate-400 uppercase">TKP</p>
                            <p class="text-xs font-bold">{{ exam.score_tkp }}</p>
                        </div>
                    </div>
                    <Link :href="route('exam.result', exam.id)" class="block w-full text-center bg-slate-900 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest">
                        Lihat Pembahasan
                    </Link>
                </div>
            </div>

            <div v-if="exams.data.length === 0" class="p-20 text-center">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-slate-400 font-bold italic">Kamu belum pernah mengikuti ujian.</p>
                <Link :href="route('marketplace.index')" class="text-indigo-600 text-xs font-black uppercase mt-4 inline-block hover:underline">Mulai Ujian Pertama →</Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>