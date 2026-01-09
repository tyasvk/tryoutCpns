<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ exam: Object });

const categories = [
    { name: 'TWK', score: props.exam.score_twk, target: 65, icon: '⚖️' },
    { name: 'TIU', score: props.exam.score_tiu, target: 80, icon: '🧠' },
    { name: 'TKP', score: props.exam.score_tkp, target: 166, icon: '🤝' },
];
</script>

<template>
    <Head title="Hasil Ujian - CPNS Nusantara" />
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-12 px-4">
            
            <div :class="exam.is_passed ? 'bg-emerald-600' : 'bg-rose-600'" 
                 class="rounded-[3rem] p-10 text-white text-center mb-8 shadow-2xl transition-all">
                <p class="text-[10px] font-black uppercase tracking-[0.4em] mb-4 opacity-80">Hasil Simulasi CAT</p>
                <h2 class="text-6xl font-black italic tracking-tighter mb-2">
                    {{ exam.is_passed ? 'LULUS' : 'TIDAK LULUS' }}
                </h2>
                <p class="text-sm font-medium opacity-90 italic">
                    {{ exam.is_passed ? 'Selamat! Pertahankan performa Anda.' : 'Jangan menyerah, perbaiki skor di ambang batas.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div v-for="cat in categories" :key="cat.name" class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-2xl">{{ cat.icon }}</span>
                            <span :class="cat.score >= cat.target ? 'text-emerald-500' : 'text-rose-500'" class="text-[10px] font-black uppercase italic">
                                PG: {{ cat.target }}
                            </span>
                        </div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ cat.name }}</p>
                        <h4 class="text-4xl font-black italic text-slate-900">{{ cat.score }}</h4>
                        
                        <div class="mt-4 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div :class="cat.score >= cat.target ? 'bg-emerald-500' : 'bg-rose-500'"
                                 :style="{ width: Math.min((cat.score/cat.target)*100, 100) + '%' }" 
                                 class="h-full transition-all duration-1000"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white flex flex-col md:flex-row justify-between items-center gap-8 shadow-xl">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2">Total Akumulasi Skor</p>
                    <h3 class="text-5xl font-black italic">{{ exam.total_score }} <span class="text-lg text-slate-500 not-italic">/ 550</span></h3>
                </div>
                <div class="flex gap-3 w-full md:w-auto">
                    <Link :href="route('exam.review', exam.id)" class="flex-1 md:flex-none text-center bg-white/10 border border-white/20 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition-all">
                        Pembahasan
                    </Link>
                    <Link :href="route('dashboard')" class="flex-1 md:flex-none text-center bg-indigo-600 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500 shadow-lg shadow-indigo-900/50 transition-all">
                        Dashboard
                    </Link>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>