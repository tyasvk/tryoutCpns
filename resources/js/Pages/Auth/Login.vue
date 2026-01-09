<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ email: '', password: '', remember: false });
const submit = () => form.post(route('login'), { onFinish: () => form.reset('password') });
</script>

<template>
    <Head title="Masuk Akun" />
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 lg:p-8">
        <div class="w-full max-w-5xl bg-white rounded-[3rem] shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 border border-slate-100">
            <div class="bg-slate-900 p-12 lg:p-16 text-white relative overflow-hidden flex flex-col justify-between min-h-[300px] lg:min-h-full">
                <div class="relative z-10">
                    <Link href="/" class="text-xl lg:text-2xl font-black italic tracking-tighter uppercase mb-10 block">CPNS Nusantara<span class="text-indigo-400">.</span></Link>
                    <h2 class="text-4xl lg:text-5xl font-black italic tracking-tighter leading-none mb-4">Gerbang Karir ASN Anda.</h2>
                    <p class="text-slate-400 text-sm lg:text-base font-medium max-w-sm italic">Masuk dan lanjutkan perjuanganmu membangun negeri bersama CPNS Nusantara.</p>
                </div>
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-600/30 rounded-full blur-[100px]"></div>
            </div>

            <div class="p-10 lg:p-16 flex flex-col justify-center">
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter mb-8">Akses Akun</h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Email Address</label>
                        <input type="email" v-model="form.email" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold focus:ring-2 focus:ring-indigo-500" placeholder="nama@email.com" />
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Password</label>
                        <input type="password" v-model="form.password" class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold focus:ring-2 focus:ring-indigo-500" placeholder="••••••••" />
                    </div>
                    <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl hover:bg-indigo-600 transition-all active:scale-95">Masuk Sekarang</button>
                    <p class="text-center text-sm font-bold text-slate-500 mt-6">Belum punya akun? <Link :href="route('register')" class="text-indigo-600 font-black uppercase hover:underline">Daftar Gratis</Link></p>
                </form>
            </div>
        </div>
    </div>
</template>