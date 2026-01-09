<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { Radar } from 'vue-chartjs';
import { 
    Chart as ChartJS, Title, Tooltip, Legend, PointElement, LineElement, 
    RadialLinearScale, Filler 
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, PointElement, LineElement, RadialLinearScale, Filler);

const props = defineProps({
    exam: Object,
    questions: Array,
    user_answers: Object,
    globalAvg: Object,
    analysis: Array
});

// --- State Management ---
const activeTab = ref('ALL');
const selectedQuestionIndex = ref(0);
const isNavOpen = ref(false); // Untuk Modal Nomor Soal di Mobile

// --- Chart Configuration ---
const chartData = {
    labels: ['TWK', 'TIU', 'TKP'],
    datasets: [
        {
            label: 'Kamu',
            backgroundColor: 'rgba(99, 102, 241, 0.2)',
            borderColor: '#6366f1',
            data: [props.exam.score_twk, props.exam.score_tiu, props.exam.score_tkp]
        },
        {
            label: 'Rata-rata',
            backgroundColor: 'rgba(203, 213, 225, 0.2)',
            borderColor: '#94a3b8',
            data: [props.globalAvg.twk, props.globalAvg.tiu, props.globalAvg.tkp]
        }
    ]
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: { r: { suggestedMin: 0, ticks: { display: false } } }
};

// --- Logika Navigasi & Filter ---
const filteredQuestions = computed(() => {
    if (activeTab.value === 'ALL') return props.questions;
    return props.questions.filter(q => q.type === activeTab.value);
});

const currentQuestion = computed(() => filteredQuestions.value[selectedQuestionIndex.value]);

const getStatus = (q) => {
    const userAns = props.user_answers[q.id];
    if (!userAns) return 'UNANSWERED';
    return userAns === q.answer ? 'CORRECT' : 'WRONG';
};

const nextQuestion = () => {
    if (selectedQuestionIndex.value < filteredQuestions.value.length - 1) {
        selectedQuestionIndex.value++;
    }
};

const prevQuestion = () => {
    if (selectedQuestionIndex.value > 0) {
        selectedQuestionIndex.value--;
    }
};
</script>

<template>
    <Head title="Review Pembahasan" />
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto pb-24 lg:pb-0">
            
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <Link :href="route('exam.history')" class="text-[10px] font-black uppercase text-indigo-600 tracking-widest hover:underline">← Back to History</Link>
                    <h2 class="text-2xl lg:text-3xl font-black text-slate-900 uppercase italic tracking-tighter mt-1">{{ exam.tryout.name }}</h2>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <div class="flex-1 md:flex-none bg-white px-4 py-2 rounded-xl border border-slate-200 text-center">
                        <p class="text-[8px] font-black text-slate-400 uppercase">Score</p>
                        <p class="text-lg font-black text-indigo-600">{{ exam.total_score }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm">
                        <h3 class="text-[10px] font-black uppercase text-slate-400 mb-6 text-center tracking-widest">Performance Radar</h3>
                        <div class="h-64">
                            <Radar :data="chartData" :options="chartOptions" />
                        </div>
                    </div>

                    <div class="hidden lg:block bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm sticky top-24">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Navigasi Nomor</p>
                        <div class="grid grid-cols-5 gap-2 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            <button v-for="(q, index) in filteredQuestions" :key="q.id"
                                @click="selectedQuestionIndex = index"
                                :class="[
                                    selectedQuestionIndex === index ? 'ring-2 ring-indigo-500 ring-offset-2 scale-110' : '',
                                    getStatus(q) === 'CORRECT' ? 'bg-emerald-500 text-white' : 
                                    getStatus(q) === 'WRONG' ? 'bg-rose-500 text-white' : 'bg-slate-100 text-slate-400'
                                ]"
                                class="h-10 rounded-xl text-[10px] font-black transition-all">
                                {{ index + 1 }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-1.5 rounded-2xl border border-slate-200 flex overflow-x-auto no-scrollbar gap-1">
                        <button v-for="tab in ['ALL', 'TWK', 'TIU', 'TKP']" :key="tab"
                            @click="activeTab = tab; selectedQuestionIndex = 0"
                            :class="activeTab === tab ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'"
                            class="flex-1 min-w-[70px] py-2.5 rounded-xl text-[10px] font-black uppercase transition-all">
                            {{ tab }}
                        </button>
                    </div>

                    <div v-if="currentQuestion" class="bg-white p-6 lg:p-12 rounded-[2.5rem] lg:rounded-[3rem] border border-slate-200 shadow-sm relative transition-all">
                        <div class="flex justify-between items-center mb-8">
                            <span class="text-[10px] font-black uppercase text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full italic">{{ currentQuestion.type }}</span>
                            <span :class="getStatus(currentQuestion) === 'CORRECT' ? 'text-emerald-500' : 'text-rose-500'" class="text-[10px] font-black uppercase italic tracking-widest">
                                ● {{ getStatus(currentQuestion) === 'CORRECT' ? 'Correct' : 'Incorrect' }}
                            </span>
                        </div>

                        <div class="prose prose-slate max-w-none">
                            <p class="text-lg lg:text-xl font-bold text-slate-800 leading-relaxed">{{ currentQuestion.content }}</p>
                            <img v-if="currentQuestion.image" :src="'/storage/' + currentQuestion.image" class="rounded-3xl mt-6 border border-slate-100 w-full object-cover" />
                        </div>

                        <div class="mt-10 space-y-3">
                            <div v-for="opt in ['a', 'b', 'c', 'd', 'e']" :key="opt"
                                :class="[
                                    currentQuestion.answer === opt.toUpperCase() ? 'bg-emerald-50 border-emerald-200 ring-1 ring-emerald-500' : 
                                    (user_answers[currentQuestion.id] === opt.toUpperCase() && getStatus(currentQuestion) === 'WRONG' ? 'bg-rose-50 border-rose-200 ring-1 ring-rose-500' : 'bg-slate-50 border-transparent')
                                ]"
                                class="p-4 lg:p-5 rounded-2xl border flex items-center gap-4 transition-all">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-black uppercase text-xs flex-shrink-0"
                                    :class="currentQuestion.answer === opt.toUpperCase() ? 'bg-emerald-500 text-white' : 'bg-white text-slate-300 border border-slate-100'">
                                    {{ opt }}
                                </span>
                                <span class="text-sm font-bold text-slate-700 leading-tight">{{ currentQuestion['option_' + opt] }}</span>
                            </div>
                        </div>

                        <div class="mt-10 bg-slate-900 p-6 lg:p-10 rounded-[2.5rem] text-white overflow-hidden relative group">
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="text-xl">💡</span>
                                    <h4 class="font-black uppercase italic tracking-tighter text-sm">Analisis Pembahasan</h4>
                                </div>
                                <p class="text-slate-300 text-sm leading-relaxed font-medium">
                                    {{ currentQuestion.explanation || 'Pembahasan belum tersedia untuk soal ini.' }}
                                </p>
                            </div>
                            <div class="absolute -right-4 -bottom-4 text-white/5 text-8xl font-black italic">HOTS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-slate-200 p-4 z-[60] flex items-center justify-between gap-4">
            <button @click="prevQuestion" :disabled="selectedQuestionIndex === 0" class="flex-1 bg-slate-100 text-slate-900 py-3 rounded-2xl text-[10px] font-black uppercase disabled:opacity-30">Prev</button>
            <button @click="isNavOpen = true" class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100">
                <span class="font-black text-sm">{{ selectedQuestionIndex + 1 }}</span>
            </button>
            <button @click="nextQuestion" :disabled="selectedQuestionIndex === filteredQuestions.length - 1" class="flex-1 bg-slate-100 text-slate-900 py-3 rounded-2xl text-[10px] font-black uppercase disabled:opacity-30">Next</button>
        </div>

        <div v-if="isNavOpen" class="fixed inset-0 z-[100] flex items-end justify-center p-4">
            <div @click="isNavOpen = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative w-full max-w-lg bg-white rounded-[3rem] p-8 shadow-2xl animate-in slide-in-from-bottom duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-black uppercase italic text-slate-900 tracking-tighter">Pilih Nomor</h3>
                    <button @click="isNavOpen = false" class="text-slate-400">✕</button>
                </div>
                <div class="grid grid-cols-5 gap-3 max-h-[60vh] overflow-y-auto p-2">
                    <button v-for="(q, index) in filteredQuestions" :key="q.id"
                        @click="selectedQuestionIndex = index; isNavOpen = false"
                        :class="[
                            selectedQuestionIndex === index ? 'ring-2 ring-indigo-500 scale-110' : '',
                            getStatus(q) === 'CORRECT' ? 'bg-emerald-500 text-white' : 
                            getStatus(q) === 'WRONG' ? 'bg-rose-500 text-white' : 'bg-slate-100 text-slate-400'
                        ]"
                        class="h-12 rounded-2xl text-[10px] font-black transition-all">
                        {{ index + 1 }}
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>