<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    exam: Object,
    tryout: Object,
    questions: Array,
});

// State Jawaban
const form = useForm({
    answers: props.exam.answers || {},
});

// State Navigasi Soal
const currentIndex = ref(0);
const currentQuestion = computed(() => props.questions[currentIndex.value]);

// === LOGIKA TIMER OTOMATIS ===
const timeLeft = ref('');
const timePercentage = ref(100);
let timerInterval = null;

const calculateTime = () => {
    const duration = 100 * 60 * 1000; // 100 Menit dalam milidetik
    const startTime = new Date(props.exam.started_at).getTime();
    const endTime = startTime + duration;
    const now = new Date().getTime();
    const diff = endTime - now;

    if (diff <= 0) {
        clearInterval(timerInterval);
        submitExam(); // Otomatis submit jika waktu habis
        return;
    }

    // Hitung Persentase untuk Progress Bar
    timePercentage.value = (diff / duration) * 100;

    // Format HH:MM:SS
    const h = Math.floor(diff / (1000 * 60 * 60));
    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const s = Math.floor((diff % (1000 * 60)) / 1000);
    
    timeLeft.value = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
};

const submitExam = () => {
    form.post(route('exam.submit', props.exam.id), {
        onBefore: () => confirm('Waktu habis atau Anda yakin ingin mengakhiri ujian?'),
    });
};

onMounted(() => {
    calculateTime();
    timerInterval = setInterval(calculateTime, 1000);
});

onUnmounted(() => {
    clearInterval(timerInterval);
});

// Navigasi Soal
const nextQuestion = () => { if (currentIndex.value < props.questions.length - 1) currentIndex.value++; };
const prevQuestion = () => { if (currentIndex.value > 0) currentIndex.value--; };
const jumpTo = (index) => { currentIndex.value = index; };
</script>

<template>
    <Head title="Simulasi CAT - CPNS Nusantara" />

    <div class="min-h-screen bg-slate-50 font-sans text-slate-900 pb-32">
        
        <div class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-5xl mx-auto px-6 h-20 flex items-center justify-between">
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Sisa Waktu</p>
                    <p class="text-2xl font-black text-slate-900 tabular-nums leading-none">{{ timeLeft }}</p>
                </div>
                <button @click="submitExam" class="bg-rose-500 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-100">
                    Selesai
                </button>
            </div>
            <div class="w-full h-1 bg-slate-100">
                <div class="h-full bg-indigo-600 transition-all duration-1000" :style="{ width: timePercentage + '%' }"></div>
            </div>
        </div>

        <main class="max-w-5xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 lg:p-12 rounded-[2.5rem] border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                            Soal {{ currentIndex + 1 }} dari {{ questions.length }}
                        </span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ currentQuestion.subject }}</span>
                    </div>

                    <p class="text-base lg:text-lg font-bold leading-relaxed mb-10 text-slate-800">
                        {{ currentQuestion.content }}
                    </p>

                    <div class="space-y-3">
                        <button v-for="opt in ['A', 'B', 'C', 'D', 'E']" :key="opt"
                            @click="form.answers[currentQuestion.id] = opt"
                            :class="form.answers[currentQuestion.id] === opt ? 'border-indigo-600 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-600' : 'border-slate-100 bg-slate-50 text-slate-600'"
                            class="w-full flex items-center p-5 rounded-2xl border-2 text-left transition-all group">
                            <div :class="form.answers[currentQuestion.id] === opt ? 'bg-indigo-600 text-white' : 'bg-white border-slate-200 text-slate-400'"
                                class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs mr-4 border shrink-0">
                                {{ opt }}
                            </div>
                            <span class="text-xs lg:text-sm font-bold">{{ currentQuestion['option_' + opt.toLowerCase()] }}</span>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center px-2">
                    <button @click="prevQuestion" :disabled="currentIndex === 0" class="text-[10px] font-black uppercase text-slate-400 disabled:opacity-20">← Sebelumnya</button>
                    <button @click="nextQuestion" :disabled="currentIndex === questions.length - 1" class="text-[10px] font-black uppercase text-indigo-600">Selanjutnya →</button>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6 px-1">Navigasi Soal</h3>
                    <div class="grid grid-cols-5 gap-2">
                        <button v-for="(q, idx) in questions" :key="q.id"
                            @click="jumpTo(idx)"
                            :class="[
                                currentIndex === idx ? 'ring-2 ring-indigo-600 ring-offset-2' : '',
                                form.answers[q.id] ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-400'
                            ]"
                            class="w-full aspect-square rounded-xl flex items-center justify-center text-[10px] font-black transition-all">
                            {{ idx + 1 }}
                        </button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</template>

<style scoped>
/* Tabular nums agar angka timer tidak goyang saat berubah */
.tabular-nums {
    font-variant-numeric: tabular-nums;
}
</style>