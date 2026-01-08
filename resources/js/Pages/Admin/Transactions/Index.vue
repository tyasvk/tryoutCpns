<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({ transactions: Array });

const updateStatus = (id, status) => {
    if (confirm(`Ubah transaksi menjadi ${status}?`)) {
        router.patch(route('admin.transactions.update', id), { status });
    }
};
</script>

<template>
    <Head title="Manajemen Transaksi" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-lg font-black uppercase italic text-slate-800">Riwayat Transaksi</h2>
        </template>

        <div class="max-w-4xl mx-auto py-8 px-4 space-y-4">
            <div v-for="trx in transactions" :key="trx.id" class="bg-white p-6 rounded-[2rem] border border-slate-100 flex flex-col md:flex-row justify-between md:items-center gap-4 shadow-sm group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                        {{ trx.status === 'success' ? '✅' : '⏳' }}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">{{ trx.user.name }}</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Membeli: {{ trx.tryout.name }}</p>
                    </div>
                </div>

                <div class="flex flex-col md:items-end gap-1">
                    <span class="text-sm font-black text-indigo-600">Rp {{ trx.amount.toLocaleString() }}</span>
                    <div class="flex items-center gap-2">
                        <span :class="trx.status === 'success' ? 'text-emerald-500 bg-emerald-50' : 'text-amber-500 bg-amber-50'" class="text-[8px] font-black px-2 py-0.5 rounded uppercase border border-current opacity-70">
                            {{ trx.status }}
                        </span>
                    </div>
                </div>

                <div v-if="trx.status === 'pending'" class="flex gap-2">
                    <button @click="updateStatus(trx.id, 'success')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase shadow-lg shadow-indigo-100">Approve</button>
                    <button @click="updateStatus(trx.id, 'failed')" class="px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-[10px] font-black uppercase">Reject</button>
                </div>
            </div>

            <div v-if="transactions.length === 0" class="text-center py-20 bg-slate-50 rounded-[2.5rem] text-slate-400 text-xs font-black uppercase italic">
                Belum ada transaksi masuk.
            </div>
        </div>
    </AuthenticatedLayout>
</template>