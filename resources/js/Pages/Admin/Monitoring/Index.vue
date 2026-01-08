<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

const props = defineProps({ activeExams: Array });

// Polling data setiap 10 detik
let timer;
onMounted(() => {
    timer = setInterval(() => {
        router.reload({ only: ['activeExams'], preserveScroll: true });
    }, 10000);
});
onUnmounted(() => clearInterval(timer));

const forceStop = (id) => {
    if (confirm('Hentikan paksa ujian ini? Skor saat ini akan langsung disimpan.')) {
        router.post(route('admin.monitoring.force-stop', id));
    }
};
</script>

<template>
    <Head title="Live Monitoring" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <h2 class="text-lg font-black uppercase italic text-slate-800">Live Simulation Monitoring</h2>
            </div>
        </template>

        <div class="max-w-5xl mx-auto space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="exam in activeExams" :key="exam.id" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:border-indigo-200 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-sm font-black text-slate-800 uppercase leading-none">{{ exam.user_name }}</h4>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ exam.tryout_name }}</p>
                            </div>
                            <span class="text-[9px] font-black px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg">Mulai: {{ exam.started_at }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-50 p-3 rounded-2xl">
                                <p class="text-[8px] font-black text-slate-400 uppercase">Sisa Waktu</p>
                                <p class="text-sm font-black text-slate-800">{{ exam.remaining_time }}</p>
                            </div>
                            <div class="bg-red-50 p-3 rounded-2xl">
                                <p class="text-[8px] font-black text-red-400 uppercase">Violations</p>
                                <p :class="exam.violations > 0 ? 'text-red-600' : 'text-slate-400'" class="text-sm font-black">{{ exam.violations }} / 3</p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-6">
                            <div class="flex justify-between items-center text-[9px] font-black uppercase text-slate-400">
                                <span>Estimasi Progres</span>
                                <span>{{ exam.progress }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-50 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-600" :style="{ width: exam.progress + '%' }"></div>
                            </div>
                        </div>
                    </div>

                    <button @click="forceStop(exam.id)" class="w-full py-3 bg-slate-900 hover:bg-red-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                        Hentikan Sesi ✖
                    </button>
                </div>
            </div>

            <div v-if="activeExams.length === 0" class="py-20 text-center bg-slate-50 border-2 border-dashed border-slate-100 rounded-[3rem]">
                <p class="text-xs font-bold text-slate-300 uppercase italic">Tidak ada ujian yang sedang berlangsung.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>