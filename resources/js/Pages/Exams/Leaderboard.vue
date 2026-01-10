<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    tryout: Object,
    rankings: Array
});

// Helper untuk medali Top 3
const getMedal = (index) => {
    if (index === 0) return '🥇';
    if (index === 1) return '🥈';
    if (index === 2) return '🥉';
    return `#${index + 1}`;
};
</script>

<template>
    <Head :title="'Peringkat - ' + tryout.name" />

    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            
            <div class="text-center mb-10 px-4">
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-2 italic">Hall of Fame</p>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter text-slate-900 leading-tight mb-2">Peringkat Nasional</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ tryout.name }}</p>
            </div>

            <div v-if="rankings.length >= 3" class="grid grid-cols-3 gap-2 items-end mb-10 px-2">
                <div class="bg-white p-4 rounded-t-[2rem] border-x border-t border-slate-100 text-center pb-8 shadow-sm">
                    <span class="text-2xl mb-2 block">🥈</span>
                    <p class="text-[8px] font-black uppercase truncate text-slate-500 mb-1">{{ rankings[1].user.name }}</p>
                    <p class="text-xl font-black italic text-slate-900 leading-none">{{ rankings[1].top_score }}</p>
                </div>
                <div class="bg-slate-900 p-6 rounded-t-[2.5rem] text-center pb-12 shadow-2xl relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 text-2xl animate-bounce">👑</div>
                    <span class="text-3xl mb-2 block">🥇</span>
                    <p class="text-[9px] font-black uppercase truncate text-slate-400 mb-1">{{ rankings[0].user.name }}</p>
                    <p class="text-2xl font-black italic text-white leading-none">{{ rankings[0].top_score }}</p>
                </div>
                <div class="bg-white p-4 rounded-t-[2rem] border-x border-t border-slate-100 text-center pb-6 shadow-sm">
                    <span class="text-2xl mb-2 block">🥉</span>
                    <p class="text-[8px] font-black uppercase truncate text-slate-500 mb-1">{{ rankings[2].user.name }}</p>
                    <p class="text-xl font-black italic text-slate-900 leading-none">{{ rankings[2].top_score }}</p>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
                <div class="p-6 lg:p-8">
                    <div v-if="rankings.length === 0" class="text-center py-10">
                        <p class="text-xs font-bold text-slate-400 uppercase italic">Belum ada peserta yang menyelesaikan ujian.</p>
                    </div>

                    <div v-else class="divide-y divide-slate-50">
                        <div v-for="(rank, index) in rankings" :key="index" 
                            :class="rank.user_id === $page.props.auth.user.id ? 'bg-indigo-50/50 -mx-6 px-6' : ''"
                            class="flex items-center py-5 transition-all">
                            
                            <div class="w-10 font-black italic text-slate-400 text-xs">
                                {{ getMedal(index) }}
                            </div>

                            <div class="flex-1 min-w-0 pr-4">
                                <h4 class="text-[11px] font-black uppercase italic text-slate-800 truncate leading-none mb-1">
                                    {{ rank.user.name }}
                                    <span v-if="rank.user_id === $page.props.auth.user.id" class="ml-2 text-[7px] bg-indigo-600 text-white px-2 py-0.5 rounded-full not-italic">ANDA</span>
                                </h4>
                                <div class="flex gap-2 text-[8px] font-bold text-slate-400 uppercase italic">
                                    <span>TWK: {{ rank.twk }}</span>
                                    <span>TIU: {{ rank.tiu }}</span>
                                    <span>TKP: {{ rank.tkp }}</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-lg font-black italic text-slate-900 leading-none">{{ rank.top_score }}</p>
                                <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Poin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center pt-6 pb-12">
                <Link :href="route('marketplace.index')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 border-b border-slate-200 pb-1">Kembali ke Daftar Tryout</Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>