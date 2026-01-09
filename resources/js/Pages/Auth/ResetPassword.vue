<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({ email: String, token: String });

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => form.post(route('password.store'), { onFinish: () => form.reset('password', 'password_confirmation') });
</script>

<template>
    <Head title="Reset Password" />
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 lg:p-8">
        <div class="w-full max-w-5xl bg-white rounded-[3rem] shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 border border-slate-100">
            <div class="bg-slate-900 p-10 lg:p-16 text-white relative overflow-hidden flex flex-col justify-between min-h-[250px]">
                <div class="relative z-10">
                    <h2 class="text-4xl font-black italic tracking-tighter leading-none mb-4 text-indigo-400">Keamanan Utama.</h2>
                    <p class="text-slate-400 text-sm font-medium">Buat password baru yang kuat untuk melindungi akun dan progres tryout Anda.</p>
                </div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-[100px]"></div>
            </div>

            <div class="p-10 lg:p-16">
                <form @submit.prevent="submit" class="space-y-4">
                    <input type="hidden" v-model="form.email" />
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Password Baru</label>
                        <input type="password" v-model="form.password" required class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold focus:ring-2 focus:ring-indigo-500" placeholder="••••••••" />
                        <p v-if="form.errors.password" class="text-rose-500 text-[10px] font-bold mt-1 ml-3">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Konfirmasi Password</label>
                        <input type="password" v-model="form.password_confirmation" required class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold focus:ring-2 focus:ring-indigo-500" placeholder="••••••••" />
                    </div>
                    <button type="submit" :disabled="form.processing" class="w-full bg-indigo-600 text-white py-5 rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl hover:bg-indigo-700 mt-4 transition-all">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>