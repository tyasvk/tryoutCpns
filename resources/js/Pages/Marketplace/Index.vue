<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({ tryouts: Array });

const buy = (id) => {
    if (confirm('Beli paket tryout ini?')) {
        router.post(route('marketplace.buy', id));
    }
};
</script>

<template>
    <Head title="Marketplace Tryout" />
    <div class="min-h-screen bg-slate-50 py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-12 text-center lg:text-left">
                <Link :href="route('dashboard')" class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 inline-block">← Kembali</Link>
                <h2 class="text-4xl font-black text-slate-800 tracking-tighter uppercase italic">Eksplor <span class="text-indigo-600">Paket Tryout</span></h2>
                <p class="text-slate-500 mt-2">Pilih paket terbaik untuk mempercepat kelulusan Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="item in tryouts" :key="item.id" class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm flex flex-col hover:shadow-2xl transition-all group">
                    <div class="flex justify-between items-start mb-6">
                        <span :class="item.is_free ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100'" class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border">
                            {{ item.is_free ? 'Gratis' : 'Premium' }}
                        </span>
                        <div class="text-3xl grayscale group-hover:grayscale-0 transition">🎁</div>
                    </div>

                    <h3 class="text-xl font-black text-slate-800 mb-2 italic">{{ item.name }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8">{{ item.duration }} Menit Simulasi</p>

                    <div class="mt-auto flex items-center justify-between gap-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Harga</span>
                            <span class="text-2xl font-black text-slate-900 tracking-tighter">
                                {{ item.is_free ? 'Free' : 'Rp' + item.price.toLocaleString() }}
                            </span>
                        </div>
                        <button @click="buy(item.id)" :class="item.is_free ? 'bg-slate-900' : 'bg-indigo-600 shadow-indigo-100'" class="px-6 py-4 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg active:scale-95 transition">
                            {{ item.is_free ? 'Ambil' : 'Beli Sekarang' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>