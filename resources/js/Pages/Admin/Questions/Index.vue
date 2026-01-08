<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({ questions: Array });

const isModalOpen = ref(false);
const isEditMode = ref(false);
const editingId = ref(null);
const expandedId = ref(null);

const sortedQuestions = computed(() => {
    const order = { 'TWK': 1, 'TIU': 2, 'TKP': 3 };
    return [...props.questions].sort((a, b) => (order[a.type] || 99) - (order[b.type] || 99));
});

const form = useForm({
    content: '', type: 'TWK', 
    option_a: '', option_b: '', option_c: '', option_d: '', option_e: '', 
    correct_answer: 'A', explanation: '',
    point_a: 1, point_b: 2, point_c: 3, point_d: 4, point_e: 5
});

// Fungsi untuk membuka modal Tambah
const openCreateModal = () => {
    isEditMode.value = false;
    editingId.value = null;
    form.reset();
    isModalOpen.value = true;
};

// Fungsi untuk membuka modal Edit
const openEditModal = (q) => {
    isEditMode.value = true;
    editingId.value = q.id;
    form.content = q.content;
    form.type = q.type;
    form.option_a = q.option_a;
    form.option_b = q.option_b;
    form.option_c = q.option_c;
    form.option_d = q.option_d;
    form.option_e = q.option_e;
    form.correct_answer = q.correct_answer;
    form.explanation = q.explanation || '';
    form.point_a = q.point_a;
    form.point_b = q.point_b;
    form.point_c = q.point_c;
    form.point_d = q.point_d;
    form.point_e = q.point_e;
    isModalOpen.value = true;
};

const submit = () => {
    if (isEditMode.value) {
        form.patch(route('admin.questions.update', editingId.value), {
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('admin.questions.store'), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};
</script>

<template>
    <Head title="Bank Soal" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center max-w-4xl mx-auto w-full px-4">
                <h2 class="text-lg font-bold text-slate-800 tracking-tight">Manajemen Soal</h2>
                <button @click="openCreateModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-100">
                    + Soal
                </button>
            </div>
        </template>

        <div class="max-w-4xl mx-auto py-8 px-4">
            <div class="bg-white border border-slate-200 rounded-[1.5rem] overflow-hidden shadow-sm">
                <div v-for="q in sortedQuestions" :key="q.id" class="border-b border-slate-100 last:border-0">
                    <div @click="expandedId = expandedId === q.id ? null : q.id" class="grid grid-cols-12 gap-4 px-6 py-5 items-center cursor-pointer hover:bg-slate-50/50 transition-colors">
                        <div class="col-span-2">
                            <span :class="q.type === 'TWK' ? 'bg-violet-50 text-violet-600' : (q.type === 'TIU' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600')" class="px-2.5 py-1 rounded-lg text-[10px] font-black border uppercase tracking-tighter">
                                {{ q.type }}
                            </span>
                        </div>
                        <div class="col-span-7">
                            <p class="text-sm text-slate-600 truncate pr-4 font-medium">{{ q.content }}</p>
                        </div>
                        <div class="col-span-3 text-right flex justify-end items-center gap-3">
                            <button @click.stop="openEditModal(q)" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">Edit</button>
                            <button @click.stop="router.delete(route('admin.questions.destroy', q.id))" class="text-slate-300 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>

                    <div v-if="expandedId === q.id" class="px-6 pb-6 pt-2 bg-slate-50/30">
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-inner">
                            <p class="text-sm text-slate-800 leading-relaxed mb-6 font-semibold">{{ q.content }}</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div v-for="opt in ['a','b','c','d','e']" :key="opt" 
                                     :class="q.type !== 'TKP' && q.correct_answer === opt.toUpperCase() ? 'bg-indigo-50 border-indigo-100' : 'bg-white border-slate-100'"
                                     class="flex justify-between items-center p-3 rounded-xl border text-xs">
                                    <span class="text-slate-600"><span class="font-bold text-slate-400 mr-2 uppercase">{{ opt }}.</span> {{ q['option_'+opt] }}</span>
                                    <span v-if="q.type === 'TKP'" class="font-black text-indigo-500">+{{ q['point_'+opt] }}</span>
                                    <span v-else-if="q.correct_answer === opt.toUpperCase()" class="text-indigo-600 font-black italic">Kunci</span>
                                </div>
                            </div>

                            <div v-if="q.explanation" class="mt-6 pt-4 border-t border-slate-100">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pembahasan:</h4>
                                <p class="text-xs text-slate-600 leading-relaxed italic">{{ q.explanation }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-8 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-black text-slate-800">{{ isEditMode ? 'Edit Soal' : 'Tambah Soal' }}</h3>
                    <button @click="closeModal" class="text-slate-400">✕</button>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="flex p-1 bg-slate-100 rounded-xl">
                        <button v-for="t in ['TWK', 'TIU', 'TKP']" :key="t" type="button" 
                                @click="form.type = t"
                                :class="form.type === t ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500'"
                                class="flex-1 py-2 text-[10px] font-black rounded-lg transition-all">{{ t }}</button>
                    </div>

                    <textarea v-model="form.content" rows="4" class="w-full border-slate-200 rounded-2xl text-sm focus:ring-indigo-500" placeholder="Pertanyaan..."></textarea>

                    <div class="space-y-3">
                        <div v-for="opt in ['a','b','c','d','e']" :key="opt" class="flex gap-2">
                            <input v-model="form['option_'+opt]" type="text" :placeholder="'Opsi ' + opt.toUpperCase()" class="flex-1 border-slate-200 rounded-xl text-sm" />
                            <div v-if="form.type === 'TKP'" class="flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-xl px-2">
                                <input v-model="form['point_'+opt]" type="number" class="w-10 border-none bg-transparent text-sm p-1 font-bold text-indigo-600 text-center" />
                            </div>
                            <div v-else class="flex items-center px-3 bg-slate-50 rounded-xl border border-slate-200">
                                <input type="radio" :value="opt.toUpperCase()" v-model="form.correct_answer" class="text-indigo-600" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pembahasan</label>
                        <textarea v-model="form.explanation" rows="3" class="w-full border-slate-200 rounded-2xl text-sm mt-1 focus:ring-indigo-500" placeholder="Tuliskan alasan atau cara penyelesaian soal..."></textarea>
                    </div>

                    <button type="submit" :disabled="form.processing" class="w-full bg-indigo-600 text-white py-4 rounded-2xl text-sm font-black shadow-lg">
                        {{ isEditMode ? 'Simpan Perubahan' : 'Simpan ke Bank Soal' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>