<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({ exam: Object, questions: Array });

// Helper cek jawaban
const isCorrect = (q) => {
    if (q.type === 'TKP') return true; // TKP selalu benar dengan poin berbeda
    return props.exam.answers[q.id] === q.correct_answer;
};
</script>

<template>
    <Head title="Review Pembahasan" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('exam.result', exam.id)" class="text-slate-400 text-xl">←</Link>
                <h2 class="font-black text-slate-800 uppercase tracking-tighter">Pembahasan Soal</h2>
            </div>
        </template>

        <div class="max-w-2xl mx-auto py-6 px-4 space-y-6">
            <div v-for="(q, index) in questions" :key="q.id" class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-black px-2 py-1 rounded bg-slate-100 text-slate-500 uppercase">{{ q.type }}</span>
                    <span :class="isCorrect(q) ? 'text-emerald-500' : 'text-red-500'" class="text-[10px] font-black uppercase tracking-widest">
                        {{ isCorrect(q) ? 'Tepat' : 'Salah' }}
                    </span>
                </div>

                <p class="text-sm font-bold text-slate-800 leading-relaxed mb-6">{{ index + 1 }}. {{ q.content }}</p>

                <div class="space-y-2 mb-6">
                    <div v-for="opt in ['A','B','C','D','E']" :key="opt" 
                         :class="[
                             exam.answers[q.id] === opt ? (isCorrect(q) ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50') : 'border-slate-50 bg-slate-50/50',
                             q.type !== 'TKP' && q.correct_answer === opt ? 'ring-2 ring-emerald-500 border-transparent' : ''
                         ]"
                         class="p-3 rounded-xl border text-xs flex justify-between items-center">
                        <span class="font-medium text-slate-600"><span class="font-black mr-1">{{ opt }}.</span> {{ q['option_'+opt.toLowerCase()] }}</span>
                        <span v-if="q.type === 'TKP'" class="text-[10px] font-black text-indigo-500">+{{ q['point_'+opt.toLowerCase()] }}</span>
                    </div>
                </div>

                <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-100/50">
                    <h4 class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">Analisis Pembahasan:</h4>
                    <p class="text-xs text-slate-600 leading-relaxed italic">{{ q.explanation || 'Pembahasan belum tersedia untuk soal ini.' }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>