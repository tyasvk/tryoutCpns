<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    exam: Object,
});
</script>

<template>
    <Head title="Hasil Ujian - CPNS Nusantara" />

    <AuthenticatedLayout>
        <template #header>Hasil Simulasi CAT</template>

        <div class="max-w-3xl mx-auto space-y-6 pb-20">
            
            <div :class="exam.is_passed ? 'bg-emerald-600 shadow-emerald-100' : 'bg-rose-600 shadow-rose-100'" 
                class="rounded-[2.5rem] p-10 text-white text-center shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] mb-4 opacity-80">Status Kelulusan</p>
                    <h2 class="text-4xl lg:text-5xl font-black uppercase tracking-tighter leading-none mb-2">
                        {{ exam.is_passed ? 'Lulus Passing Grade' : 'Belum Lulus' }}
                    </h2>
                    <p class="text-sm font-medium opacity-90">
                        {{ exam.tryout?.name }} • {{ exam.completed_at }}
                    </p>
                </div>
                <div class="absolute -right-6 -bottom-6 text-9xl opacity-10">
                    {{ exam.is_passed ? '🎉' : '📚' }}
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200 text-center shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Skor TWK</p>
                    <p class="text-4xl font-black text-slate-900">{{ exam.score_twk }}</p>
                    <p class="text-[10px] font-bold text-emerald-500 mt-2">MIN. 65</p>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200 text-center shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Skor TIU</p>
                    <p class="text-4xl font-black text-slate-900">{{ exam.score_tiu }}</p>
                    <p class="text-[10px] font-bold text-emerald-500 mt-2">MIN. 80</p>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200 text-center shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Skor TKP</p>
                    <p class="text-4xl font-black text-slate-900">{{ exam.score_tkp }}</p>
                    <p class="text-[10px] font-bold text-emerald-500 mt-2">MIN. 166</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a :href="route('exam.certificate', exam.id)" 
                    :class="exam.is_passed ? 'bg-emerald-600' : 'bg-slate-900'"
                    class="text-white p-5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center shadow-xl flex items-center justify-center gap-2 transition-all">
                    <span>📄</span> Unduh Sertifikat Hasil
                </a>

                <Link :href="route('exam.review', exam.id)" 
                    class="bg-white border border-slate-200 text-slate-900 p-5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center shadow-sm flex items-center justify-center gap-2">
                    <span>🔍</span> Lihat Pembahasan Soal
                </Link>

                <Link :href="route('tryout.leaderboard', exam.tryout_id)" 
                    class="bg-white border border-slate-200 text-slate-900 p-5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center shadow-sm flex items-center justify-center gap-2">
                    <span>🥇</span> Cek Peringkat Nasional
                </Link>

                <Link :href="route('exam.analysis')" 
                    class="bg-indigo-600 text-white p-5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center shadow-xl shadow-indigo-50 flex items-center justify-center gap-2">
                    <span>📊</span> Analisis Kemampuan
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>