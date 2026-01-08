<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

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
    <Head title="Daftar Akun" />

    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-8 px-4 sm:px-6">
        <div class="w-full max-w-[440px] mx-auto">
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center gap-2 mb-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-100">
                        <span class="text-white font-black text-2xl">T</span>
                    </div>
                </Link>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Akun</h2>
                <p class="text-slate-500 mt-2 font-medium">Gratis akses ribuan soal latihan</p>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="name" value="Nama Lengkap" class="text-xs uppercase tracking-widest font-bold text-slate-400 ml-1" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-2 block w-full !bg-slate-50 !border-transparent !rounded-2xl focus:!bg-white focus:!ring-4 focus:!ring-indigo-100 transition-all py-4 px-5"
                            placeholder="John Doe"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Alamat Email" class="text-xs uppercase tracking-widest font-bold text-slate-400 ml-1" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-2 block w-full !bg-slate-50 !border-transparent !rounded-2xl focus:!bg-white focus:!ring-4 focus:!ring-indigo-100 transition-all py-4 px-5"
                            placeholder="name@email.com"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <InputLabel for="password" value="Password" class="text-xs uppercase tracking-widest font-bold text-slate-400 ml-1" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="mt-2 block w-full !bg-slate-50 !border-transparent !rounded-2xl focus:!bg-white focus:!ring-4 focus:!ring-indigo-100 transition-all py-4 px-5"
                                placeholder="••••••••"
                                required
                            />
                        </div>
                        <div>
                            <InputLabel for="password_confirmation" value="Ulangi Password" class="text-xs uppercase tracking-widest font-bold text-slate-400 ml-1" />
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="mt-2 block w-full !bg-slate-50 !border-transparent !rounded-2xl focus:!bg-white focus:!ring-4 focus:!ring-indigo-100 transition-all py-4 px-5"
                                placeholder="••••••••"
                                required
                            />
                        </div>
                    </div>

                    <div class="pt-4">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-100 hover:bg-indigo-700 active:scale-[0.98] transition-all disabled:opacity-50"
                        >
                            Buat Akun Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-slate-500 text-sm">
                        Sudah punya akun? 
                        <Link :href="route('login')" class="text-indigo-600 font-bold hover:underline">Masuk</Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>