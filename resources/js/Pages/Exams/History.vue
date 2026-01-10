<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    exams: Array
});
</script>

<template>
    <Head title="Riwayat Ujian" />
    <AuthenticatedLayout>
        <template #header>Riwayat Ujian</template>

        <div v-if="exams.length === 0" class="bg-white p-12 rounded-[2rem] text-center border border-dashed">
            <p class="text-slate-400 font-bold uppercase text-[10px]">Belum ada riwayat ujian.</p>
        </div>

        <div v-else class="space-y-4">
            <div v-for="exam in exams" :key="exam.id" class="bg-white p-6 rounded-[2rem] border border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">{{ exam.completed_at }}</p>
                    <h3 class="text-lg font-black uppercase italic tracking-tighter">{{ exam.tryout?.name }}</h3>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-[8px] font-black text-slate-400 uppercase">Skor</p>
                        <p class="text-xl font-black italic">{{ exam.total_score }}</p>
                    </div>
                    <Link :href="route('exam.review', exam.id)" class="bg-slate-900 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">
                        Review
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>