<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const user = usePage().props.auth.user;

const form = useForm({
    _method: 'patch',
    name: user.name,
    email: user.email,
    target_agency: user.target_agency || '',
    avatar: null,
});

const photoPreview = ref(user.avatar ? `/storage/${user.avatar}` : null);

const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.avatar = file;
        const reader = new FileReader();
        reader.onload = (e) => photoPreview.value = e.target.result;
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => alert('Profil Berhasil Diperbarui!'),
    });
};
</script>

<template>
    <Head title="Profil Saya - CPNS Nusantara" />

    <AuthenticatedLayout>
        <template #header>Pengaturan Profil</template>

        <div class="max-w-4xl mx-auto space-y-6 pb-24">
            
            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm text-center">
                    <div class="relative inline-block mb-6">
                        <div class="w-32 h-32 rounded-[2.5rem] bg-slate-100 overflow-hidden border-4 border-white shadow-xl">
                            <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-4xl text-slate-300 font-black">
                                {{ user.name.charAt(0) }}
                            </div>
                        </div>
                        <label class="absolute -bottom-2 -right-2 bg-indigo-600 w-10 h-10 rounded-xl flex items-center justify-center text-white cursor-pointer shadow-lg hover:bg-slate-900 transition-all">
                            <input type="file" class="hidden" @change="handlePhotoChange" accept="image/*" />
                            <span>📸</span>
                        </label>
                    </div>
                    <h3 class="text-sm font-black uppercase tracking-tight text-slate-900">{{ user.name }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID Peserta: #{{ user.id }}</p>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6">
                    <div class="bg-indigo-50 p-6 rounded-3xl border border-indigo-100">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600 mb-3">Instansi Impian (Target)</label>
                        <input v-model="form.target_agency" type="text" 
                            class="w-full bg-white border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 shadow-sm"
                            placeholder="Contoh: Kemenkumham, Pemprov DKI, dll.">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Nama Lengkap</label>
                            <input v-model="form.name" type="text" required
                                class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Alamat Email</label>
                            <input v-model="form.email" type="email" required
                                class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all">
                        </div>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full bg-slate-900 text-white py-5 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.3em] shadow-xl hover:bg-indigo-600 transition-all disabled:opacity-50">
                    Simpan Perubahan Profil
                </button>
            </form>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-center justify-between group">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-tight text-slate-900">Keamanan Akun</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klik untuk mengganti kata sandi Anda</p>
                </div>
                <Link :href="route('profile.edit')" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center font-bold group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    🔒
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>