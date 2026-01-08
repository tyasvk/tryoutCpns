<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ 
    tryouts: Array 
});

const isModalOpen = ref(false);
const isEdit = ref(false);
const editingId = ref(null);

const form = useForm({
    name: '',
    duration: 100,
    is_free: true,
    price: 0
});

const openModal = (item = null) => {
    isEdit.value = !!item;
    if (item) {
        editingId.value = item.id;
        form.name = item.name;
        form.duration = item.duration;
        form.is_free = !!item.is_free;
        form.price = item.price;
    } else {
        form.reset();
    }
    isModalOpen.value = true;
};

const submit = () => {
    if (isEdit.value) {
        form.patch(route('admin.tryouts.update', editingId.value), {
            onSuccess: () => { isModalOpen.value = false; form.reset(); }
        });
    } else {
        form.post(route('admin.tryouts.store'), {
            onSuccess: () => { isModalOpen.value = false; form.reset(); }
        });
    }
};

const deleteTryout = (id) => {
    if (confirm('Hapus paket tryout ini? Semua soal di dalamnya juga akan terhapus.')) {
        router.delete(route('admin.tryouts.destroy', id));
    }
};
</script>

<template>
    <Head title="Kelola Paket Tryout" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center max-w-5xl mx-auto w-full px-4">
                <div>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-1">Content Manager</p>
                    <h2 class="text-xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">Manajemen Tryout</h2>
                </div>
                <button @click="openModal()" class="bg-slate-950 text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-indigo-600 transition-all active:scale-95">
                    + Paket Baru
                </button>
            </div>
        </template>

        <div class="max-w-5xl mx-auto py-6 px-4 space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="item in tryouts" :key="item.id" class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all group flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span :class="item.is_free ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100'" class="text-[9px] font-black px-3 py-1 rounded-full border uppercase tracking-widest">
                                {{ item.is_free ? 'Gratis' : 'Premium' }}
                            </span>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openModal(item)" class="p-2 bg-slate-50 text-slate-400 hover:text-indigo-600 rounded-lg transition-colors text-xs">✎</button>
                                <button @click="deleteTryout(item.id)" class="p-2 bg-slate-50 text-slate-400 hover:text-red-500 rounded-lg transition-colors text-xs">✕</button>
                            </div>
                        </div>

                        <h3 class="text-lg font-black text-slate-900 tracking-tighter uppercase italic leading-tight mb-1">{{ item.name }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-6">
                            {{ item.duration }} Menit • {{ !item.is_free ? 'Rp ' + item.price.toLocaleString() : 'Akses Publik' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Jumlah Soal</span>
                            <span class="text-sm font-black text-slate-900">{{ item.questions_count ?? 0 }} Butir</span>
                        </div>
                        <Link :href="route('admin.tryouts.questions', item.id)" class="bg-indigo-50 text-indigo-600 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                            Kelola Soal ➔
                        </Link>
                    </div>
                </div>
            </div>

            <div v-if="tryouts.length === 0" class="py-20 text-center bg-slate-50 border-2 border-dashed border-slate-100 rounded-[3rem]">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] italic">Belum ada paket tryout tersedia</p>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm px-6">
            <div class="bg-white w-full max-w-md rounded-[3rem] p-8 shadow-2xl transform transition-all">
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">{{ isEdit ? 'Update' : 'Buat' }} Paket<span class="text-indigo-600">.</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Konfigurasi data simulasi ujian</p>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Nama Paket</label>
                        <input v-model="form.name" type="text" placeholder="Contoh: Tryout Akbar Nasional #1" class="w-full bg-slate-50 border-none rounded-2xl text-xs font-bold p-4 focus:ring-2 focus:ring-indigo-500 transition-all" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Durasi (Menit)</label>
                            <input v-model="form.duration" type="number" class="w-full bg-slate-50 border-none rounded-2xl text-xs font-bold p-4 focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Tipe Akses</label>
                            <select v-model="form.is_free" class="w-full bg-slate-50 border-none rounded-2xl text-xs font-bold p-4 focus:ring-2 focus:ring-indigo-500">
                                <option :value="true">Gratis</option>
                                <option :value="false">Premium</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="!form.is_free" class="space-y-1 animate-in fade-in slide-in-from-top-2 duration-300">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Harga (Rupiah)</label>
                        <input v-model="form.price" type="number" placeholder="Rp 0" class="w-full bg-slate-50 border-none rounded-2xl text-xs font-bold p-4 focus:ring-2 focus:ring-indigo-500" />
                    </div>

                    <div class="flex gap-2 pt-6">
                        <button type="submit" :disabled="form.processing" class="flex-1 bg-slate-950 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-indigo-600 transition-all active:scale-95 disabled:opacity-50">
                            {{ isEdit ? 'Simpan Perubahan' : 'Publish Paket' }}
                        </button>
                        <button @click="isModalOpen = false" type="button" class="px-6 py-4 bg-slate-100 text-slate-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Transisi halus untuk modal */
.animate-in {
    animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>