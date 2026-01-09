<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    duration: 110, // Fitur: Default 110 Menit
    type: 'free',     // Fitur: Pilihan Tipe
    is_published: false,
});

const submit = () => {
    form.post(route('admin.tryouts.store'));
};
</script>

<template>
    <Head title="Create New Package" />
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto px-4">
            <div class="mb-8">
                <Link :href="route('admin.tryouts.index')" class="text-[10px] font-black uppercase text-slate-400 hover:text-indigo-600 tracking-widest transition-all">← Back to inventory</Link>
                <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter mt-2">New Package</h2>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm space-y-10">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Package Identity</label>
                            <input v-model="form.name" type="text" placeholder="e.g SKD Premium Batch #1" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Duration (Mins)</label>
                            <input v-model="form.duration" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-black text-center focus:ring-2 focus:ring-indigo-500" />
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-4 block">Access Category</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button type="button" @click="form.type = 'free'" :class="form.type === 'free' ? 'border-indigo-600 bg-indigo-50 ring-4 ring-indigo-100' : 'border-slate-100 bg-slate-50 opacity-60'" class="p-6 rounded-[2rem] border-2 text-left transition-all">
                                <span class="text-2xl mb-2 block">🎁</span>
                                <p class="font-black text-[11px] uppercase text-indigo-600 tracking-widest">Gratis</p>
                                <p class="text-[9px] text-slate-500 mt-1 font-bold italic leading-tight">Public access for everyone.</p>
                            </button>

                            <button type="button" @click="form.type = 'paid'" :class="form.type === 'paid' ? 'border-amber-500 bg-amber-50 ring-4 ring-amber-100' : 'border-slate-100 bg-slate-50 opacity-60'" class="p-6 rounded-[2rem] border-2 text-left transition-all">
                                <span class="text-2xl mb-2 block">💎</span>
                                <p class="font-black text-[11px] uppercase text-amber-600 tracking-widest">Berbayar</p>
                                <p class="text-[9px] text-slate-500 mt-1 font-bold italic leading-tight">Premium users only.</p>
                            </button>

                            <button type="button" @click="form.type = 'grand'" :class="form.type === 'grand' ? 'border-rose-500 bg-rose-50 ring-4 ring-rose-100' : 'border-slate-100 bg-slate-50 opacity-60'" class="p-6 rounded-[2rem] border-2 text-left transition-all">
                                <span class="text-2xl mb-2 block">🔥</span>
                                <p class="font-black text-[11px] uppercase text-rose-600 tracking-widest">TO Akbar</p>
                                <p class="text-[9px] text-slate-500 mt-1 font-bold italic leading-tight">National event schedule.</p>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Notes / Description</label>
                        <textarea v-model="form.description" rows="4" class="w-full bg-slate-50 border-none rounded-3xl p-6 focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-slate-900 rounded-[2.5rem] text-white">
                        <input v-model="form.is_published" type="checkbox" class="w-8 h-8 rounded-xl text-indigo-500 border-none focus:ring-0 cursor-pointer" />
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-widest italic leading-none">Live Visibility</p>
                            <p class="text-[9px] text-slate-400 mt-1">Check to make this package visible for participants.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-6 rounded-[2.5rem] text-xs font-black uppercase tracking-widest shadow-2xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
                        🚀 Deploy Package
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>