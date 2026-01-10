<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    totalExams: Number,
});

const page = usePage();
const settings = computed(() => page.props.site_settings);
</script>

<template>
    <Head title="Dashboard - CPNS Nusantara" />

    <AuthenticatedLayout>
        <template #header>Dashboard Peserta</template>

        <div class="space-y-6 pb-20">
            
            <div v-if="settings.announcement" 
                class="bg-white border-l-4 border-indigo-600 p-6 rounded-3xl shadow-sm flex gap-4 items-start animate-in fade-in slide-in-from-top-4 duration-700">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-xl shrink-0">📢</div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-600 mb-1">Pengumuman Penting</h4>
                    <p class="text-xs font-bold text-slate-600 leading-relaxed">{{ settings.announcement }}</p>
                </div>
            </div>

            <div v-if="settings.is_to_akbar_active == '1'" 
                class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-slate-200">
                <div class="relative z-10">
                    <div class="inline-block px-3 py-1 bg-rose-500 rounded-lg text-[8px] font-black uppercase tracking-widest mb-4 animate-pulse">Live Now</div>
                    <h3 class="text-2xl font-black uppercase tracking-tighter mb-2">Tryout Akbar Nasional</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Jadwal: {{ settings.grand_to_start }}</p>
                    
                    <Link :href="route('marketplace.index')" 
                        class="inline-block bg-white text-slate-900 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                        Ikuti Sekarang →
                    </Link>
                </div>
                <div class="absolute -right-10 -top-10 text-8xl opacity-10">🏆</div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="(val, label) in { 'TWK': stats?.twk, 'TIU': stats?.tiu, 'TKP': stats?.tkp }" :key="label" 
                    class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ label }}</p>
                    <p class="text-3xl font-black text-slate-900 leading-none">{{ val || 0 }}</p>
                </div>
                <div class="bg-indigo-600 p-6 rounded-3xl text-white shadow-lg shadow-indigo-100">
                    <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-1">Total Ujian</p>
                    <p class="text-3xl font-black leading-none">{{ totalExams || 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Link :href="route('marketplace.index')" class="bg-white p-8 rounded-[2.5rem] border border-slate-200 flex items-center justify-between group hover:border-indigo-600 transition-all">
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-tight text-slate-900">Mulai Latihan</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih paket soal terbaru</p>
                    </div>
                    <span class="text-xl group-hover:translate-x-2 transition-transform">→</span>
                </Link>

                <Link :href="route('exam.analysis')" class="bg-white p-8 rounded-[2.5rem] border border-slate-200 flex items-center justify-between group hover:border-indigo-600 transition-all">
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-tight text-slate-900">Analisis Skor</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cek kelemahan belajarmu</p>
                    </div>
                    <span class="text-xl group-hover:translate-x-2 transition-transform">📊</span>
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>