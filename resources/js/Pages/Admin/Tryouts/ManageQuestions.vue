<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { ref, computed, watch, nextTick } from 'vue';
import draggable from 'vuedraggable';

const props = defineProps({ 
    tryout: Object, 
    questions: Array 
});

// --- STATE MANAGEMENT ---
const isModalOpen = ref(false);
const editingQuestion = ref(null);
const searchQuery = ref('');
const filterType = ref('ALL');
const expandedQuestions = ref([]);
const imagePreview = ref(null);

// State lokal untuk Draggable (agar reaktif instan)
const localQuestions = ref([...props.questions]);

// Sinkronisasi data saat props berubah (setelah save/delete dari server)
watch(() => props.questions, (newVal) => {
    localQuestions.value = [...newVal];
});

// --- HELPER IMAGE URL ---
const getImageUrl = (path) => {
    if (!path) return null;
    // Jika path sudah url lengkap (http...), kembalikan langsung
    if (path.startsWith('http')) return path;
    
    // Hapus 'public/' jika tersimpan di DB, dan hapus slash awal
    const cleanPath = path.replace(/^public\//, '').replace(/^\/+/, '');
    return `/storage/${cleanPath}`;
};

const handleImageError = (e) => {
    e.target.style.display = 'none'; // Sembunyikan jika broken
};

// --- FORM HANDLING ---
const form = useForm({
    tryout_id: props.tryout.id,
    content: '',
    image: null, // Input File
    type: 'TWK',
    option_a: '', point_a: 0,
    option_b: '', point_b: 0,
    option_c: '', point_c: 0,
    option_d: '', point_d: 0,
    option_e: '', point_e: 0,
    answer: 'A',
    explanation: ''
});

// --- LOGIKA UPLOAD ---
const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
};

// --- LOGIKA DRAG & DROP ---
const onDragEnd = () => {
    const orderedIds = localQuestions.value.map(q => q.id);
    router.post(route('admin.questions.reorder'), {
        ordered_ids: orderedIds
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

// --- LOGIKA UI (Expand, Filter, Stats) ---
const toggleExpand = (id) => {
    if (expandedQuestions.value.includes(id)) {
        expandedQuestions.value = expandedQuestions.value.filter(qId => qId !== id);
    } else {
        expandedQuestions.value.push(id);
    }
};

const isVisible = (q) => {
    const matchesSearch = q.content.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesType = filterType.value === 'ALL' || q.type === filterType.value;
    return matchesSearch && matchesType;
};

const stats = computed(() => {
    return {
        twk: props.questions.filter(q => q.type === 'TWK').length,
        tiu: props.questions.filter(q => q.type === 'TIU').length,
        tkp: props.questions.filter(q => q.type === 'TKP').length,
        total: props.questions.length
    }
});

// --- MODAL & CRUD ---
const openModal = (item = null) => {
    editingQuestion.value = item;
    imagePreview.value = null;
    form.clearErrors();

    if (item) {
        // --- MODE EDIT ---
        form.content = item.content;
        form.type = item.type;
        
        // Form image null (karena tidak upload baru), tapi preview ambil dari DB
        form.image = null; 
        imagePreview.value = getImageUrl(item.image);

        form.option_a = item.option_a; form.point_a = item.point_a;
        form.option_b = item.option_b; form.point_b = item.point_b;
        form.option_c = item.option_c; form.point_c = item.point_c;
        form.option_d = item.option_d; form.point_d = item.point_d;
        form.option_e = item.option_e; form.point_e = item.point_e;
        form.answer = item.answer;
        form.explanation = item.explanation;
    } else {
        // --- MODE CREATE ---
        form.reset();
        form.tryout_id = props.tryout.id;
        form.type = 'TWK';
        form.answer = 'A';
    }
    isModalOpen.value = true;
};

// --- SUBMIT HANDLING (FIXED METHOD NOT ALLOWED) ---
const submit = (createAnother = false) => {
    if (editingQuestion.value) {
        // UPDATE: Gunakan transform untuk inject _method: 'put'
        // Ini wajib agar file gambar bisa terkirim via POST ke route PUT
        form.transform((data) => ({
            ...data,
            _method: 'put'
        })).post(route('admin.questions.update', editingQuestion.value.id), {
            onSuccess: () => {
                isModalOpen.value = false;
                form.reset();
                imagePreview.value = null;
            },
            preserveScroll: true,
            forceFormData: true // WAJIB
        });
    } else {
        // CREATE: Gunakan POST biasa
        form.post(route('admin.questions.store'), {
            onSuccess: () => {
                if (createAnother) {
                    const lastType = form.type;
                    form.reset();
                    imagePreview.value = null;
                    
                    form.tryout_id = props.tryout.id;
                    form.type = lastType;
                    form.answer = 'A';
                    
                    nextTick(() => document.querySelector('.modal-content')?.scrollTo({ top: 0, behavior: 'smooth' }));
                } else {
                    isModalOpen.value = false;
                    form.reset();
                    imagePreview.value = null;
                }
            },
            preserveScroll: true,
            forceFormData: true // WAJIB
        });
    }
};

const deleteQuestion = (id) => {
    if(confirm('Yakin ingin menghapus soal ini? Gambar terkait juga akan dihapus.')) {
        router.delete(route('admin.questions.destroy', id), { preserveScroll: true });
    }
};
</script>

<template>
    <Head :title="'Kelola Soal: ' + tryout.name" />
    <AuthenticatedLayout>
        
        <div class="sticky top-0 z-30 bg-slate-50/95 backdrop-blur-md border-b border-slate-200 mb-6 py-4 shadow-sm">
            <div class="max-w-5xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <Link :href="route('admin.tryouts.index')" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors">
                        ←
                    </Link>
                    <div>
                        <h2 class="text-base font-black text-slate-900 uppercase italic tracking-tighter leading-none">{{ tryout.name }}</h2>
                        <div class="flex gap-2 mt-1">
                            <span class="text-[9px] font-bold bg-slate-200 text-slate-600 px-2 py-0.5 rounded">Total: {{ stats.total }}</span>
                            <span class="text-[9px] font-bold bg-red-100 text-red-600 px-2 py-0.5 rounded">TWK: {{ stats.twk }}</span>
                            <span class="text-[9px] font-bold bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded">TIU: {{ stats.tiu }}</span>
                            <span class="text-[9px] font-bold bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded">TKP: {{ stats.tkp }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 w-full md:w-auto">
                    <input v-model="searchQuery" type="text" placeholder="Cari soal..." class="flex-1 md:w-48 bg-white border border-slate-200 rounded-xl text-xs font-bold p-3 focus:ring-2 focus:ring-indigo-500">
                    <button @click="openModal()" class="bg-indigo-600 text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-200 whitespace-nowrap">
                        + Soal Baru
                    </button>
                </div>
            </div>
            
            <div class="max-w-5xl mx-auto px-4 mt-3 flex gap-2 overflow-x-auto no-scrollbar">
                <button v-for="type in ['ALL', 'TWK', 'TIU', 'TKP']" :key="type" 
                    @click="filterType = type" 
                    :class="filterType === type ? 'bg-slate-800 text-white' : 'bg-white text-slate-500 border-slate-200'"
                    class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest border transition-all">
                    {{ type === 'ALL' ? 'Semua' : type }}
                </button>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 pb-24 space-y-3">
            
            <div v-if="localQuestions.length === 0" class="text-center py-10 bg-white rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-400 font-bold text-xs">Belum ada soal. Klik tombol "+ Soal Baru" untuk memulai.</p>
            </div>

            <draggable 
                v-model="localQuestions" 
                item-key="id" 
                handle=".drag-handle" 
                @end="onDragEnd"
                class="space-y-3"
            >
                <template #item="{element: q, index}">
                    <div v-show="isVisible(q)" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 group">
                        
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors select-none">
                            <div class="flex items-center gap-4 overflow-hidden flex-1 cursor-pointer" @click="toggleExpand(q.id)">
                                <div class="drag-handle cursor-move text-slate-300 hover:text-slate-600 p-2 -ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/></svg>
                                </div>

                                <span class="text-xs font-black text-slate-400 w-6">{{ index + 1 }}.</span>

                                <div :class="{
                                    'bg-red-50 text-red-600 border-red-100': q.type === 'TWK',
                                    'bg-indigo-50 text-indigo-600 border-indigo-100': q.type === 'TIU',
                                    'bg-emerald-50 text-emerald-600 border-emerald-100': q.type === 'TKP'
                                }" class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-xl text-[10px] font-black border">
                                    {{ q.type }}
                                </div>

                                <div class="flex-1 min-w-0 flex items-center gap-3">
                                    
                                    <div v-if="q.image" class="relative group/img flex-shrink-0">
                                        <img 
                                            :src="getImageUrl(q.image)" 
                                            class="w-10 h-10 rounded-lg object-cover border border-slate-200 bg-slate-50"
                                            @error="handleImageError"
                                            alt="Soal"
                                        >
                                    </div>

                                    <div class="overflow-hidden">
                                        <p class="text-xs font-bold text-slate-800 truncate pr-4">{{ q.content }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span v-if="q.type !== 'TKP'" class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                                                Kunci: <span class="text-indigo-600">{{ q.answer }}</span>
                                            </span>
                                            <span v-else class="text-[9px] font-bold text-emerald-500 uppercase tracking-wider">
                                                Bobot Poin Aktif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 pl-2" @click="toggleExpand(q.id)">
                                <span class="text-slate-300 text-xs transition-transform duration-300 cursor-pointer" :class="{'rotate-180': expandedQuestions.includes(q.id)}">
                                    ▼
                                </span>
                            </div>
                        </div>

                        <div v-if="expandedQuestions.includes(q.id)" class="border-t border-slate-100 bg-slate-50/50 p-6 animate-in slide-in-from-top-2 fade-in duration-200">
                            
                            <div v-if="q.image" class="mb-6">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Lampiran Gambar:</p>
                                <img 
                                    :src="getImageUrl(q.image)" 
                                    class="max-w-full md:max-w-md rounded-2xl border border-slate-200 shadow-sm bg-white"
                                    @error="handleImageError"
                                    alt="Detail Gambar Soal"
                                >
                            </div>

                            <p class="text-sm text-slate-800 leading-relaxed mb-6 whitespace-pre-wrap font-medium">{{ q.content }}</p>

                            <div class="space-y-2 mb-6">
                                <div v-for="opt in ['a','b','c','d','e']" :key="opt" 
                                    :class="{'border-emerald-500 bg-emerald-50': q.type !== 'TKP' && q.answer === opt.toUpperCase()}"
                                    class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 bg-white text-xs">
                                    
                                    <span :class="{'bg-emerald-500 text-white': q.type !== 'TKP' && q.answer === opt.toUpperCase(), 'bg-slate-100 text-slate-500': q.type === 'TKP' || q.answer !== opt.toUpperCase()}" 
                                        class="w-6 h-6 flex-shrink-0 flex items-center justify-center rounded-lg text-[9px] font-black uppercase">
                                        {{ opt }}
                                    </span>
                                    
                                    <span class="flex-1 text-slate-700">{{ q['option_' + opt] }}</span>

                                    <span v-if="q.type === 'TKP'" class="bg-indigo-50 text-indigo-600 px-2 py-1 rounded text-[9px] font-black">
                                        {{ q['point_' + opt] }} Poin
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pt-4 border-t border-slate-200">
                                <div class="flex-1">
                                    <div v-if="q.explanation" class="bg-indigo-50 border border-indigo-100 rounded-xl p-3">
                                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">💡 Pembahasan</p>
                                        <p class="text-xs text-slate-600 leading-relaxed">{{ q.explanation }}</p>
                                    </div>
                                    <p v-else class="text-[10px] text-amber-500 font-bold italic">⚠️ Belum ada pembahasan</p>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="openModal(q)" class="bg-white border border-slate-200 hover:border-indigo-500 text-slate-600 hover:text-indigo-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                        Edit
                                    </button>
                                    <button @click="deleteQuestion(q.id)" class="bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </template>
            </draggable>

        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
            <div class="bg-white w-full max-w-3xl rounded-[2.5rem] p-8 max-h-[90vh] overflow-y-auto shadow-2xl relative modal-content">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-slate-900 uppercase italic">
                        {{ editingQuestion ? 'Edit Soal' : 'Buat Soal Baru' }}
                    </h3>
                    <button @click="isModalOpen = false" class="text-slate-400 hover:text-red-500 text-2xl">×</button>
                </div>
                
                <form @submit.prevent="submit(false)" class="space-y-5">
                    
                    <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Tipe Soal</label>
                            <select v-model="form.type" class="w-full bg-white border-slate-200 rounded-xl text-xs font-bold p-3 focus:ring-2 focus:ring-indigo-500">
                                <option value="TWK">TWK</option>
                                <option value="TIU">TIU</option>
                                <option value="TKP">TKP</option>
                            </select>
                        </div>
                        <div v-if="form.type !== 'TKP'" class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Kunci Jawaban</label>
                            <select v-model="form.answer" class="w-full bg-emerald-50 border-emerald-200 text-emerald-700 rounded-xl text-xs font-black p-3 focus:ring-2 focus:ring-emerald-500">
                                <option v-for="opt in ['A','B','C','D','E']" :key="opt" :value="opt">Jawaban Benar: {{ opt }}</option>
                            </select>
                        </div>
                         <div v-else class="flex items-center text-[10px] text-emerald-600 font-bold bg-emerald-50 px-4 rounded-xl">
                            ℹ️ Masukkan bobot nilai 1-5 untuk setiap opsi.
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Gambar Soal (Opsional)</label>
                        <div class="flex items-start gap-4">
                            <div v-if="imagePreview" class="relative group">
                                <img :src="imagePreview" class="w-24 h-24 object-cover rounded-xl border border-slate-200 bg-slate-50" @error="handleImageError">
                                <button @click="removeImage" type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-md hover:bg-red-600 transition-colors">×</button>
                            </div>

                            <div class="flex-1">
                                <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <p class="text-xs text-slate-500 font-bold">Klik untuk upload gambar</p>
                                        <p class="text-[10px] text-slate-400">PNG, JPG (Max 2MB)</p>
                                    </div>
                                    <input type="file" class="hidden" @change="handleImageUpload" accept="image/*" />
                                </label>
                                <p v-if="form.errors.image" class="text-red-500 text-xs mt-1 ml-2">{{ form.errors.image }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Pertanyaan</label>
                        <textarea v-model="form.content" rows="4" placeholder="Ketik isi soal disini..." class="w-full bg-slate-50 border-none rounded-2xl text-sm p-4 focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Pilihan Jawaban</label>
                        <div v-for="opt in ['a','b','c','d','e']" :key="opt" class="flex items-center gap-3">
                            <span :class="form.answer === opt.toUpperCase() && form.type !== 'TKP' ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500'" class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg text-[10px] font-black uppercase transition-colors">
                                {{ opt }}
                            </span>
                            
                            <input v-model="form['option_' + opt]" type="text" :placeholder="'Opsi ' + opt.toUpperCase()" class="flex-1 bg-slate-50 border-transparent rounded-xl text-xs p-3 focus:bg-white focus:border-indigo-500">
                            
                            <div v-if="form.type === 'TKP'" class="w-16 flex-shrink-0">
                                <input v-model="form['point_' + opt]" type="number" min="1" max="5" placeholder="Poin" class="w-full bg-emerald-50 border-none text-emerald-700 font-black text-center rounded-xl text-xs p-3 focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Pembahasan</label>
                        <textarea v-model="form.explanation" rows="3" placeholder="Penjelasan jawaban..." class="w-full bg-indigo-50/50 border-none rounded-2xl text-xs p-4 focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 pt-4 border-t border-slate-100">
                        
                        <button v-if="!editingQuestion" @click="submit(true)" type="button" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-200 transition-all flex items-center justify-center gap-2">
                            <span>↻</span> Simpan & Tambah Lagi
                        </button>

                        <button @click="submit(false)" type="button" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200 transition-all">
                            {{ editingQuestion ? 'Update Data' : 'Simpan & Tutup' }}
                        </button>
                        
                        <button @click="isModalOpen = false" type="button" class="px-6 bg-slate-100 text-slate-500 hover:bg-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>