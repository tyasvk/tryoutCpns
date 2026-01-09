<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ user: Object, exams: Array });
const isEditing = ref(false);

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    form.put(route('admin.users.update', props.user.id), {
        onSuccess: () => isEditing.value = false,
    });
};
</script>

<template>
    <Head :title="'Profil: ' + user.name" />
    <AuthenticatedLayout>
        <div class="max-w-5xl mx-auto px-4 py-8">
            <Link :href="route('admin.users.index')" class="text-xs font-black text-indigo-600 uppercase mb-4 inline-block">← Kembali</Link>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm h-fit">
                    <h3 class="text-lg font-black text-slate-900 uppercase italic mb-6">Edit User</h3>
                    <form @submit.prevent="updateProfile" class="space-y-4">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Nama</label>
                            <input v-model="form.name" type="text" class="w-full bg-slate-50 border-none rounded-2xl text-sm p-3 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Email</label>
                            <input v-model="form.email" type="email" class="w-full bg-slate-50 border-none rounded-2xl text-sm p-3 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="pt-4 border-t border-slate-50">
                            <p class="text-[9px] text-slate-400 font-bold mb-2 italic">Kosongkan jika tidak ingin ganti password</p>
                            <input v-model="form.password" type="password" placeholder="Password Baru" class="w-full bg-slate-50 border-none rounded-2xl text-sm p-3 mb-2">
                            <input v-model="form.password_confirmation" type="password" placeholder="Konfirmasi Password" class="w-full bg-slate-50 border-none rounded-2xl text-sm p-3">
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200">Simpan Perubahan</button>
                    </form>
                </div>

                <div class="md:col-span-2 space-y-4">
                    <h3 class="text-lg font-black text-slate-900 uppercase italic mb-2">Riwayat Ujian</h3>
                    <div v-if="exams.length === 0" class="bg-white p-12 rounded-[2.5rem] text-center border border-dashed border-slate-200 text-slate-400 text-sm font-bold">
                        User ini belum pernah mengikuti ujian.
                    </div>
                    <div v-for="exam in exams" :key="exam.id" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-black text-slate-800 uppercase">{{ exam.tryout.name }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold">{{ new Date(exam.created_at).toLocaleString('id-ID') }}</p>
                        </div>
                        <div class="flex gap-4 items-center">
                            <div class="text-center">
                                <p class="text-[8px] font-black text-slate-400 uppercase">Skor</p>
                                <p class="text-xl font-black text-indigo-600">{{ exam.total_score }}</p>
                            </div>
                            <Link :href="route('exam.result', exam.id)" class="bg-slate-50 p-3 rounded-xl hover:bg-slate-100">
                                👁️
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>