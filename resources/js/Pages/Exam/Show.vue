<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    exam: Object,
    questions: Array,
});

const currentNumber = ref(0);
const answers = ref(props.exam.answers || {});
const violations = ref(props.exam.violations || 0);
const timeLeft = ref(0);

// Timer Logic
const formatTime = (seconds) => {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    return `${h}:${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
};

// Anti-Cheat: Visibility Change
const handleVisibility = () => {
    if (document.hidden) {
        axios.post(route('exam.violation', props.exam.id))
            .then(res => {
                violations.value = res.data.current_violations;
                if (res.data.status === 'forced_stop') {
                    alert('Ujian dihentikan paksa karena pelanggaran batas tab-switching.');
                    window.location.href = route('exam.result', props.exam.id);
                } else {
                    alert(`Peringatan! Jangan tinggalkan tab ujian. Pelanggaran: ${violations.value}/3`);
                }
            });
    }
};

const selectAnswer = (qId, choice) => {
    answers.value[qId] = choice;
};

const submitExam = () => {
    if (confirm('Yakin ingin mengakhiri ujian?')) {
        router.post(route('exam.submit', props.exam.id), { answers: answers.value });
    }
};

onMounted(() => {
    // Hitung sisa waktu (detik)
    const startTime = new Date(props.exam.started_at).getTime();
    const duration = (props.exam.tryout.duration || 100) * 60;
    const now = new Date().getTime();
    timeLeft.value = Math.max(0, Math.floor(duration - (now - startTime) / 1000));

    const interval = setInterval(() => {
        if (timeLeft.value > 0) timeLeft.value--;
        else submitExam();
    }, 1000);

    document.addEventListener('visibilitychange', handleVisibility);
    onUnmounted(() => {
        clearInterval(interval);
        document.removeEventListener('visibilitychange', handleVisibility);
    });
});
</script>

<template>
    <Head title="Simulasi Ujian" />
    <div class="min-h-screen bg-slate-50 font-sans p-4 lg:p-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative">
                    <div class="flex justify-between items-center mb-8">
                        <span class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest">
                            Pertanyaan {{ currentNumber + 1 }}
                        </span>
                        <div class="flex items-center gap-2">
                            <span v-if="violations > 0" class="text-red-500 text-[10px] font-black animate-pulse uppercase">Violation: {{ violations }}/3</span>
                        </div>
                    </div>

                    <p class="text-lg font-bold text-slate-800 leading-relaxed mb-10">
                        {{ questions[currentNumber].content }}
                    </p>

                    <div class="space-y-3">
                        <button v-for="opt in ['A','B','C','D','E']" :key="opt"
                            @click="selectAnswer(questions[currentNumber].id, opt)"
                            :class="answers[questions[currentNumber].id] === opt ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-100' : 'bg-white text-slate-600 border-slate-100 hover:border-indigo-200'"
                            class="w-full text-left p-4 rounded-2xl border-2 transition-all flex items-center gap-4 group"
                        >
                            <span :class="answers[questions[currentNumber].id] === opt ? 'bg-white/20' : 'bg-slate-50'" class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs uppercase">{{ opt }}</span>
                            <span class="text-sm font-medium">{{ questions[currentNumber]['option_' + opt.toLowerCase()] }}</span>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button @click="currentNumber--" :disabled="currentNumber === 0" class="px-8 py-3 bg-white border border-slate-200 rounded-xl font-bold text-xs uppercase disabled:opacity-30">Kembali</button>
                    <button v-if="currentNumber < questions.length - 1" @click="currentNumber++" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-bold text-xs uppercase">Berikutnya</button>
                    <button v-else @click="submitExam" class="px-8 py-3 bg-emerald-500 text-white rounded-xl font-black text-xs uppercase shadow-lg shadow-emerald-100">Selesai Ujian</button>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-slate-950 p-8 rounded-[2rem] text-center text-white shadow-2xl">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2">Sisa Waktu</p>
                    <h2 class="text-4xl font-black tracking-tighter tabular-nums">{{ formatTime(timeLeft) }}</h2>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-4">Navigasi Soal</p>
                    <div class="grid grid-cols-5 gap-2">
                        <button v-for="(q, index) in questions" :key="q.id"
                            @click="currentNumber = index"
                            :class="[
                                currentNumber === index ? 'ring-2 ring-indigo-600 ring-offset-2' : '',
                                answers[q.id] ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-400 border-transparent'
                            ]"
                            class="aspect-square rounded-xl flex items-center justify-center text-[10px] font-black transition-all border"
                        >
                            {{ index + 1 }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>