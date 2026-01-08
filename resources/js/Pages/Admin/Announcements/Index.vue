<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({ announcements: Array });

const isModalOpen = ref(false);
const isEdit = ref(false);
const editingId = ref(null);

const form = useForm({
    title: '',
    content: '',
    is_active: true
});

const openModal = (item = null) => {
    isEdit.value = !!item;
    if (item) {
        editingId.value = item.id;
        form.title = item.title;
        form.content = item.content;
        form.is_active = !!item.is_active;
    } else {
        form.reset();
    }
    isModalOpen.value = true;
};

const submit = () => {
    if (isEdit.value) {
        form.patch(route('admin.announcements.update', editingId.value), {
            onSuccess: () => { isModalOpen.value = false; form.reset(); }
        });
    } else {
        form.post(route('admin.announcements.store'), {
            onSuccess: () => { isModalOpen.value = false; form.reset(); }
        });
    }
};
</script>

<template>
    <Head title="Kelola Pengumuman" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center max-w-4xl mx-auto w-full px-4">
                <h2 class="text-lg font-black uppercase italic text-slate-800">Manajemen Pengumuman</h2>
                <button @click="openModal()" class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-xs font-bold shadow-lg shadow-indigo-100">+ Info Baru</button>
            </div>
        </template>

        <div class="max-w-4xl mx-auto py-8 px-4 space-y-4">
            <div v-for="ann in announcements" :key="ann.id" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group transition-all hover:border-indigo-100">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-2">
                        <span :class="ann.is_active ? 'bg-emerald-500' : 'bg-slate-300'" class="w-2 h-2 rounded-full"></span>
                        <h4 class="font-bold text-slate-800 text-sm">{{ ann.title }}</h4>
                    </div>
                    <div class="flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="openModal(ann)" class="text-[10px] font-black uppercase text-indigo-600">Edit</button>
                        <button @click="router.delete(route('admin.announcements.destroy', ann.id))" class="text-[10px] font-black uppercase text-red-400">Hapus</button>
                    </div>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">{{ ann.content }}</p>
                <p class="text-[9px] text-slate-300 mt-4 font-bold uppercase tracking-widest">{{ new Date(ann.created_at).toLocaleDateString() }}</p>
            </div>

            <div v-if="announcements.length === 0" class="text-center py-20 bg-slate-50 rounded-[2.5rem] text-slate-400 text-xs font-black uppercase italic">
                Belum ada pengumuman.
            </div>

            <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
                <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl">
                    <h3 class="font-black text-slate-800 mb-6 uppercase italic">{{ isEdit ? 'Update' : 'Buat' }} Pengumuman</h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        <input v-model="form.title" type="text" placeholder="Judul Pengumuman" class="w-full border-slate-200 rounded-2xl text-sm p-4" />
                        <textarea v-model="form.content" rows="4" placeholder="Isi Pengumuman..." class="w-full border-slate-200 rounded-2xl text-sm p-4"></textarea>
                        <div class="flex items-center gap-3 px-2">
                            <input type="checkbox" v-model="form.is_active" id="active" class="rounded text-indigo-600" />
                            <label for="active" class="text-xs font-bold text-slate-500 uppercase">Tampilkan ke Peserta</label>
                        </div>
                        <div class="flex gap-2 pt-4">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-100">Simpan</button>
                            <button @click="isModalOpen = false" type="button" class="px-6 py-4 bg-slate-50 text-slate-400 rounded-2xl font-black text-xs uppercase tracking-widest">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>