<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tryout: Object
});

const form = useForm({
    file: null,
});

const isDragging = ref(false);

const handleFileChange = (e) => {
    form.file = e.target.files[0];
};

const handleDrop = (e) => {
    isDragging.value = false;
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        form.file = files[0];
    }
};

const submit = () => {
    if (!form.file) return;
    form.post(route('admin.questions.import', props.tryout.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Bulk Import Soal - CPNS Nusantara" />

    <AuthenticatedLayout>
        <div class="max-w-5xl mx-auto pb-20">
            
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 px-4 lg:px-0">
                <div>
                    <Link :href="route('admin.tryouts.index')" class="text-[10px] font-black uppercase text-slate-400 hover:text-indigo-600 tracking-[0.2em] transition-all flex items-center gap-2 mb-4">
                        <span>←</span> Kembali ke Inventori
                    </Link>
                    <h2 class="text-4xl lg:text-5xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Bulk Import</h2>
                    <p class="text-slate-500 font-medium mt-2">Menambahkan soal secara massal ke paket: <span class="text-indigo-600 font-bold underline">{{ tryout.name }}</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start px-4 lg:px-0">
                
                <div class="lg:col-span-5 space-y-6">
                    
                    <div class="bg-indigo-600 rounded-[3rem] p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden group">
                        <div class="relative z-10">
                            <h3 class="text-lg font-black uppercase italic tracking-tighter mb-2">Gunakan Template</h3>
                            <p class="text-indigo-100 text-[11px] font-medium leading-relaxed mb-8">
                                Pastikan struktur file Excel/CSV Anda sesuai dengan standar sistem CPNS Nusantara untuk menghindari kegagalan sistem.
                            </p>
                            <a :href="route('admin.questions.template')" 
                               class="inline-flex items-center gap-3 bg-white text-indigo-600 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-lg active:scale-95">
                                <span>📥</span> Download Template .CSV
                            </a>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                    </div>

                    <div class="bg-white rounded-[3rem] p-8 border border-slate-200 shadow-sm">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 bg-slate-100 rounded-lg flex items-center justify-center text-[10px]">📝</span>
                            Aturan Format Data
                        </h3>
                        <ul class="space-y-4 text-[11px] font-bold italic text-slate-500">
                            <li class="flex items-start gap-3">
                                <span class="text-indigo-600 font-black">01.</span>
                                <span>Kategori wajib diisi: <strong class="text-slate-900">TWK, TIU, atau TKP</strong>.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-indigo-600 font-black">02.</span>
                                <span>Jawaban Benar hanya menerima huruf: <strong class="text-slate-900">A, B, C, D, atau E</strong>.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-indigo-600 font-black">03.</span>
                                <span>Maksimal ukuran file adalah <strong class="text-slate-900">2MB</strong>.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div 
                            @dragover.prevent="isDragging = true" 
                            @dragleave.prevent="isDragging = false" 
                            @drop.prevent="handleDrop"
                            :class="[
                                isDragging ? 'border-indigo-500 bg-indigo-50/50 ring-4 ring-indigo-100' : 'border-slate-200 bg-white',
                                form.file ? 'border-emerald-500 bg-emerald-50/20' : ''
                            ]"
                            class="relative border-4 border-dashed rounded-[4rem] p-12 lg:p-20 text-center transition-all duration-300 group"
                        >
                            <input 
                                type="file" 
                                @change="handleFileChange" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                accept=".csv, .xlsx, .xls"
                            />
                            
                            <div class="relative z-0">
                                <div class="text-6xl mb-6 group-hover:scale-110 transition-transform duration-500">
                                    {{ form.file ? '📄' : (isDragging ? '📥' : '☁️') }}
                                </div>
                                
                                <div v-if="!form.file">
                                    <h4 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">
                                        {{ isDragging ? 'Lepaskan File Disini' : 'Pilih File Excel / CSV' }}
                                    </h4>
                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-2">Atau Seret & Lepas File ke Area Ini</p>
                                </div>
                                
                                <div v-else class="animate-in fade-in duration-500">
                                    <h4 class="text-xl font-black text-emerald-600 uppercase italic tracking-tighter">File Terdeteksi!</h4>
                                    <p class="text-sm font-bold text-slate-700 mt-2">{{ form.file.name }}</p>
                                    <button type="button" @click.stop="form.file = null" class="mt-4 text-[9px] font-black text-rose-500 uppercase underline tracking-widest hover:text-rose-700">Ganti File</button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-[3rem] border border-slate-200 flex flex-col gap-4">
                            <button 
                                type="submit" 
                                :disabled="form.processing || !form.file"
                                class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] text-xs font-black uppercase tracking-[0.3em] shadow-2xl shadow-slate-200 hover:bg-indigo-600 hover:shadow-indigo-100 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ form.processing ? 'Sedang Memproses Data...' : '🚀 Konfirmasi & Import Soal' }}
                            </button>
                            
                            <div v-if="form.errors.file" class="bg-rose-50 p-4 rounded-2xl border border-rose-100 flex items-center gap-3">
                                <span class="text-rose-500">⚠️</span>
                                <p class="text-[10px] font-bold text-rose-600 uppercase">{{ form.errors.file }}</p>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.animate-in {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>