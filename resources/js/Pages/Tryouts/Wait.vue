<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    tryout: Object, // Data paket tryout
});

// Form untuk memicu pembuatan record ujian baru
const form = useForm({
    tryout_id: props.tryout.id,
});

const startExam = () => {
    if (confirm('Sudah siap memulai ujian? Waktu akan langsung berjalan.')) {
        form.post(route('exam.start'));
    }
};
</script>

<template>
    <Head :title="'Persiapan - ' + tryout.name" />

    <AuthenticatedLayout>
        <template #header>Ruang Tunggu Ujian</template>

        <div class="max-w-3xl mx-auto space-y-6 pb-20">
            
            <div class="bg-slate-900 rounded-[2.5rem] p-8 lg:p-12 text-white shadow-2xl shadow-slate-200">
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-indigo-400 mb-4">Simulasi CAT Aktif</p>
                <h2 class="text-3xl lg:text-5xl font-black uppercase tracking-tighter leading-none mb-8">
                    {{ tryout.name }}
                </h2>
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/10 p-4 rounded-2xl border border-white/5">
                        <p class="text-[8px] font-black uppercase text-slate-400 mb-1">Soal</p>
                        <p class="text-sm font-black">{{ tryout.questions_count || 110 }} Butir</p>
                    </div>
                    <div class="bg-white/10 p-4 rounded-2xl border border-white/5">
                        <p class="text-[8px] font-black uppercase text-slate-400 mb-1">Waktu</p>
                        <p class="text-sm font-black">100 Menit</p>
                    </div>
                    <div class="bg-white/10 p-4 rounded-2xl border border-white/5">
                        <p class="text-[8px] font-black uppercase text-slate-400 mb-1">Passing</p>
                        <p class="text-sm font-black">Standar BKN</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 lg:p-10 shadow-sm">
                <h3 class="text-lg font-black uppercase tracking-tighter text-slate-900 mb-6">Instruksi Pengerjaan</h3>
                
                <ul class="space-y-4">
                    <li class="flex gap-4">
                        <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-black shrink-0">1</div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">Ujian terdiri dari materi TWK, TIU, dan TKP yang dikerjakan dalam satu kesatuan waktu.</p>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-black shrink-0">2</div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">Jawaban benar pada TWK & TIU bernilai 5 poin, salah 0 poin. Pada TKP, bobot nilai berkisar 1 sampai 5.</p>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-black shrink-0">3</div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">Sistem akan otomatis menutup dan menyimpan jawaban jika waktu 100 menit telah habis.</p>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-[10px] font-black shrink-0">!</div>
                        <p class="text-xs text-rose-600 font-black uppercase tracking-tight leading-relaxed">Dilarang menyegarkan (refresh) halaman atau membuka tab lain selama ujian berlangsung.</p>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <Link :href="route('marketplace.index')" class="flex-1 px-8 py-5 bg-white border border-slate-200 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-slate-50 transition-all">
                    Batal & Kembali
                </Link>
                <button @click="startExam" :disabled="form.processing" 
                    class="flex-[2] px-8 py-5 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] text-center hover:bg-slate-900 transition-all shadow-xl shadow-indigo-100">
                    Mulai Ujian Sekarang
                </button>
            </div>

            <div class="text-center pt-4">
                <p class="text-[8px] font-bold text-slate-300 uppercase tracking-widest">Integritas adalah kunci kelulusan Anda • CPNS Nusantara</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>