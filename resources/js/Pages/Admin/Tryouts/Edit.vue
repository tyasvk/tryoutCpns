<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    tryout: Object
});

// Inisialisasi form dengan data yang sudah ada
const form = useForm({
    name: props.tryout.name,
    description: props.tryout.description || '',
    duration: props.tryout.duration,
    type: props.tryout.type || 'free',
    is_published: props.tryout.is_published ? true : false,
});

const submit = () => {
    // Menggunakan patch atau put untuk update
    form.put(route('admin.tryouts.update', props.tryout.id));
};
</script>

<template>
    <Head title="Edit Paket Tryout" />
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <Link :href="route('admin.tryouts.index')" class="text-[10px] font-black uppercase text-slate-400 hover:text-indigo-600 tracking-widest transition-all">
                        ← Kembali ke Inventori
                    </Link>
                    <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter mt-2">Edit Package</h2>
                </div>
                <div :class="form.is_published ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'" 
                     class="px-4 py-2 rounded-xl text-[10px] font-black uppercase border border-current">
                    Status: {{ form.is_published ? 'Published' : 'Draft' }}
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm space-y-10">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Nama Paket</label>
                            <input v-model="form.name" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-indigo-500" />
                            <p v-if="form.errors.name" class="text-red-500 text-[10px] mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Durasi (Menit)</label>
                            <input v-model="form.duration" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-center focus:ring-2 focus:ring-indigo-500" />
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-4 block">Kategori Akses</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button type="button" @click="form.type = 'free'" :class="form.type === 'free' ? 'border-indigo-600 bg-indigo-50 ring-4 ring-indigo-100' : 'border-slate-100 bg-slate-50'" class="p-6 rounded-[2rem] border-2 text-left transition-all group">
                                <span class="text-2xl mb-2 block group-hover:scale-110 transition-transform">🎁</span>
                                <p class="font-black text-[11px] uppercase text-indigo-600">Gratis</p>
                                <p class="text-[9px] text-slate-400 mt-1 italic">Dapat diakses semua user.</p>
                            </button>

                            <button type="button" @click="form.type = 'paid'" :class="form.type === 'paid' ? 'border-amber-500 bg-amber-50 ring-4 ring-amber-100' : 'border-slate-100 bg-slate-50'" class="p-6 rounded-[2rem] border-2 text-left transition-all group">
                                <span class="text-2xl mb-2 block group-hover:scale-110 transition-transform">💎</span>
                                <p class="font-black text-[11px] uppercase text-amber-600">Berbayar</p>
                                <p class="text-[9px] text-slate-400 mt-1 italic">Hanya untuk user premium.</p>
                            </button>

                            <button type="button" @click="form.type = 'grand'" :class="form.type === 'grand' ? 'border-rose-500 bg-rose-50 ring-4 ring-rose-100' : 'border-slate-100 bg-slate-50'" class="p-6 rounded-[2rem] border-2 text-left transition-all group">
                                <span class="text-2xl mb-2 block group-hover:scale-110 transition-transform">🔥</span>
                                <p class="font-black text-[11px] uppercase text-rose-600">TO Akbar</p>
                                <p class="text-[9px] text-slate-400 mt-1 italic">Event nasional terjadwal.</p>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Deskripsi Paket</label>
                        <textarea v-model="form.description" rows="4" class="w-full bg-slate-50 border-none rounded-3xl p-6 focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
                    </div>

                    <div class="flex items-center justify-between p-6 bg-slate-900 rounded-[2.5rem] text-white">
                        <div class="flex items-center gap-4">
                            <div :class="form.is_published ? 'bg-emerald-500' : 'bg-slate-700'" class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors">
                                <span>{{ form.is_published ? '🚀' : '📁' }}</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-black uppercase tracking-widest italic">Visibilitas Paket</p>
                                <p class="text-[9px] text-slate-400">Tentukan apakah paket tampil di dashboard peserta.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" v-model="form.is_published" class="sr-only peer">
                            <div class="w-14 h-7 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" :disabled="form.processing" class="flex-1 bg-indigo-600 text-white py-6 rounded-[2.5rem] text-xs font-black uppercase tracking-widest shadow-2xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
                        {{ form.processing ? 'Updating...' : '💾 Simpan Perubahan' }}
                    </button>
                    <Link :href="route('admin.tryouts.index')" class="px-10 bg-white border border-slate-200 text-slate-400 py-6 rounded-[2.5rem] text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Batal
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>