<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
// Import Chart.js components
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

// Register ChartJS components
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
    exam: Object,
    averages: Object
});

// Konfigurasi Data Chart
const chartData = computed(() => ({
    labels: ['TWK', 'TIU', 'TKP'],
    datasets: [
        {
            label: 'Nilai Anda',
            backgroundColor: '#4f46e5', // Indigo-600
            borderRadius: 8,
            data: [props.exam.score_twk, props.exam.score_tiu, props.exam.score_tkp]
        },
        {
            label: 'Rata-rata Nasional',
            backgroundColor: '#cbd5e1', // Slate-300
            borderRadius: 8,
            data: [props.averages.twk, props.averages.tiu, props.averages.tkp]
        }
    ]
}));

// Konfigurasi Opsi Chart (Visual Modern)
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                font: { family: "Inter, sans-serif", size: 11, weight: 'bold' },
                boxWidth: 12,
                usePointStyle: true,
            }
        },
        tooltip: {
            backgroundColor: '#0f172a',
            titleFont: { family: "Inter, sans-serif", weight: 'bold' },
            bodyFont: { family: "Inter, sans-serif" },
            padding: 12,
            cornerRadius: 8,
            displayColors: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { display: true, color: '#f1f5f9' }, // Grid halus
            ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' },
            border: { display: false }
        },
        x: {
            grid: { display: false },
            ticks: { font: { size: 11, weight: 'black' }, color: '#334155' },
            border: { display: false }
        }
    }
};

// Pesan motivasi sederhana berdasarkan total skor (contoh batas 300)
const isPassing = computed(() => props.exam.total_score >= 300);
</script>

<template>
    <Head title="Hasil Ujian" />
    <AuthenticatedLayout>
        <div class="fixed inset-0 overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[10%] right-[10%] w-[40%] h-[40%] bg-indigo-50 rounded-full blur-[100px] opacity-50"></div>
            <div class="absolute bottom-[10%] left-[10%] w-[40%] h-[40%] bg-emerald-50 rounded-full blur-[100px] opacity-50"></div>
        </div>

        <template #header>
             <h2 class="text-xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">
                Result Summary<span class="text-indigo-600">.</span>
            </h2>
        </template>

        <div class="max-w-5xl mx-auto space-y-8 pb-12">
            
            <div class="bg-slate-950 rounded-[3rem] p-8 md:p-10 text-center text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <span class="inline-block bg-white/10 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] mb-4">
                        {{ exam.tryout?.name ?? 'Simulasi CAT' }}
                    </span>
                    <h1 class="text-7xl md:text-8xl font-black tracking-tighter leading-none mb-2">
                        {{ exam.total_score }}
                    </h1>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Total Skor Akumulasi</p>

                     <div :class="isPassing ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/50' : 'bg-red-500/20 text-red-300 border-red-500/50'" 
                         class="inline-flex items-center gap-2 px-5 py-2 rounded-xl border backdrop-blur-md">
                        <span class="text-xl">{{ isPassing ? '🎉' : '💪' }}</span>
                        <span class="text-[10px] font-black uppercase tracking-widest">
                            {{ isPassing ? 'Hasil Sangat Baik! Pertahankan.' : 'Tetap Semangat! Tingkatkan Lagi.' }}
                        </span>
                    </div>
                </div>
                <div class="absolute top-[-50%] left-[-20%] w-full h-full bg-indigo-600/30 blur-[120px] rounded-full"></div>
                 <div class="absolute bottom-[-50%] right-[-20%] w-full h-full bg-emerald-600/20 blur-[120px] rounded-full"></div>
            </div>

            <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 md:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                     <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tighter uppercase italic leading-none">Analisis Performa</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Anda vs Rata-rata Nasional</p>
                     </div>
                </div>
                <div class="h-72 lg:h-80 w-full">
                    <Bar :data="chartData" :options="chartOptions" />
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 text-center">
                <div v-for="(score, label) in {TWK: exam.score_twk, TIU: exam.score_tiu, TKP: exam.score_tkp}" :key="label"
                     class="bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm flex flex-col justify-center"
                >
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ label }}</p>
                    <h4 :class="label === 'TKP' ? 'text-indigo-600' : 'text-slate-900'" class="text-3xl font-black tracking-tighter leading-none">
                        {{ score }}
                    </h4>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                <Link :href="route('exam.review', exam.id)" class="flex items-center justify-center gap-2 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition-all order-1 md:order-none">
                    <span>📝</span> Lihat Pembahasan Lengkap
                </Link>
                <Link :href="route('dashboard')" class="flex items-center justify-center py-4 bg-white border-2 border-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:border-indigo-200 hover:text-indigo-600 active:scale-95 transition-all">
                    Kembali ke Dashboard
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>