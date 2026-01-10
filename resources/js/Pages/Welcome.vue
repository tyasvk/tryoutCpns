<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Selamat Datang - CPNS Nusantara" />

    <div class="min-h-screen bg-white text-slate-900 font-sans selection:bg-indigo-100 selection:text-indigo-700">
        
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black">C</div>
                    <span class="font-black uppercase tracking-tighter text-lg">CPNS Nusantara</span>
                </div>

                <div v-if="canLogin" class="flex items-center gap-2 lg:gap-3">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" 
                        class="text-[10px] font-black uppercase tracking-widest px-5 py-3 bg-slate-900 text-white rounded-xl hover:bg-indigo-600 transition-all">
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link :href="route('login')" class="text-[10px] font-black uppercase tracking-widest px-4 py-2 text-slate-600 hover:text-indigo-600 transition-all">
                            Masuk
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" 
                            class="hidden sm:block text-[10px] font-black uppercase tracking-widest px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-slate-900 transition-all shadow-lg shadow-indigo-100">
                            Daftar
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <main>
            <section class="max-w-7xl mx-auto px-6 pt-16 lg:pt-32 pb-20 text-center">
                <div class="inline-block px-4 py-1.5 mb-8 bg-indigo-50 border border-indigo-100 rounded-full">
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-600">Sistem Latihan CAT CASN 2026</p>
                </div>
                <h1 class="text-5xl lg:text-8xl font-black uppercase tracking-tighter leading-[0.9] mb-8">
                    Wujudkan Mimpi <br class="hidden lg:block"> Menjadi ASN.
                </h1>
                <p class="max-w-2xl mx-auto text-slate-500 text-base lg:text-lg font-medium leading-relaxed mb-10">
                    Platform simulasi CAT paling akurat dengan standar penilaian terbaru. Latih dirimu dengan ribuan soal berkualitas dan pantau peringkatmu secara nasional.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 px-4">
                    <Link :href="route('register')" class="w-full sm:w-auto px-10 py-5 bg-slate-900 text-white rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-xl">
                        Mulai Belajar Gratis
                    </Link>
                    <a href="#fitur" class="w-full sm:w-auto px-10 py-5 bg-white border border-slate-200 text-slate-900 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Pelajari Fitur
                    </a>
                </div>
            </section>

            <section id="fitur" class="max-w-7xl mx-auto px-6 py-12 lg:py-24">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
                    <div class="bg-slate-50 p-10 rounded-[2.5rem] border border-slate-100 group transition-all">
                        <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl mb-8">📝</div>
                        <h3 class="text-xl font-black uppercase tracking-tight mb-4 text-slate-900">Sistem CAT Akurat</h3>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">Antarmuka simulasi dirancang 99% mirip dengan sistem CAT BKN yang sesungguhnya untuk membiasakan Anda.</p>
                    </div>

                    <div class="bg-slate-900 p-10 rounded-[2.5rem] text-white shadow-2xl shadow-slate-200">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-2xl mb-8">⚡</div>
                        <h3 class="text-xl font-black uppercase tracking-tight mb-4">Real-time Scoring</h3>
                        <p class="text-sm text-slate-400 font-medium leading-relaxed">Hasil skor TWK, TIU, dan TKP langsung dikalkulasi sesaat setelah Anda menekan tombol selesai ujian.</p>
                    </div>

                    <div class="bg-indigo-600 p-10 rounded-[2.5rem] text-white shadow-xl shadow-indigo-200">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-2xl mb-8">🥇</div>
                        <h3 class="text-xl font-black uppercase tracking-tight mb-4 text-white">Nasional Ranking</h3>
                        <p class="text-sm text-indigo-100 font-medium leading-relaxed">Bandingkan skormu dengan ribuan peserta lain secara transparan melalui sistem leaderboard kami.</p>
                    </div>
                </div>
            </section>

            <section class="max-w-7xl mx-auto px-6 py-12 lg:py-24">
                <div class="bg-indigo-50 rounded-[3rem] p-10 lg:p-24 text-center border border-indigo-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-3xl lg:text-6xl font-black uppercase tracking-tighter mb-8 leading-none text-slate-900">Tunggu Apa Lagi?</h2>
                        <p class="text-slate-600 font-medium mb-10 max-w-xl mx-auto text-sm lg:text-base leading-relaxed">
                            Bergabunglah bersama ribuan pejuang NIP lainnya dan persiapkan diri Anda menjadi ASN melalui <strong>CPNS Nusantara</strong>.
                        </p>
                        <Link :href="route('register')" class="inline-block px-12 py-5 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg">
                            Daftar Sekarang
                        </Link>
                    </div>
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-200/30 rounded-full blur-3xl"></div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-100 py-16 px-6 bg-slate-50/50">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white text-[10px] font-black uppercase">C</div>
                    <span class="font-black uppercase text-xs tracking-[0.2em] text-slate-400">CPNS Nusantara • 2026</span>
                </div>
                <div class="flex flex-wrap justify-center gap-6 lg:gap-10">
                    <a href="#" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">Tentang Kami</a>
                    <a href="#" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
            <div class="text-center mt-12">
                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest italic opacity-0">#PejuangNIP2026</p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Transisi masuk yang halus */
main {
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Custom shadow untuk bento grid */
.group:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px -20px rgba(0,0,0,0.1);
}
</style>