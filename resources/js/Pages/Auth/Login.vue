<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
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
    <Head title="Masuk ke Akun" />

    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-8 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -top-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-100/50 blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 w-[30%] h-[30%] rounded-full bg-blue-100/50 blur-[100px]"></div>

        <div class="w-full max-w-[440px] mx-auto relative z-10">
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center gap-2 mb-4 group">
                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-100 group-hover:rotate-6 transition-transform">
                        <span class="text-white font-black text-2xl">T</span>
                    </div>
                </Link>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h2>
                <p class="text-slate-500 mt-2 font-medium">Masuk untuk melanjutkan latihan soal</p>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-2xl border border-green-100">
                {{ status }}
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="email" value="Email" class="text-xs uppercase tracking-widest font-bold text-slate-400 ml-1" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-2 block w-full !bg-slate-50 !border-transparent !rounded-2xl focus:!bg-white focus:!ring-4 focus:!ring-indigo-100 transition-all py-4 px-5"
                            placeholder="nama@email.com"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center ml-1">
                            <InputLabel for="password" value="Password" class="text-xs uppercase tracking-widest font-bold text-slate-400" />
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-500"
                            >
                                Lupa?
                            </Link>
                        </div>
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-2 block w-full !bg-slate-50 !border-transparent !rounded-2xl focus:!bg-white focus:!ring-4 focus:!ring-indigo-100 transition-all py-4 px-5"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center ml-1">
                        <label class="flex items-center cursor-pointer group">
                            <Checkbox name="remember" v-model:checked="form.remember" class="!rounded-md border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                            <span class="ml-2 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-100 hover:bg-indigo-700 active:scale-[0.98] transition-all disabled:opacity-50"
                        >
                            Masuk Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-10 text-center border-t border-slate-100 pt-8">
                    <p class="text-slate-500 text-sm">
                        Belum punya akun? 
                        <Link :href="route('register')" class="text-indigo-600 font-bold hover:underline ml-1">Daftar Akun Gratis</Link>
                    </p>
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-slate-400 text-xs relative z-10 font-medium">
            &copy; 2026 TryoutCPNS System. Secured by Laravel.
        </p>
    </div>
</template>

<style scoped>
/* Menghilangkan ring biru default browser agar konsisten dengan fokus indigo kita */
input:focus {
    outline: none;
}
</style>