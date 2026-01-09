<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
    stats: { type: Object },
});
</script>

<template>
    <Head title="Profil Peserta - CPNS Nusantara" />

    <AuthenticatedLayout>
        <template #header>Informasi Akun & Pencapaian</template>

        <div class="max-w-6xl mx-auto space-y-10 animate-in fade-in duration-700">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-5">
                    <div class="bg-slate-900 rounded-[3rem] p-8 h-full relative overflow-hidden shadow-2xl shadow-indigo-200 group">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/40 transition-all duration-700"></div>
                        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>
                        
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-10">
                                    <h3 class="text-white font-black italic tracking-tighter text-xl uppercase italic">Nusantara<span class="text-indigo-400">ID</span></h3>
                                    <span class="bg-white/10 backdrop-blur-md text-white px-4 py-1.5 rounded-xl text-[8px] font-black uppercase tracking-widest border border-white/10">Member Terverifikasi</span>
                                </div>
                                
                                <div class="flex items-center gap-6 mb-12">
                                    <div class="w-20 h-20 rounded-3xl bg-indigo-600 flex items-center justify-center font-black text-white text-3xl italic shadow-2xl shadow-indigo-900">
                                        {{ $page.props.auth.user.name.substring(0,2).toUpperCase() }}
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">{{ $page.props.auth.user.name }}</h4>
                                        <p class="text-indigo-300 text-[10px] font-black uppercase tracking-widest">{{ $page.props.auth.user.email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-6 rounded-[2rem]">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">ID PESERTA</p>
                                        <p class="text-xs font-mono text-indigo-200 font-bold">#{{ String($page.props.auth.user.id).padStart(6, '0') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">BERGABUNG</p>
                                        <p class="text-xs font-mono text-indigo-200 font-bold">JAN 2026</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-sm relative group overflow-hidden">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Skor Tertinggi</p>
                        <h3 class="text-5xl font-black italic tracking-tighter text-slate-900 mb-4">{{ stats.highest_score }}</h3>
                        <p class="text-[10px] font-bold text-emerald-500 uppercase">Top Performer ⚡</p>
                        <span class="absolute right-8 bottom-8 text-4xl opacity-10 group-hover:scale-125 transition-transform duration-500">🏆</span>
                    </div>

                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-sm relative group overflow-hidden">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Total Simulasi</p>
                        <h3 class="text-5xl font-black italic tracking-tighter text-slate-900 mb-4">{{ stats.total_exams }}</h3>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase">Fokus & Konsisten 🎯</p>
                        <span class="absolute right-8 bottom-8 text-4xl opacity-10 group-hover:rotate-12 transition-transform duration-500">📝</span>
                    </div>

                    <div class="md:col-span-2 bg-indigo-50 p-10 rounded-[2.5rem] border border-indigo-100 flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-black italic text-indigo-900 uppercase tracking-tighter">Status Kelulusan</h4>
                            <p class="text-sm text-indigo-600 font-medium">Anda telah lulus sebanyak <span class="font-black underline">{{ stats.passed_count }} kali</span> dari seluruh percobaan.</p>
                        </div>
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-indigo-100">
                            🎉
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>
            </div>

            <div class="bg-rose-50 p-10 rounded-[3rem] border border-rose-100">
                <DeleteUserForm class="max-w-xl text-rose-900" />
            </div>

        </div>
    </AuthenticatedLayout>
</template>