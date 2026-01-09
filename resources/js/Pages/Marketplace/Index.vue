<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    tryouts: Array // Pastikan data dikirim dari controller
});

const selectedCategory = ref('ALL');
const categories = [
    { name: 'ALL', icon: '🌟' },
    { name: 'FREE', icon: '🎁' },
    { name: 'PAID', icon: '💎' },
    { name: 'GRAND', icon: '🔥' },
];

const filteredTryouts = computed(() => {
    if (selectedCategory.value === 'ALL') return props.tryouts;
    return props.tryouts.filter(t => t.type.toUpperCase() === selectedCategory.value);
});

// Helper untuk warna badge
const getTypeStyle = (type) => {
    const styles = {
        free: 'bg-emerald-50 text-emerald-600 border-emerald-100',
        paid: 'bg-amber-50 text-amber-600 border-amber-100',
        grand: 'bg-rose-50 text-rose-600 border-rose-100'
    };
    return styles[type.toLowerCase()] || 'bg-slate-50 text-slate-600';
};
</script>

<template>
    <Head title="Katalog Tryout" />
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-black text-slate-900 uppercase italic tracking-tighter leading-none mb-2">Pilih Paket</h2>
                    <p class="text-slate-500 font-medium text-sm lg:text-base">Tingkatkan skor SKD-mu dengan latihan soal berbasis CAT.</p>
                </div>
                
                <div class="flex overflow-x-auto no-scrollbar gap-2 w-full lg:w-auto pb-2 lg:pb-0">
                    <button v-for="cat in categories" :key="cat.name"
                        @click="selectedCategory = cat.name"
                        :class="selectedCategory === cat.name ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 scale-105' : 'bg-white text-slate-500 border-slate-200'"
                        class="flex items-center gap-2 px-6 py-3 rounded-2xl border font-black text-[10px] uppercase tracking-widest transition-all whitespace-nowrap">
                        <span>{{ cat.icon }}</span>
                        {{ cat.name }}
                    </button>
                </div>
            </div>

            <div v-if="filteredTryouts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <div v-for="item in filteredTryouts" :key="item.id" 
                     class="group bg-white rounded-[3rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:shadow-indigo-100 hover:border-indigo-200 transition-all duration-500 overflow-hidden flex flex-col">
                    
                    <div class="p-8 pb-4">
                        <div class="flex justify-between items-start mb-6">
                            <span :class="getTypeStyle(item.type)" class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border">
                                {{ item.type }}
                            </span>
                            <div class="flex items-center gap-1 text-slate-400">
                                <span class="text-xs font-bold">{{ item.duration }}</span>
                                <span class="text-[9px] font-black uppercase">Mins</span>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-tight group-hover:text-indigo-600 transition-colors">
                            {{ item.name }}
                        </h3>
                        <p class="text-slate-400 text-xs mt-3 line-clamp-2 font-medium">
                            {{ item.description || 'Simulasi ujian CAT CPNS dengan standar soal terbaru 2026.' }}
                        </p>
                    </div>

                    <div class="px-8 py-6 flex items-center gap-6 border-y border-slate-50 bg-slate-50/50">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Questions</span>
                            <span class="text-sm font-black text-slate-800">{{ item.questions_count || 110 }} Soal</span>
                        </div>
                        <div class="w-px h-6 bg-slate-200"></div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Format</span>
                            <span class="text-sm font-black text-slate-800 uppercase italic">CAT BKN</span>
                        </div>
                    </div>

                    <div class="p-8 mt-auto">
                        <template v-if="item.type.toLowerCase() === 'free'">
                            <Link :href="route('exam.start', { tryout_id: item.id })" method="post" as="button"
                                class="w-full bg-slate-900 text-white py-5 rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:shadow-indigo-100 transition-all active:scale-95">
                                Kerjakan Gratis
                            </Link>
                        </template>
                        <template v-else>
                            <button class="w-full bg-indigo-600 text-white py-5 rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                                <span>💎</span> Unlock Premium
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <div v-else class="bg-white p-20 rounded-[4rem] border border-dashed border-slate-300 text-center">
                <div class="text-6xl mb-6">🔍</div>
                <h3 class="text-xl font-black text-slate-900 uppercase italic italic">Paket Belum Tersedia</h3>
                <p class="text-slate-400 text-sm mt-2">Maaf, paket kategori ini sedang dalam tahap penyusunan.</p>
                <button @click="selectedCategory = 'ALL'" class="mt-8 text-indigo-600 font-black text-[10px] uppercase tracking-widest hover:underline">Lihat Semua Paket</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>