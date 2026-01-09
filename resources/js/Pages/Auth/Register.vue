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
    <Head title="Daftar Akun Baru" />

    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 lg:p-8">
        <div class="w-full max-w-5xl bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 overflow-hidden grid grid-cols-1 lg:grid-cols-2 border border-slate-100">
            
            <div class="bg-indigo-600 p-10 lg:p-16 text-white relative overflow-hidden flex flex-col justify-between min-h-[300px] lg:min-h-full order-last lg:order-first">
                <div class="relative z-10">
                    <Link href="/" class="text-2xl font-black italic tracking-tighter uppercase mb-10 block w-fit">
                        CAT Premium<span class="text-indigo-300">.</span>
                    </Link>
                    <h2 class="text-4xl lg:text-5xl font-black italic tracking-tighter leading-none mb-4">
                        Mulai Perjalanan ASN Anda.
                    </h2>
                    <p class="text-indigo-200 text-sm lg:text-base font-medium max-w-sm leading-relaxed">
                        Bergabunglah dengan ribuan peserta lain yang menggunakan platform premium kami untuk menaklukkan passing grade.
                    </p>
                </div>
                
                <div class="relative z-10 mt-10 lg:mt-0 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">✅</div>
                        <p class="text-xs font-black uppercase tracking-wider">Akses Tryout Gratis</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">📊</div>
                        <p class="text-xs font-black uppercase tracking-wider">Analisis Radar & Ranking</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">💡</div>
                        <p class="text-xs font-black uppercase tracking-wider">Pembahasan HOTS Lengkap</p>
                    </div>
                </div>

                <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-[100px]"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-900/30 rounded-full blur-[100px]"></div>
            </div>

            <div class="p-10 lg:p-16 flex flex-col justify-center bg-white">
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter mb-8 text-center lg:text-left">
                    Buat Akun Baru
                </h3>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Nama Lengkap</label>
                        <input 
                            id="name" 
                            type="text" 
                            v-model="form.name" 
                            required 
                            autofocus
                            class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-300"
                            placeholder="Masukkan nama Anda"
                        />
                        <p v-if="form.errors.name" class="text-rose-500 text-[10px] font-bold mt-2 ml-3">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Email Address</label>
                        <input 
                            id="email" 
                            type="email" 
                            v-model="form.email" 
                            required 
                            class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-300"
                            placeholder="nama@email.com"
                        />
                        <p v-if="form.errors.email" class="text-rose-500 text-[10px] font-bold mt-2 ml-3">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Password</label>
                        <input 
                            id="password" 
                            type="password" 
                            v-model="form.password" 
                            required 
                            class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-300"
                            placeholder="Minimal 8 karakter"
                        />
                        <p v-if="form.errors.password" class="text-rose-500 text-[10px] font-bold mt-2 ml-3">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-2 block">Konfirmasi Password</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            v-model="form.password_confirmation" 
                            required 
                            class="w-full bg-slate-50 border-none rounded-3xl p-5 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-300"
                            placeholder="Ulangi password"
                        />
                        <p v-if="form.errors.password_confirmation" class="text-rose-500 text-[10px] font-bold mt-2 ml-3">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <div class="pt-4">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full bg-indigo-600 text-white py-5 rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition-all active:scale-95 disabled:opacity-70"
                        >
                            {{ form.processing ? 'Mendaftarkan...' : 'Mulai Sekarang' }}
                        </button>
                    </div>

                    <div class="text-center mt-8">
                        <p class="text-sm font-bold text-slate-500">
                            Sudah punya akun? 
                            <Link :href="route('login')" class="text-indigo-600 font-black uppercase tracking-wider hover:underline ml-1">
                                Masuk disini
                            </Link>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>