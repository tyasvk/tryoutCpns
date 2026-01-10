<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    tryouts: Array, // Data paket tryout dari controller
});

// Helper untuk format mata uang jika ada paket berbayar
const formatPrice = (price) => {
    if (price == 0) return 'GRATIS';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(price);
};
</script>

<template>
    <Head title="Marketplace Tryout - CPNS Nusantara" />

    <AuthenticatedLayout>
        <template #header>Pilih Paket Simulasi</template>

        <div class="space-y-8 pb-20">
            <div class="flex overflow-x-auto pb-4 gap-3 no-scrollbar">
                <button class="bg-slate-900 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shrink-0">Semua Paket</button>
                <button class="bg-white border border-slate-200 text-slate-500 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shrink-0">Premium</button>
                <button class="bg-white border border-slate-200 text-slate-500 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shrink-0">Gratis</button>
            </div>

            <div v-if="tryouts.length === 0" class="bg-white p-20 rounded-[3rem] border border-dashed text-center">
                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada paket yang tersedia saat ini.</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="tryout in tryouts" :key="tryout.id" 
                    class="group bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300">
                    
                    <div class="px-8 pt-8 flex justify-between items-center">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[8px] font-black uppercase tracking-widest">
                            {{ tryout.questions_count }} Soal
                        </span>
                        <span class="text-[10px] font-black text-slate-900 uppercase tracking-tighter">
                            {{ formatPrice(tryout.price || 0) }}
                        </span>
                    </div>

                    <div class="p-8">
                        <h3 class="text-2xl font-black uppercase tracking-tighter text-slate-900 leading-none mb-4 group-hover:text-indigo-600 transition-colors">
                            {{ tryout.name }}
                        </h3>
                        
                        <p class="text-xs text-slate-400 font-medium leading-relaxed mb-8 line-clamp-2">
                            Simulasi CAT CPNS dengan standar materi terbaru. Lengkap dengan pembahasan dan ranking nasional.
                        </p>

                        <div class="grid grid-cols-2 gap-3 mb-8">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p class="text-[7px] font-black text-slate-400 uppercase mb-1">Waktu</p>
                                <p class="text-xs font-black text-slate-900 uppercase">100 Menit</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p class="text-[7px] font-black text-slate-400 uppercase mb-1">Metode</p>
                                <p class="text-xs font-black text-slate-900 uppercase">Sistem CAT</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <Link :href="route('tryout.wait', tryout.id)" 
                                class="flex-1 bg-slate-900 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-indigo-600 transition-all shadow-lg shadow-slate-200">
                                Kerjakan
                            </Link>
                            <Link :href="route('tryout.leaderboard', tryout.id)" 
                                class="w-14 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-lg hover:bg-slate-50 transition-all">
                                🥇
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Hilangkan scrollbar di filter mobile */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>