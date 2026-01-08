<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    isAdmin: Boolean,
    stats: Object,
    userStats: Object,
    announcements: Array
});
</script>

<template>
    <Head title="Dashboard Overview" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col">
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-1">Central Hub</p>
                <h2 class="text-2xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">
                    Dashboard<span class="text-indigo-600">.</span>
                </h2>
            </div>
        </template>

        <div class="space-y-6 pb-12">
            
            <div v-if="isAdmin" class="grid grid-cols-1 md:grid-cols-12 gap-4 auto-rows-fr">
                <div class="md:col-span-8 bg-slate-950 rounded-[2.5rem] p-8 text-white relative overflow-hidden flex flex-col justify-between shadow-2xl">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full animate-ping"></span>
                            <span class="text-[9px] font-black uppercase tracking-widest text-indigo-400">Live Participant Data</span>
                        </div>
                        <h3 class="text-5xl font-black tracking-tighter leading-none mb-2">{{ stats.total_users }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total User Terdaftar</p>
                    </div>
                    <div class="relative z-10 mt-8 flex gap-3">
                        <Link :href="route('admin.users.index')" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Kelola Peserta ➔</Link>
                    </div>
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-600 rounded-full blur-[100px] opacity-20"></div>
                </div>

                <div class="md:col-span-4 grid grid-cols-1 gap-4">
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm hover:border-indigo-200 transition-all group">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Bank Soal</p>
                        <div class="flex items-end justify-between">
                            <h4 class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ stats.total_questions }}</h4>
                            <span class="text-[10px] font-black text-indigo-600 group-hover:translate-x-1 transition-transform">📚</span>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm hover:border-indigo-200 transition-all group">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Paket Tryout</p>
                        <div class="flex items-end justify-between">
                            <h4 class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ stats.total_tryouts }}</h4>
                            <span class="text-[10px] font-black text-indigo-600 group-hover:translate-x-1 transition-transform">🏆</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="space-y-6">
                <div class="relative group overflow-hidden bg-white border border-slate-100 p-8 md:p-12 rounded-[3rem] shadow-sm flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="z-10 text-center md:text-left">
                        <div class="inline-flex items-center gap-2 bg-indigo-50 px-3 py-1 rounded-full mb-6">
                            <span class="text-[8px] font-black text-indigo-600 uppercase tracking-widest">Siap Ujian 2026</span>
                        </div>
                        <h3 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase leading-[0.9] mb-6">
                            Tingkatkan Skor <br> <span class="text-indigo-600">CAT Anda.</span>
                        </h3>
                        <Link :href="route('exam.start')" class="inline-flex px-8 py-4 bg-slate-950 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 transition-all active:scale-95 shadow-xl shadow-slate-200">
                            Mulai Simulasi ➔
                        </Link>
                    </div>
                    
                    <div class="relative w-32 h-32 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-50" />
                            <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="364.4" stroke-dashoffset="100" class="text-indigo-600" />
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-xl font-black tracking-tighter">75%</span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase">Progress</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-if="announcements?.length">
                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 ml-4">Informasi Terbaru</h4>
                        <div class="space-y-3">
                            <div v-for="ann in announcements" :key="ann.id" class="bg-white border border-slate-100 p-5 rounded-[2rem] hover:border-indigo-100 transition-all">
                                <h5 class="font-black text-slate-800 text-[11px] uppercase italic mb-1">{{ ann.title }}</h5>
                                <p class="text-[10px] text-slate-500 leading-relaxed">{{ ann.content }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-[2rem] p-8 flex flex-col justify-center items-center text-center border-2 border-dashed border-slate-200">
                        <span class="text-2xl mb-2">💡</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
                            Tips: Kerjakan soal TIU <br> secara rutin setiap hari <br> untuk melatih logika.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Refined interaction */
.shadow-2xl {
    shadow-color: rgba(79, 70, 229, 0.1);
}
</style>