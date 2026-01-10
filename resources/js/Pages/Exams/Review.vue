<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    exam: Object,
    questions: Array,
    tryout: Object
});

// Helper untuk mengecek jawaban user
const isUserChoice = (questionId, option) => {
    return props.exam.answers[questionId] === option;
};

// Helper untuk menentukan warna border opsi
const getOptionClass = (q, opt) => {
    const userAns = props.exam.answers[q.id];
    const isCorrect = opt === q.correct_answer;
    const isWrong = (userAns === opt && userAns !== q.correct_answer);

    if (isCorrect) return 'border-emerald-500 bg-emerald-50 text-emerald-700';
    if (isWrong) return 'border-rose-500 bg-rose-50 text-rose-700';
    return 'border-slate-100 bg-slate-50 text-slate-400';
};
</script>

<template>
    <Head :title="'Pembahasan - ' + tryout.name" />

    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            
            <div class="mb-8">
                <Link :href="route('exam.history')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600">
                    ← Kembali ke Riwayat
                </Link>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter mt-2 leading-tight">
                    Pembahasan:<br>{{ tryout.name }}
                </h2>
                <div class="flex gap-2 mt-4">
                    <span class="px-3 py-1 bg-slate-900 text-white rounded-lg text-[8px] font-black uppercase tracking-widest italic">Skor: {{ exam.total_score }}</span>
                    <span :class="exam.is_passed ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'" class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest italic">
                        {{ exam.is_passed ? 'Lulus PG' : 'Gagal PG' }}
                    </span>
                </div>
            </div>

            <div v-for="(q, index) in questions" :key="q.id" class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
                
                <div class="p-6 lg:p-10">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor {{ index + 1 }}</span>
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[9px] font-black uppercase tracking-widest italic">{{ q.subject }}</span>
                    </div>

                    <p class="text-slate-800 font-bold leading-relaxed mb-8 text-sm lg:text-base">
                        {{ q.content }}
                    </p>

                    <div class="space-y-3 mb-8">
                        <div v-for="opt in ['A', 'B', 'C', 'D', 'E']" :key="opt"
                            :class="getOptionClass(q, opt)"
                            class="flex items-center p-4 rounded-2xl border-2 transition-all">
                            
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-black mr-4 shrink-0 border"
                                :class="opt === q.correct_answer ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white border-slate-200'">
                                {{ opt }}
                            </div>

                            <span class="text-xs font-bold">{{ q['option_' + opt.toLowerCase()] }}</span>

                            <span v-if="opt === q.correct_answer" class="ml-auto text-[10px] font-black uppercase italic tracking-widest">Kunci</span>
                            <span v-else-if="isUserChoice(q.id, opt)" class="ml-auto text-[10px] font-black uppercase italic tracking-widest">Anda</span>
                        </div>
                    </div>

                    <div class="bg-slate-900 rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden">
                        <p class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-3 italic">Penjelasan Jawaban</p>
                        <p class="text-xs font-medium leading-relaxed opacity-90 italic">
                            {{ q.explanation || 'Pembahasan belum tersedia untuk soal ini. Silakan hubungi admin jika memerlukan penjelasan lebih lanjut.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="py-10 text-center">
                <Link :href="route('dashboard')" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest border-b-2 border-indigo-100 pb-1">
                    Kembali ke Dashboard
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>