<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    recent_exams: Array
});

const today = new Date().toLocaleDateString('id-ID', { 
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
});
</script>

<template>
    <Head title="Admin Dashboard - CPNS Nusantara" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 animate-in fade-in slide-in-from-top duration-700">
                <div>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-2 italic">Control Center</p>
                    <h1 class="text-5xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Overview</h1>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ today }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-slate-200 relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Peserta Terdaftar</p>
                        <h3 class="text-4xl font-black italic">{{ stats.total_users }}</h3>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-10 group-hover:scale-110 transition-transform duration-500">👥</span>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Paket Soal Aktif</p>
                        <h3 class="text-4xl font-black italic text-slate-900">{{ stats.total_tryouts }}</h3>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform duration-500">📝</span>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Simulasi</p>
                        <h3 class="text-4xl font-black italic text-slate-900">{{ stats.total_exams }}</h3>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform duration-500">⚡</span>
                </div>

                <div class="bg-indigo-600 p-8 rounded-[2.5rem] text-white shadow-xl shadow-indigo-100 relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-1">Lulus Hari Ini</p>
                        <h3 class="text-4xl font-black italic">{{ stats.passed_today }}</h3>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-20 group-hover:scale-110 transition-transform duration-500">🎯</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-8 bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden p-8 lg:p-12">
                    <div class="flex justify-between items-center mb-10">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Aktivitas Ujian Terbaru</h4>
                        <Link :href="route('admin.tryouts.index')" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest border-b-2 border-indigo-100 pb-1">Lihat Semua</Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[9px] font-black text-slate-300 uppercase tracking-widest border-b border-slate-50">
                                    <th class="pb-4">Peserta</th>
                                    <th class="pb-4">Paket</th>
                                    <th class="pb-4 text-center">Skor</th>
                                    <th class="pb-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="exam in recent_exams" :key="exam.id" class="group transition-all">
                                    <td class="py-5">
                                        <p class="text-xs font-black text-slate-800 uppercase italic">{{ exam.user?.name }}</p>
                                        <p class="text-[9px] text-slate-400 font-medium">{{ exam.user?.email }}</p>
                                    </td>
                                    <td class="py-5 text-[10px] font-bold text-slate-600">{{ exam.tryout?.name }}</td>
                                    <td class="py-5 text-center font-black text-xs">{{ exam.total_score }}</td>
                                    <td class="py-5 text-right">
                                        <span :class="exam.is_passed ? 'text-emerald-500 bg-emerald-50 border-emerald-100' : 'text-rose-500 bg-rose-50 border-rose-100'"
                                              class="px-3 py-1 rounded-lg text-[8px] font-black uppercase border italic">
                                            {{ exam.is_passed ? 'Lulus' : 'Gagal' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-indigo-50 p-10 rounded-[3rem] border border-indigo-100 relative overflow-hidden group">
                        <h4 class="text-[10px] font-black text-indigo-900 uppercase tracking-widest mb-6 relative z-10">Aksi Cepat</h4>
                        <div class="space-y-3 relative z-10">
                            <Link :href="route('admin.tryouts.create')" class="flex items-center justify-between w-full bg-white p-5 rounded-2xl shadow-sm hover:bg-slate-900 hover:text-white transition-all group">
                                <span class="text-[10px] font-black uppercase tracking-widest">Buat Paket Baru</span>
                                <span class="text-lg">→</span>
                            </Link>
                            <Link :href="route('admin.users.index')" class="flex items-center justify-between w-full bg-white p-5 rounded-2xl shadow-sm hover:bg-slate-900 hover:text-white transition-all">
                                <span class="text-[10px] font-black uppercase tracking-widest">Kelola Peserta</span>
                                <span class="text-lg">→</span>
                            </Link>
                        </div>
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-200/20 rounded-full blur-3xl"></div>
                    </div>

                    <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm italic">
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-4">💡 Insight</p>
                        <p class="text-xs text-slate-600 font-medium leading-relaxed">
                            Rata-rata kelulusan hari ini naik <span class="text-emerald-500 font-black">12%</span>. Pastikan bank soal diperbarui setiap minggu untuk variasi materi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <p class="text-[8px] font-black text-slate-300 uppercase tracking-[0.4em]">CPNS Nusantara Engine v1.0.4</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.animate-in {
    animation-duration: 0.7s;
    animation-fill-mode: both;
}
</style>