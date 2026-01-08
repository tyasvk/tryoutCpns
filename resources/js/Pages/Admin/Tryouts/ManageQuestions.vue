<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ tryout: Object, questions: Array });

const isModalOpen = ref(false);
const editingQuestion = ref(null);

const form = useForm({
    tryout_id: props.tryout.id,
    content: '',
    type: 'TIU',
    option_a: '', point_a: 0,
    option_b: '', point_b: 0,
    option_c: '', point_c: 0,
    option_d: '', point_d: 0,
    option_e: '', point_e: 0,
    answer: 'A',
    explanation: ''
});

const openModal = (item = null) => {
    editingQuestion.value = item;
    if (item) {
        form.content = item.content;
        form.type = item.type;
        form.option_a = item.option_a; form.point_a = item.point_a;
        form.option_b = item.option_b; form.point_b = item.point_b;
        form.option_c = item.option_c; form.point_c = item.point_c;
        form.option_d = item.option_d; form.point_d = item.point_d;
        form.option_e = item.option_e; form.point_e = item.point_e;
        form.answer = item.answer;
        form.explanation = item.explanation;
    } else {
        form.reset();
        form.tryout_id = props.tryout.id;
    }
    isModalOpen.value = true;
};

const submit = () => {
    if (editingQuestion.value) {
        form.put(route('admin.questions.update', editingQuestion.value.id), {
            onSuccess: () => isModalOpen.value = false
        });
    } else {
        form.post(route('admin.questions.store'), {
            onSuccess: () => isModalOpen.value = false
        });
    }
};
</script>

<template>
    <Head :title="'Kelola Soal: ' + tryout.name" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full max-w-5xl mx-auto px-4">
                <h2 class="text-xl font-black text-slate-900 tracking-tighter uppercase italic">{{ tryout.name }}</h2>
                <button @click="openModal()" class="bg-slate-950 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl">
                    + Tambah Soal
                </button>
            </div>
        </template>

        <div class="max-w-5xl mx-auto space-y-4 pb-20 px-4">
            <div v-for="(q, index) in questions" :key="q.id" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group">
                <div class="flex justify-between items-start mb-4">
                    <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ q.type }}</span>
                    <div class="flex gap-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="openModal(q)" class="text-[9px] font-black uppercase text-indigo-600">Edit</button>
                        <button @click="router.delete(route('admin.questions.destroy', q.id))" class="text-[9px] font-black uppercase text-red-400">Hapus</button>
                    </div>
                </div>
                <p class="text-sm font-bold text-slate-800 leading-relaxed">{{ q.content }}</p>
                
                <div v-if="q.explanation" class="mt-3 p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                    <p class="text-[8px] font-black text-indigo-600 uppercase tracking-widest">💡 Pembahasan Tersedia</p>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-2xl rounded-[2.5rem] p-8 max-h-[90vh] overflow-y-auto shadow-2xl relative">
                <h3 class="text-xl font-black text-slate-900 mb-6 uppercase italic">Konfigurasi Soal</h3>
                
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Tipe Soal</label>
                            <select v-model="form.type" class="w-full bg-slate-50 border-none rounded-xl text-xs font-bold p-4">
                                <option value="TWK">TWK (Wawasan Kebangsaan)</option>
                                <option value="TIU">TIU (Intelegensia Umum)</option>
                                <option value="TKP">TKP (Karakteristik Pribadi)</option>
                            </select>
                        </div>
                        <div v-if="form.type !== 'TKP'" class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Kunci Jawaban</label>
                            <select v-model="form.answer" class="w-full bg-indigo-50 border-none rounded-xl text-xs font-black p-4 text-indigo-600">
                                <option v-for="opt in ['A','B','C','D','E']" :key="opt" :value="opt">Jawaban Benar: {{ opt }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Pertanyaan</label>
                        <textarea v-model="form.content" placeholder="Tulis soal di sini..." class="w-full bg-slate-50 border-none rounded-2xl text-xs p-4 min-h-[100px]"></textarea>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Pilihan Jawaban & {{ form.type === 'TKP' ? 'Poin' : 'Teks' }}</label>
                        <div v-for="opt in ['a','b','c','d','e']" :key="opt" class="flex items-center gap-2">
                            <span class="w-8 h-8 flex items-center justify-center bg-slate-100 rounded-lg text-[10px] font-black uppercase">{{ opt }}</span>
                            <input v-model="form['option_' + opt]" type="text" :placeholder="'Teks Pilihan ' + opt.toUpperCase()" class="flex-1 bg-slate-50 border-none rounded-xl text-xs p-3">
                            <input v-if="form.type === 'TKP'" v-model="form['point_' + opt]" type="number" placeholder="Poin" class="w-16 bg-indigo-50 border-none rounded-xl text-xs font-black text-center p-3 text-indigo-600">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Pembahasan</label>
                        <textarea v-model="form.explanation" placeholder="Berikan penjelasan jawaban..." class="w-full bg-slate-50 border-none rounded-2xl text-xs p-4 min-h-[80px]"></textarea>
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100">Simpan Soal</button>
                        <button @click="isModalOpen = false" type="button" class="px-8 bg-slate-100 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>