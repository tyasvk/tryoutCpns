<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    type: 'free',
    duration: 110,
    description: '',
    price: 0,
    start_at: '',
    end_at: '',
    is_published: false,
});

const submit = () => {
    form.post(route('admin.tryouts.store'));
};
</script>

<template>
    <Head title="Buat Paket Tryout - CPNS Nusantara" />

    <AuthenticatedLayout>
        <div class="max-w-5xl mx-auto py-12 px-4">
            
            <div class="mb-10 flex justify-between items-end animate-in fade-in duration-700">
                <div>
                    <Link :href="route('admin.tryouts.index')" class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] hover:text-indigo-600 transition-all">
                        ← Kembali ke Daftar
                    </Link>
                    <h1 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter mt-2 leading-none">Paket Baru</h1>
                </div>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="bg-white rounded-[2.5rem] p-8 lg:p-10 shadow-sm border border-slate-200">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 block">Informasi Umum</label>
                        
                        <div class="space-y-6">
                            <div>
                                <input v-model="form.name" type="text" placeholder="Nama Paket Tryout (Contoh: TO Akbar Nasional #1)" 
                                    class="w-full bg-slate-50 border-none rounded-2xl p-5 font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50 placeholder:text-slate-300" />
                                <div v-if="form.errors.name" class="text-rose-500 text-[10px] font-bold mt-2 uppercase italic">{{ form.errors.name }}</div>
                            </div>

                            <textarea v-model="form.description" rows="4" 
                                class="w-full bg-slate-50 border-none rounded-2xl p-6 font-medium text-slate-600 focus:ring-4 focus:ring-indigo-50 placeholder:text-slate-300 text-sm" 
                                placeholder="Deskripsi paket (Opsional)"></textarea>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 lg:p-10 shadow-sm border border-slate-200">
                        <div class="flex justify-between items-center mb-8">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tipe & Komersial</label>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-8">
                            <button v-for="t in [ {id:'free', label:'Gratis'}, {id:'paid', label:'Berbayar'}, {id:'grand_to', label:'TO Akbar'} ]" 
                                :key="t.id" type="button" @click="form.type = t.id"
                                :class="form.type === t.id ? 'bg-slate-900 text-white shadow-xl scale-105' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                                class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                                {{ t.label }}
                            </button>
                        </div>

                        <div v-if="form.type !== 'free'" class="animate-in slide-in-from-top duration-300 mb-6">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Harga Paket (IDR)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-slate-300 text-sm font-italic">Rp</span>
                                <input v-model="form.price" type="number" 
                                    class="w-full bg-indigo-50/50 border-none rounded-2xl pl-12 pr-5 py-5 font-black text-indigo-600 focus:ring-4 focus:ring-indigo-100" placeholder="0" />
                            </div>
                        </div>

                        <div v-if="form.type === 'grand_to'" class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-in slide-in-from-top duration-500">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Jadwal Mulai</label>
                                <input v-model="form.start_at" type="datetime-local" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 text-xs" />
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Jadwal Selesai</label>
                                <input v-model="form.end_at" type="datetime-local" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 text-xs" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm sticky top-10">
                        <div class="mb-8">
                            <label class="text-[10px] font-black text-slate-400 uppercase mb-4 block tracking-widest">Durasi Ujian</label>
                            <div class="relative">
                                <input v-model="form.duration" type="number" class="w-full bg-slate-50 border-none rounded-2xl p-5 font-black text-slate-800 pr-16" />
                                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase italic">Menit</span>
                            </div>
                        </div>

                        <div class="mb-10 flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                            <span class="text-[10px] font-black text-slate-500 uppercase">Langsung Terbitkan?</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.is_published" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <button type="submit" :disabled="form.processing" 
                            class="w-full bg-slate-900 text-white py-6 rounded-[2rem] text-[10px] font-black uppercase tracking-[0.3em] shadow-2xl hover:bg-indigo-600 transition-all active:scale-95 disabled:opacity-50">
                            {{ form.processing ? 'Memproses...' : '🚀 Buat Paket' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>