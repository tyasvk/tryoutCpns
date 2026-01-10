<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    tryout: Object,
    question: Object
});

const form = useForm({
    subject: props.question?.subject ?? 'TWK',
    content: props.question?.content ?? '',
    option_a: props.question?.option_a ?? '',
    option_b: props.question?.option_b ?? '',
    option_c: props.question?.option_c ?? '',
    option_d: props.question?.option_d ?? '',
    option_e: props.question?.option_e ?? '',
    correct_answer: props.question?.correct_answer ?? 'A',
    points_a: props.question?.points_a ?? 0,
    points_b: props.question?.points_b ?? 0,
    points_c: props.question?.points_c ?? 0,
    points_d: props.question?.points_d ?? 0,
    points_e: props.question?.points_e ?? 0,
    explanation: props.question?.explanation ?? '',
});

const submit = () => {
    if (props.question) {
        form.patch(route('admin.questions.update', props.question.id));
    } else {
        form.post(route('admin.questions.store', props.tryout.id));
    }
};
</script>

<template>
    <Head :title="question ? 'Edit Soal' : 'Tambah Soal'" />

    <AuthenticatedLayout>
        <template #header>{{ question ? 'Perbarui Data Soal' : 'Input Soal Baru' }}</template>

        <form @submit.prevent="submit" class="max-w-4xl space-y-6 pb-20">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Materi Soal</label>
                <div class="grid grid-cols-3 gap-3">
                    <button v-for="sub in ['TWK', 'TIU', 'TKP']" :key="sub" type="button"
                        @click="form.subject = sub"
                        :class="form.subject === sub ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-50 text-slate-400 border border-slate-100'"
                        class="py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                        {{ sub }}
                    </button>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Pertanyaan</label>
                <textarea v-model="form.content" rows="5" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Tuliskan soal disini..."></textarea>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-4">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Opsi Jawaban & Bobot</label>
                
                <div v-for="opt in ['a', 'b', 'c', 'd', 'e']" :key="opt" class="flex gap-4 items-center">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black uppercase shrink-0">{{ opt }}</div>
                    <input v-model="form['option_' + opt]" type="text" class="flex-1 bg-slate-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500" :placeholder="'Teks Opsi ' + opt.toUpperCase()">
                    
                    <div v-if="form.subject === 'TKP'" class="w-20">
                        <input v-model="form['points_' + opt]" type="number" class="w-full bg-indigo-50 border-none rounded-xl text-center text-xs font-black text-indigo-600" placeholder="Poin">
                    </div>
                </div>
            </div>

            <div v-if="form.subject !== 'TKP'" class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Kunci Jawaban Benar</label>
                <select v-model="form.correct_answer" class="w-full bg-slate-50 border-none rounded-2xl text-xs font-black uppercase tracking-widest focus:ring-2 focus:ring-indigo-500">
                    <option v-for="opt in ['A', 'B', 'C', 'D', 'E']" :key="opt" :value="opt">Pilihan {{ opt }}</option>
                </select>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Pembahasan / Penjelasan</label>
                <textarea v-model="form.explanation" rows="3" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500" placeholder="Jelaskan mengapa jawaban tersebut benar..."></textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" :disabled="form.processing" class="flex-1 bg-slate-900 text-white py-5 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.3em] hover:bg-indigo-600 transition-all">
                    {{ question ? 'Perbarui Soal' : 'Simpan Soal Sekarang' }}
                </button>
                <Link :href="route('admin.questions.manage', tryout.id)" class="px-8 bg-white border border-slate-200 text-slate-400 flex items-center justify-center rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest">Batal</Link>
            </div>
        </form>
    </AuthenticatedLayout>
</template>