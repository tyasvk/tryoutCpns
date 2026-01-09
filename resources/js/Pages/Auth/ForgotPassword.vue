<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ status: { type: String } });

const form = useForm({ email: '' });
const submit = () => form.post(route('password.email'));
</script>

<template>
    <Head title="Pulihkan Akun" />
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 lg:p-8">
        <div class="w-full max-w-5xl bg-white rounded-[3rem] shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 border border-slate-100">
            <div class="bg-indigo-600 p-10 lg:p-16 text-white relative overflow-hidden flex flex-col justify-between min-h-[250px] lg:min-h-full">
                <div class="relative z-10">
                    <Link href="/" class="text-xl font-black italic tracking-tighter uppercase mb-8 block">CAT Premium.</Link>
                    <h2 class="text-4xl font-black italic tracking-tighter leading-none mb-4">Lupa Password?</h2>
                    <p class="text-indigo-100 text-sm font-medium leading-relaxed">Jangan khawatir. Masukkan email Anda, kami akan mengirimkan link untuk mengatur ulang password Anda.</p>
                </div>
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-[80px]"></div>
            </div>

            <div class="p-10 lg:p-16 flex flex-col justify-center">
                <div v-if="status" class="mb-6 font-bold text-sm text-emerald-600 bg-emerald-50 p-4 rounded-2xl text-center">{{ status }}</div>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Email Terdaftar</label>
                        <input type="email" v-model="form.email" required class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="nama@email.com" />
                        <p v-if="form.errors.email" class="text-rose-500 text-[10px] font-bold mt-2 ml-3">{{ form.errors.email }}</p>
                    </div>
                    <button type="submit" :disabled="form.processing" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl hover:bg-indigo-600 transition-all">
                        Kirim Link Reset
                    </button>
                    <Link :href="route('login')" class="block text-center text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600">Kembali ke Login</Link>
                </form>
            </div>
        </div>
    </div>
</template>