<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    tryout: Object,
    status: String,
    serverTime: String
});

const timeLeft = ref({ days: 0, hours: 0, minutes: 0, seconds: 0 });
const currentStatus = ref(props.status);
let timer = null;

const calculateTime = () => {
    const start = new Date(props.tryout.start_at).getTime();
    const now = new Date().getTime();
    const diff = start - now;

    if (diff <= 0) {
        currentStatus.value = 'ongoing';
        clearInterval(timer);
        return;
    }

    timeLeft.value = {
        days: Math.floor(diff / (1000 * 60 * 60 * 24)),
        hours: Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
        minutes: Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)),
        seconds: Math.floor((diff % (1000 * 60)) / 1000),
    };
};

onMounted(() => {
    if (currentStatus.value === 'upcoming') {
        calculateTime();
        timer = setInterval(calculateTime, 1000);
    }
});

onUnmounted(() => clearInterval(timer));

const formattedPrice = computed(() => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(props.tryout.price);
});
</script>

<template>
    <Head :title="'Waiting Room - ' + tryout.name" />

    <AuthenticatedLayout>
        <div class="max-w-5xl mx-auto py-12 px-4">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <div class="lg:col-span-6 space-y-8 animate-in slide-in-from-left duration-700">
                    <div>
                        <span class="bg-indigo-600 text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest italic mb-6 inline-block shadow-lg shadow-indigo-200">
                            {{ tryout.type === 'grand_to' ? 'Event Akbar Nasional' : 'Premium Tryout' }}
                        </span>
                        <h1 class="text-5xl lg:text-6xl font-black text-slate-900 uppercase italic tracking-tighter leading-tight">
                            {{ tryout.name }}
                        </h1>
                        <p class="text-slate-500 mt-4 leading-relaxed font-medium">
                            {{ tryout.description || 'Persiapkan diri Anda untuk simulasi CAT paling akurat dengan sistem penilaian terbaru.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">DURASI</p>
                            <p class="text-xl font-black italic">{{ tryout.duration }} MENIT</p>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">HARGA</p>
                            <p class="text-xl font-black italic text-indigo-600">{{ tryout.type === 'free' ? 'GRATIS' : formattedPrice }}</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 animate-in zoom-in duration-700">
                    <div class="bg-slate-900 rounded-[3.5rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden text-center">
                        
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
                        
                        <div v-if="currentStatus === 'upcoming'">
                            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-slate-500 mb-10">Dimulai Dalam</p>
                            
                            <div class="flex justify-center gap-4 lg:gap-6 mb-12">
                                <div v-for="(val, label) in timeLeft" :key="label" class="flex flex-col">
                                    <span class="text-4xl lg:text-5xl font-black italic tracking-tighter">{{ String(val).padStart(2, '0') }}</span>
                                    <span class="text-[8px] font-black uppercase text-slate-500 tracking-widest mt-2">{{ label }}</span>
                                </div>
                            </div>

                            <div class="p-6 bg-white/5 border border-white/10 rounded-3xl inline-block w-full">
                                <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1 italic">Jadwal Pelaksanaan</p>
                                <p class="text-xs font-bold">{{ new Date(tryout.start_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) }} WIB</p>
                            </div>
                        </div>

                        <div v-else-if="currentStatus === 'ongoing'">
                            <div class="mb-8">
                                <span class="text-5xl block mb-4 animate-bounce">🚀</span>
                                <h3 class="text-3xl font-black italic uppercase tracking-tighter">Gerbang Dibuka!</h3>
                                <p class="text-slate-400 text-xs mt-2 font-medium">Waktu pengerjaan telah dimulai. Segera masuk ke ruang ujian.</p>
                            </div>

                            <Link :href="route('exam.start', { tryout_id: tryout.id })" method="post" as="button"
                                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-6 rounded-3xl text-xs font-black uppercase tracking-[0.3em] shadow-xl shadow-indigo-900/50 transition-all active:scale-95">
                                MULAI UJIAN SEKARANG
                            </Link>
                        </div>

                        <div v-else class="py-10">
                            <h3 class="text-3xl font-black italic text-slate-500 uppercase">EVENT BERAKHIR</h3>
                            <p class="text-xs mt-2 text-slate-600">Maaf, periode pengerjaan TO Akbar ini telah ditutup.</p>
                            <Link :href="route('marketplace.index')" class="mt-8 inline-block text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b border-indigo-900 pb-1">Cari Jadwal Lain</Link>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>