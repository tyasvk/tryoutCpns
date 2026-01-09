<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ 
    question: Object,
    tryout: Object 
});

const imagePreview = ref(props.question.image ? `/storage/${props.question.image}` : null);
const fileInput = ref(null);

const form = useForm({
    _method: 'PATCH', // Penting untuk upload file via Inertia update
    subject: props.question.subject,
    content: props.question.content,
    image: null,
    option_a: props.question.option_a, 
    option_b: props.question.option_b, 
    option_c: props.question.option_c, 
    option_d: props.question.option_d, 
    option_e: props.question.option_e,
    points_a: props.question.points_a, 
    points_b: props.question.points_b, 
    points_c: props.question.points_c, 
    points_d: props.question.points_d, 
    points_e: props.question.points_e,
    correct_answer: props.question.correct_answer,
    explanation: props.question.explanation,
});

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const triggerFileInput = () => fileInput.value.click();

const submitUpdate = () => {
    // Sinkronisasi poin otomatis jika bukan TKP
    if (form.subject !== 'TKP') {
        ['a','b','c','d','e'].forEach(opt => {
            form['points_' + opt] = (form.correct_answer === opt.toUpperCase()) ? 5 : 0;
        });
    }
    form.post(route('admin.questions.update', props.question.id));
};
</script>

<template>
    <Head title="Edit Soal - CPNS Nusantara" />

    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto pb-16 px-4 lg:px-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pt-4">
                <div>
                    <Link :href="route('admin.questions.manage', tryout.id)" class="text-[9px] font-black uppercase text-slate-400 hover:text-indigo-600 tracking-[0.2em] transition-all flex items-center gap-2">
                        <span>←</span> BATALKAN PERUBAHAN
                    </Link>
                    <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter mt-1 leading-none">Edit Soal</h1>
                </div>

                <div class="flex p-1 bg-white border border-slate-200 rounded-2xl shadow-sm">
                    <button v-for="cat in ['TWK', 'TIU', 'TKP']" :key="cat" type="button"
                        @click="form.subject = cat"
                        :class="form.subject === cat ? 'bg-slate-900 text-white shadow-md' : 'text-slate-400'"
                        class="px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                        {{ cat }}
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitUpdate" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-[2rem] p-6 lg:p-8 shadow-sm border border-slate-200">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 block">Konten & Media</label>

                        <div class="mb-6 group relative border-2 border-dashed border-slate-100 rounded-[1.5rem] bg-slate-50/50 hover:bg-indigo-50/30 transition-all cursor-pointer overflow-hidden" @click="triggerFileInput">
                            <input type="file" ref="fileInput" @change="handleImageUpload" class="hidden" accept="image/*" />
                            
                            <div v-if="!imagePreview" class="py-10 text-center text-slate-300 font-bold uppercase text-[9px]">
                                <span class="text-3xl block mb-2">🖼️</span> Tambah Gambar
                            </div>
                            
                            <div v-else class="relative group">
                                <img :src="imagePreview" class="w-full h-[220px] object-cover rounded-[1rem]" />
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center backdrop-blur-sm rounded-[1rem]">
                                    <span class="text-white text-[9px] font-black uppercase tracking-widest">Ganti Gambar</span>
                                </div>
                            </div>
                        </div>

                        <textarea v-model="form.content" rows="6" class="w-full bg-slate-50 border-none rounded-[1.5rem] p-6 font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50 transition-all"></textarea>
                    </div>

                    <div class="bg-white rounded-[2rem] p-6 lg:p-8 shadow-sm border border-slate-200">
                        <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-8">Pilihan Jawaban</h3>
                        <div class="space-y-4">
                            <div v-for="opt in ['a','b','c','d','e']" :key="opt" class="flex flex-col md:flex-row gap-3">
                                <div class="flex-1 flex items-center gap-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black uppercase text-xs shadow-md">{{ opt }}</div>
                                    <input v-model="form['option_'+opt]" type="text" class="flex-1 bg-slate-50 border-none rounded-xl p-4 font-bold text-sm text-slate-700 focus:ring-4 focus:ring-indigo-50">
                                </div>
                                <div v-if="form.subject === 'TKP'" class="flex items-center gap-2">
                                    <span class="text-[8px] font-black text-slate-300 italic uppercase">Poin</span>
                                    <input v-model="form['points_'+opt]" type="number" class="w-16 bg-amber-50 border-none rounded-xl p-4 text-center font-black text-amber-600">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm sticky top-10">
                        <div v-if="form.subject !== 'TKP'" class="mb-8 text-center">
                            <label class="text-[9px] font-black text-slate-400 uppercase mb-4 block tracking-widest">Kunci Jawaban</label>
                            <div class="flex justify-center gap-2">
                                <button v-for="opt in ['A','B','C','D','E']" :key="opt" type="button"
                                    @click="form.correct_answer = opt"
                                    :class="form.correct_answer === opt ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-50 text-slate-400'"
                                    class="w-10 h-10 rounded-lg font-black text-[10px] transition-all">
                                    {{ opt }}
                                </button>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="text-[9px] font-black text-slate-400 uppercase mb-3 block tracking-widest">Pembahasan</label>
                            <textarea v-model="form.explanation" rows="5" class="w-full bg-slate-50 border-none rounded-xl p-4 text-xs font-bold text-slate-600 leading-relaxed"></textarea>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full bg-slate-900 text-white py-5 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-indigo-600 transition-all">
                            {{ form.processing ? 'MEMPERBARUI...' : '💾 SIMPAN PERUBAHAN' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>