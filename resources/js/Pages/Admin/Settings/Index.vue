<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object
});

const form = useForm({
    announcement: props.settings.announcement,
    grand_to_start: props.settings.grand_to_start,
    grand_to_end: props.settings.grand_to_end,
    is_to_akbar_active: props.settings.is_to_akbar_active,
});

const submit = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => alert('Pengaturan Berhasil Disimpan!'),
    });
};
</script>

<template>
    <Head title="Pengaturan Situs - CPNS Nusantara" />

    <AuthenticatedLayout>
        <template #header>Pengaturan Situs</template>

        <form @submit.prevent="submit" class="max-w-4xl space-y-6 pb-24">
            
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl">📢</div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-tight text-slate-900">Pengumuman Dashboard</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akan muncul di halaman utama peserta</p>
                    </div>
                </div>
                <textarea v-model="form.announcement" rows="4" 
                    class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all"
                    placeholder="Tulis pengumuman penting disini..."></textarea>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl">📅</div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-tight text-slate-900">Jadwal TO Akbar</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Atur waktu pelaksanaan tryout serentak</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Waktu Mulai</label>
                        <input v-model="form.grand_to_start" type="datetime-local" 
                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Waktu Selesai</label>
                        <input v-model="form.grand_to_end" type="datetime-local" 
                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-indigo-600">
                    </div>
                </div>

                <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-xs font-black uppercase tracking-widest text-slate-600">Aktifkan TO Akbar Sekarang</span>
                    <select v-model="form.is_to_akbar_active" class="bg-white border-slate-200 rounded-xl text-[10px] font-black uppercase">
                        <option value="1">YA (AKTIF)</option>
                        <option value="0">TIDAK (NON-AKTIF)</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" :disabled="form.processing"
                    class="flex-1 bg-slate-900 text-white py-5 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.3em] shadow-xl hover:bg-indigo-600 transition-all disabled:opacity-50">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </AuthenticatedLayout>
</template>