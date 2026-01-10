<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk - CPNS Nusantara" />

    <div class="min-h-screen bg-slate-50 flex flex-col justify-center items-center p-6 font-sans">
        <Link href="/" class="mb-10 flex flex-col items-center gap-3 group">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-indigo-100 group-hover:bg-slate-900 transition-all">
                C
            </div>
            <h1 class="font-black uppercase tracking-tighter text-xl">CPNS Nusantara</h1>
        </Link>

        <div class="w-full max-w-[400px] bg-white p-8 lg:p-10 rounded-[2.5rem] shadow-sm border border-slate-200">
            <div class="mb-8">
                <h2 class="text-2xl font-black uppercase tracking-tighter text-slate-900">Selamat Datang</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Masuk untuk melanjutkan belajar</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Alamat Email</label>
                    <input v-model="form.email" type="email" required autofocus
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all"
                        placeholder="nama@email.com">
                    <p v-if="form.errors.email" class="text-[10px] font-black text-rose-500 uppercase mt-2 px-1">{{ form.errors.email }}</p>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Kata Sandi</label>
                        <Link v-if="route().has('password.request')" :href="route('password.request')" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">Lupa?</Link>
                    </div>
                    <input v-model="form.password" type="password" required
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all"
                        placeholder="••••••••">
                </div>

                <label class="flex items-center gap-3 px-1 cursor-pointer group">
                    <input type="checkbox" v-model="form.remember" class="w-5 h-5 rounded-lg border-slate-200 text-indigo-600 focus:ring-indigo-600 transition-all">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Ingat Saya</span>
                </label>

                <button type="submit" :disabled="form.processing"
                    class="w-full bg-slate-900 text-white py-5 rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl shadow-slate-100 mt-4 disabled:opacity-50">
                    Masuk Sekarang
                </button>
            </form>
        </div>

        <p class="mt-8 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            Belum punya akun? 
            <Link :href="route('register')" class="text-indigo-600 font-black ml-1 border-b-2 border-indigo-100 pb-0.5">Daftar Gratis</Link>
        </p>
    </div>
</template>