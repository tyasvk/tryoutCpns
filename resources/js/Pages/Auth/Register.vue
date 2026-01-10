<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar - CPNS Nusantara" />

    <div class="min-h-screen bg-slate-50 flex flex-col justify-center items-center p-6 font-sans">
        <Link href="/" class="mb-10 flex flex-col items-center gap-3">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-indigo-100">
                C
            </div>
            <h1 class="font-black uppercase tracking-tighter text-xl text-slate-900">CPNS Nusantara</h1>
        </Link>

        <div class="w-full max-w-[450px] bg-white p-8 lg:p-10 rounded-[2.5rem] shadow-sm border border-slate-200">
            <div class="mb-8">
                <h2 class="text-2xl font-black uppercase tracking-tighter text-slate-900">Buat Akun</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Mulai perjalanan karir Anda hari ini</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Nama Lengkap</label>
                    <input v-model="form.name" type="text" required autofocus
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all"
                        placeholder="Contoh: Budi Santoso">
                    <p v-if="form.errors.name" class="text-[10px] font-black text-rose-500 uppercase mt-2 px-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Email</label>
                    <input v-model="form.email" type="email" required
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all"
                        placeholder="nama@email.com">
                    <p v-if="form.errors.email" class="text-[10px] font-black text-rose-500 uppercase mt-2 px-1">{{ form.errors.email }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Sandi</label>
                        <input v-model="form.password" type="password" required
                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all"
                            placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Konfirmasi</label>
                        <input v-model="form.password_confirmation" type="password" required
                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full bg-slate-900 text-white py-5 rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl shadow-slate-100 mt-6 disabled:opacity-50">
                    Daftar Sekarang
                </button>
            </form>
        </div>

        <p class="mt-8 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            Sudah punya akun? 
            <Link :href="route('login')" class="text-indigo-600 font-black ml-1 border-b-2 border-indigo-100 pb-0.5">Masuk Disini</Link>
        </p>
    </div>
</template>