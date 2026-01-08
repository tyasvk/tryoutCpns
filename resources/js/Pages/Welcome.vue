<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome - CAT Online 2026" />

    <div class="min-h-screen bg-white text-slate-950 font-sans selection:bg-indigo-100 flex flex-col items-center justify-center p-6 overflow-hidden relative">
        
        <div class="fixed inset-0 overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-5%] left-[-10%] w-[60%] h-[60%] bg-indigo-50 rounded-full blur-[120px] opacity-70"></div>
            <div class="absolute bottom-[5%] right-[-5%] w-[50%] h-[50%] bg-blue-50 rounded-full blur-[100px] opacity-70"></div>
        </div>

        <div class="mb-12">
            <div class="w-16 h-16 bg-slate-950 rounded-2xl flex items-center justify-center shadow-2xl transform hover:rotate-12 transition-transform duration-500">
                <span class="text-white font-black italic text-2xl leading-none">T</span>
            </div>
        </div>

        <div class="text-center max-w-lg mb-12">
            <h1 class="text-4xl lg:text-6xl font-black text-slate-900 leading-[0.9] tracking-tighter italic uppercase mb-4">
                CAT <span class="text-indigo-600">Online</span> 2026
            </h1>
            <p class="text-slate-500 text-sm font-medium leading-relaxed px-4">
                Platform simulasi ujian CPNS & PPPK dengan sistem penilaian akurat dan analisis real-time.
            </p>
        </div>

        <div v-if="canLogin" class="flex flex-col w-full max-w-xs gap-3">
            <div v-if="$page.props.auth.user" class="w-full">
                <Link :href="route('dashboard')" class="block w-full py-4 bg-slate-950 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl text-center active:scale-95 transition-all">
                    Masuk ke Dashboard
                </Link>
            </div>
            
            <div v-else class="flex flex-col gap-3 w-full">
                <Link :href="route('login')" class="block w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 text-center active:scale-95 transition-all">
                    Masuk Akun
                </Link>
                <Link v-if="canRegister" :href="route('register')" class="block w-full py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] text-center active:scale-95 transition-all hover:border-indigo-600 hover:text-indigo-600">
                    Daftar Baru
                </Link>
            </div>

            <div class="mt-4 text-center">
                <Link :href="route('marketplace.index')" class="text-[9px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">
                    Lihat Paket Tryout 🛒
                </Link>
            </div>
        </div>

        <footer class="mt-20 text-slate-300 text-[9px] font-bold uppercase tracking-[0.3em] italic">
            © 2026 CAT-ONLINE SYSTEM
        </footer>
    </div>
</template>