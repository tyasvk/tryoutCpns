<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    question: Object
});
</script>

<template>
    <Head :title="'Preview Soal - ' + question.subject" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto pb-20 px-4">
            
            <div class="flex justify-between items-center mb-10 pt-6">
                <div>
                    <Link :href="route('admin.questions.manage', question.tryout_id)" class="text-[9px] font-black uppercase text-slate-400 hover:text-indigo-600 tracking-[0.2em] transition-all flex items-center gap-2">
                        <span>←</span> KEMBALI KE DAFTAR
                    </Link>
                    <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter mt-1 leading-none">Pratinjau Soal</h2>
                </div>
                <div class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg">
                    Mode Admin
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                
                <div class="bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-8">
                        <span class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest italic">
                            {{ question.subject }}
                        </span>
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Tampilan Simulasi CAT</span>
                    </div>

                    <div v-if="question.image" class="mb-8 overflow-hidden rounded-2xl border border-slate-100">
                        <img :src="'/storage/' + question.image" class="w-full max-h-[400px] object-contain bg-slate-50" />
                    </div>

                    <h3 class="text-lg lg:text-xl font-bold text-slate-800 leading-relaxed mb-10">
                        {{ question.content }}
                    </h3>

                    <div class="space-y-3">
                        <div v-for="opt in ['a','b','c','d','e']" :key="opt"
                            :class="question.correct_answer === opt.toUpperCase() ? 'border-emerald-500 bg-emerald-50/30' : 'border-slate-100 bg-slate-50'"
                            class="w-full text-left p-5 rounded-2xl border-2 flex items-center gap-4 group transition-all"
                        >
                            <span :class="question.correct_answer === opt.toUpperCase() ? 'bg-emerald-500 text-white' : 'bg-white text-slate-400 border border-slate-200'"
                                  class="w-8 h-8 flex items-center justify-center rounded-lg font-black text-xs uppercase">
                                {{ opt }}
                            </span>
                            <p class="flex-1 text-sm font-bold text-slate-700">{{ question['option_' + opt] }}</p>
                            
                            <div v-if="question.subject === 'TKP'" class="text-[10px] font-black text-amber-600 bg-amber-50 px-3 py-1 rounded-md border border-amber-100">
                                +{{ question['points_' + opt] }} Poin
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-900 rounded-[2.5rem] p-8 lg:p-12 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-300 mb-6">Penjelasan & Kunci Jawaban</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="bg-white/10 p-5 rounded-2xl border border-white/10">
                                <p class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-1">Kunci Jawaban</p>
                                <p class="text-2xl font-black italic">Opsi {{ question.correct_answer }}</p>
                            </div>
                            <div class="bg-white/10 p-5 rounded-2xl border border-white/10">
                                <p class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-1">Status Publish</p>
                                <p class="text-2xl font-black italic">Ready to CAT</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-[8px] font-black text-indigo-200 uppercase tracking-widest">Narasi Pembahasan</p>
                            <p class="text-sm font-medium leading-relaxed italic text-indigo-50">
                                {{ question.explanation || 'Belum ada pembahasan yang diinput untuk soal ini.' }}
                            </p>
                        </div>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>