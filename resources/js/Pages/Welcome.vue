<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="CPNS Nusantara - Platform Tryout No. 1" />

    <div class="min-h-screen bg-white text-slate-900 selection:bg-indigo-500 selection:text-white">
        <nav class="fixed top-0 left-0 right-0 z-[100] p-4 lg:p-6 flex justify-center pointer-events-none">
            <div class="w-full max-w-7xl flex justify-between items-center bg-white/70 backdrop-blur-xl border border-white/20 shadow-2xl p-3 lg:p-4 rounded-[2rem] pointer-events-auto">
                <div class="flex items-center gap-2 ml-4">
                    <span class="text-lg lg:text-xl font-black italic tracking-tighter uppercase">CPNS Nusantara<span class="text-indigo-600">.</span></span>
                </div>
                
                <div class="flex gap-2">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-600 transition-all">Dashboard</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="px-6 py-3 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-50 transition-all">Log in</Link>
                        <Link :href="route('register')" class="px-6 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">Daftar</Link>
                    </template>
                </div>
            </div>
        </nav>

        <header class="relative pt-32 pb-20 lg:pt-52 lg:pb-32 px-6 text-center">
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-full mb-8">
                    <span class="text-[10px] font-black uppercase text-indigo-600 tracking-widest italic">🚀 Seleksi ASN Nusantara 2026</span>
                </div>
                <h1 class="text-5xl lg:text-9xl font-black italic tracking-tighter leading-[0.9] uppercase mb-8">
                    Lolos Seleksi <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">Nusantara 2026.</span>
                </h1>
                <p class="max-w-2xl mx-auto text-slate-500 font-medium text-base lg:text-xl mb-12 leading-relaxed">
                    Platform simulasi CAT paling akurat untuk calon abdi negara di seluruh penjuru Indonesia dengan teknologi analisis radar terbaru.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <Link :href="route('register')" class="px-10 py-6 bg-slate-900 text-white rounded-[2rem] font-black uppercase tracking-widest text-xs shadow-2xl hover:scale-105 transition-all">Mulai Perjuangan Sekarang</Link>
                </div>
            </div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-[600px] bg-indigo-100/50 rounded-full blur-[120px] -z-10"></div>
        </header>
    </div>
</template>