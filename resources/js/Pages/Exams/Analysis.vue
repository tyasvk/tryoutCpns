<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object
});

// Rekomendasi belajar berdasarkan materi terlemah
const recommendations = {
    'TWK': 'Fokus perkuat pemahaman tentang Pilar Negara dan Nasionalisme.',
    'TIU': 'Perbanyak latihan soal pola deret angka dan logika analitis.',
    'TKP': 'Pahami karakteristik soal orientasi pelayanan dan profesionalisme.'
};
</script>

<template>
    <Head title="Analisis Kemampuan - CPNS Nusantara" />

    <AuthenticatedLayout>
        <template #header>Analisis Kemampuan</template>

        <div class="max-w-5xl mx-auto space-y-6 pb-20">
            
            <div class="bg-slate-900 rounded-[2.5rem] p-8 lg:p-12 text-white shadow-2xl shadow-slate-200 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-indigo-400 mb-4">Rekomendasi Ahli</p>
                    <h2 class="text-2xl lg:text-4xl font-black uppercase tracking-tighter leading-tight mb-4">
                        Tingkatkan Skor <br class="hidden sm:block"> {{ stats.weakest_subject }} Anda!
                    </h2>
                    <p class="text-sm text-slate-400 font-medium mb-8 max-w-md">
                        {{ recommendations[stats.weakest_subject] }} Berdasarkan data, ini adalah materi yang paling perlu Anda asah.
                    </p>
                    <Link :href="route('marketplace.index')" class="inline-block bg-white text-slate-900 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg">
                        Cari Soal {{ stats.weakest_subject }}
                    </Link>
                </div>
                <div class="absolute -right-10 -bottom-10 text-9xl opacity-10">📊</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200">
                    <div class="flex justify-between items-start mb-6">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rata-rata TWK</span>
                        <span class="text-indigo-600 font-black text-[10px]">Max 150</span>
                    </div>
                    <p class="text-4xl font-black text-slate-900">{{ stats.twk }}</p>
                    <div class="mt-4 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full" :style="{ width: (stats.twk / 150 * 100) + '%' }"></div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-slate-200">
                    <div class="flex justify-between items-start mb-6">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rata-rata TIU</span>
                        <span class="text-indigo-600 font-black text-[10px]">Max 175</span>
                    </div>
                    <p class="text-4xl font-black text-slate-900">{{ stats.tiu }}</p>
                    <div class="mt-4 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full" :style="{ width: (stats.tiu / 175 * 100) + '%' }"></div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-slate-200">
                    <div class="flex justify-between items-start mb-6">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rata-rata TKP</span>
                        <span class="text-indigo-600 font-black text-[10px]">Max 225</span>
                    </div>
                    <p class="text-4xl font-black text-slate-900">{{ stats.tkp }}</p>
                    <div class="mt-4 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full" :style="{ width: (stats.tkp / 225 * 100) + '%' }"></div>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 p-8 rounded-[2.5rem] border border-indigo-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h3 class="text-sm font-black uppercase tracking-tight text-indigo-900">Total Data Simulasi</h3>
                    <p class="text-xs text-indigo-700 font-medium">Berdasarkan {{ stats.total_exams }} pengerjaan ujian terakhir Anda.</p>
                </div>
                <div class="text-4xl font-black text-indigo-600">{{ stats.total_exams }}</div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>