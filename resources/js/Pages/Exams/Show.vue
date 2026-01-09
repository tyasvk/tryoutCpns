<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';

const props = defineProps({
    exam: Object,
    questions: Array
});

// State Management
const currentIndex = ref(0);
const selectedAnswers = ref({}); // Format: { question_id: 'A' }
const timeLeft = ref(props.exam.tryout.duration * 60);

// Timer Logic
onMounted(() => {
    const timer = setInterval(() => {
        if (timeLeft.value > 0) {
            timeLeft.value--;
        } else {
            clearInterval(timer);
            submitExam(); // Auto-submit jika waktu habis
        }
    }, 1000);
});

const formatTime = (seconds) => {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
};

// Navigation
const currentQuestion = computed(() => props.questions[currentIndex.value]);
const goTo = (index) => { currentIndex.value = index; };

// Progress
const answeredCount = computed(() => Object.keys(selectedAnswers.value).length);

// Submit Exam
const submitExam = () => {
    if (confirm('Yakin ingin mengakhiri ujian? Pastikan semua jawaban tersimpan.')) {
        router.post(route('exam.submit', props.exam.id), {
            answers: selectedAnswers.value
        });
    }
};
</script>

<template>
    <Head title="Simulasi CAT - CPNS Nusantara" />

    <div class="min-h-screen bg-slate-50 flex flex-col">
        
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200 px-6 py-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-black italic uppercase tracking-tighter hidden md:block">CPNS Nusantara<span class="text-indigo-600">.</span></span>
                    <div class="bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-black tracking-widest flex items-center gap-2">
                        <span class="animate-pulse">⏱️</span> {{ formatTime(timeLeft) }}
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="hidden md:block text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Progress</p>
                        <p class="text-xs font-black text-slate-900 uppercase italic">{{ answeredCount }} / {{ questions.length }} Terjawab</p>
                    </div>
                    <button @click="submitExam" class="bg-rose-600 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-700 transition-all shadow-lg shadow-rose-100">
                        Selesai Ujian
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 p-6">
            
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-8">
                        <span class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-indigo-100">
                            {{ currentQuestion.subject }}
                        </span>
                        <span class="text-xs font-black text-slate-300 italic uppercase tracking-widest">Soal No. {{ currentIndex + 1 }}</span>
                    </div>
                    
                    <h2 class="text-lg lg:text-xl font-bold text-slate-800 leading-relaxed mb-10">
                        {{ currentQuestion.question_text }}
                    </h2>

                    <div class="space-y-3">
                        <button 
                            v-for="opt in ['a', 'b', 'c', 'd', 'e']" :key="opt"
                            @click="selectedAnswers[currentQuestion.id] = opt.toUpperCase()"
                            :class="[
                                selectedAnswers[currentQuestion.id] === opt.toUpperCase() 
                                ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-100' 
                                : 'border-slate-100 bg-slate-50 hover:border-slate-300'
                            ]"
                            class="w-full text-left p-5 rounded-2xl border-2 transition-all flex items-center gap-4 group"
                        >
                            <span :class="selectedAnswers[currentQuestion.id] === opt.toUpperCase() ? 'bg-indigo-600 text-white' : 'bg-white text-slate-400 border border-slate-200'" 
                                  class="w-8 h-8 flex items-center justify-center rounded-lg font-black text-xs uppercase transition-colors">
                                {{ opt }}
                            </span>
                            <p class="flex-1 text-sm font-bold text-slate-700">{{ currentQuestion['option_' + opt] }}</p>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center px-2">
                    <button @click="currentIndex--" :disabled="currentIndex === 0" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 disabled:opacity-30">
                        ← Sebelumnya
                    </button>
                    <button @click="currentIndex++" :disabled="currentIndex === questions.length - 1" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-900 hover:text-indigo-600 disabled:opacity-30">
                        Selanjutnya →
                    </button>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-200 shadow-sm sticky top-28">
                    <h3 class="text-xs font-black text-slate-900 uppercase italic tracking-widest mb-6 px-1">Navigasi Soal</h3>
                    
                    <div class="grid grid-cols-5 gap-2">
                        <button 
                            v-for="(q, idx) in questions" :key="q.id"
                            @click="goTo(idx)"
                            :class="[
                                currentIndex === idx ? 'ring-2 ring-indigo-600 ring-offset-2 scale-110 z-10' : '',
                                selectedAnswers[q.id] ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-400 border-slate-100 hover:border-slate-300'
                            ]"
                            class="aspect-square flex items-center justify-center rounded-xl border text-[10px] font-black transition-all"
                        >
                            {{ idx + 1 }}
                        </button>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-50 flex justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-slate-900 rounded-sm"></div>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Terjawab</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-slate-50 border border-slate-200 rounded-sm"></div>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Belum</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</template>

<style scoped>
/* Transisi Smooth */
.bg-white { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
button:active { transform: scale(0.98); }

/* Custom Scrollbar untuk Navigator jika banyak soal */
.grid { max-height: 400px; overflow-y: auto; padding-right: 4px; }
.grid::-webkit-scrollbar { width: 4px; }
.grid::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>