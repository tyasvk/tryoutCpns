<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({ password: '' });
const submit = () => form.post(route('password.confirm'), { onFinish: () => form.reset() });
</script>

<template>
    <Head title="Konfirmasi Keamanan" />
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-[4rem] shadow-2xl p-12 text-center border border-slate-100">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">🔒</div>
            <h3 class="text-xl font-black italic uppercase tracking-tighter mb-2">Area Terproteksi</h3>
            <p class="text-slate-400 text-xs font-medium mb-8 uppercase tracking-wider">Silakan konfirmasi password Anda untuk melanjutkan.</p>
            
            <form @submit.prevent="submit" class="space-y-6">
                <input type="password" v-model="form.password" required autofocus class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold text-center focus:ring-2 focus:ring-rose-500 transition-all" placeholder="••••••••" />
                <p v-if="form.errors.password" class="text-rose-500 text-[10px] font-bold mt-1">{{ form.errors.password }}</p>
                <button type="submit" :disabled="form.processing" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl hover:bg-rose-600 transition-all">
                    Konfirmasi
                </button>
            </form>
        </div>
    </div>
</template>