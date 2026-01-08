<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const isSidebarOpen = ref(false);
const page = usePage();
const user = page.props.auth.user;

const menuItems = [
    { name: 'Overview', icon: '📊', route: 'dashboard', active: 'dashboard' },
    { name: 'Bank Soal', icon: '📚', route: 'admin.questions.index', active: 'admin.questions.*', adminOnly: true },
    { name: 'Tryout Pro', icon: '🏆', route: 'admin.tryouts.index', active: 'admin.tryouts.*', adminOnly: true },
    { name: 'Peserta', icon: '👥', route: 'admin.users.index', active: 'admin.users.*', adminOnly: true },
    { name: 'Live Monitor', icon: '📡', route: 'admin.monitoring.index', active: 'admin.monitoring.*', adminOnly: true },
    { name: 'Transaksi', icon: '💳', route: 'admin.transactions.index', active: 'admin.transactions.*', adminOnly: true },
];
</script>

<template>
    <div class="flex min-h-screen bg-white text-slate-900 font-sans selection:bg-indigo-100">
        <aside 
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-60 bg-slate-950 text-slate-400 transition-all duration-300 lg:static lg:inset-0 border-r border-slate-900"
        >
            <div class="flex h-full flex-col p-5">
                <div class="mb-8 flex items-center gap-3 px-2">
                    <div class="h-9 w-9 bg-white rounded-lg flex items-center justify-center font-black text-indigo-600 italic text-lg shadow-xl">T</div>
                    <span class="text-sm font-black tracking-tighter text-white uppercase italic">CAT<span class="text-indigo-500">26</span></span>
                </div>

                <nav class="flex-1 space-y-1">
                    <template v-for="item in menuItems" :key="item.name">
                        <Link 
                            v-if="!item.adminOnly || user.is_admin"
                            :href="route(item.route)"
                            :class="route().current(item.active) ? 'bg-indigo-600 text-white' : 'hover:bg-slate-900 hover:text-white'"
                            class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest transition-all"
                        >
                            <span class="text-base">{{ item.icon }}</span>
                            {{ item.name }}
                        </Link>
                    </template>
                </nav>

                <div class="mt-auto pt-6 border-t border-slate-900">
                    <div class="flex items-center gap-3 mb-4 px-2">
                        <div class="h-8 w-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white font-black text-[10px]">{{ user.name.charAt(0) }}</div>
                        <div class="overflow-hidden">
                            <p class="truncate text-[10px] font-black text-white leading-none">{{ user.name }}</p>
                            <p class="text-[8px] text-slate-500 uppercase font-black tracking-widest mt-1">Admin</p>
                        </div>
                    </div>
                    <Link :href="route('logout')" method="post" as="button" class="w-full text-left px-4 py-2 text-[9px] font-black uppercase tracking-widest text-red-400 hover:bg-red-500/10 rounded-xl transition-all">
                        🚪 Keluar
                    </Link>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="lg:hidden flex h-14 items-center justify-between px-6 bg-white border-b border-slate-100">
                <span class="font-black italic text-xs uppercase">CAT ONLINE</span>
                <button @click="isSidebarOpen = !isSidebarOpen" class="w-10 h-10 bg-slate-900 text-white rounded-xl">☰</button>
            </header>

            <div class="hidden lg:flex h-16 items-center px-8">
                <slot name="header" />
            </div>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 lg:pt-0">
                <slot />
            </main>
        </div>
    </div>
</template>