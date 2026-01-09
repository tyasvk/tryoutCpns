<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Gunakan Default Values agar tidak 'undefined'
const props = defineProps({
    stats: { type: Object, default: () => ({ twk: 0, tiu: 0, tkp: 0 }) },
    recentExams: { type: Array, default: () => [] },
    recommended: { type: Array, default: () => [] },
    totalExams: { type: Number, default: 0 }
});
</script>

<template>
    <Head title="Dashboard - CPNS Nusantara" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
            
            <div class="bg-slate-900 rounded-[3rem] p-8 lg:p-14 text-white relative overflow-hidden shadow-2xl">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl lg:text-6xl font-black italic tracking-tighter uppercase leading-none mb-4">
                            Hi, {{ $page.props.auth.user.name }}!
                        </h1>
                        <p class="text-slate-400 font-medium max-w-sm text-sm lg:text-lg italic">
                            Selamat datang di <span class="text-white underline">CPNS Nusantara</span>. Lanjutkan perjuanganmu hari ini!
                        </p>
                    </div>
                    <Link :href="route('marketplace.index')" class="bg-indigo-600 px-10 py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                        Mulai Simulasi Baru →
                    </Link>
                </div>
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-600/20 rounded-full blur-[80px]"></div>
            </div>

            <div v-if="stats" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white p-8 lg:p-10 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col justify-between group">
                    <div class="flex justify-between items-center mb-10 px-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Rata-rata Skor</span>
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">📊</div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-2">TWK</p>
                            <h3 class="text-4xl lg:text-6xl font-black italic tracking-tighter text-slate-900 leading-none">{{ stats.twk ?? 0 }}</h3>
                        </div>
                        <div class="border-x border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-2">TIU</p>
                            <h3 class="text-4xl lg:text-6xl font-black italic tracking-tighter text-slate-900 leading-none">{{ stats.tiu ?? 0 }}</h3>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-2">TKP</p>
                            <h3 class="text-4xl lg:text-6xl font-black italic tracking-tighter text-slate-900 leading-none">{{ stats.tkp ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-600 rounded-[3rem] p-8 text-white flex flex-col justify-center items-center text-center shadow-xl shadow-indigo-100 relative overflow-hidden group">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300 relative z-10">Simulasi Selesai</p>
                    <h2 class="text-8xl lg:text-9xl font-black italic tracking-tighter relative z-10 my-4">{{ totalExams }}</h2>
                    <p class="text-[9px] font-black uppercase relative z-10 opacity-60 italic tracking-[0.2em]">ASN Nusantara 2026</p>
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-700 to-violet-800 transition-transform duration-700 group-hover:scale-110"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex items-center justify-between px-4">
                        <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Aktivitas Terakhir</h3>
                        <Link :href="route('exam.history')" class="text-[10px] font-black text-indigo-600 uppercase hover:underline">Lihat Semua</Link>
                    </div>
                    
                    <div class="space-y-4">
                        <div v-for="exam in recentExams" :key="exam.id" class="bg-white p-6 lg:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-indigo-50 transition-colors">📜</div>
                                <div>
                                    <h4 class="font-black text-slate-800 uppercase italic tracking-tight leading-none text-sm lg:text-base">{{ exam.tryout?.name || 'Simulasi CAT' }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1.5">{{ new Date(exam.created_at).toLocaleDateString() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-black text-slate-900 leading-none tracking-tighter">{{ exam.total_score }}</p>
                                <Link :href="route('exam.result', exam.id)" class="text-[9px] font-black text-indigo-500 uppercase tracking-widest hover:underline mt-2 inline-block">Review →</Link>
                            </div>
                        </div>

                        <div v-if="recentExams.length === 0" class="text-center py-20 bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                            <p class="text-slate-400 text-xs font-black uppercase italic">Belum ada riwayat ujian</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter px-4 leading-none">Pilihan Terbaik</h3>
                    <div class="space-y-4">
                        <div v-for="item in recommended" :key="item.id" class="bg-gradient-to-br from-white to-slate-50 p-8 rounded-[3rem] border border-slate-200 shadow-sm group">
                            <h4 class="font-black text-slate-800 uppercase italic mb-8 leading-tight text-lg group-hover:text-indigo-600 transition-colors">{{ item.name }}</h4>
                            <Link :href="route('exam.start', { tryout_id: item.id })" method="post" as="button" class="w-full py-5 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-indigo-600 transition-all">
                                Mulai Sekarang
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>