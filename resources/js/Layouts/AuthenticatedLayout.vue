<script setup>
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => !!user.value?.is_admin);

const logout = () => {
    if (confirm('Keluar dari sistem?')) {
        router.post(route('logout'));
    }
};

const activeClass = 'bg-indigo-600 text-white shadow-lg shadow-indigo-100';
const inactiveClass = 'text-slate-500 hover:bg-slate-50';
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans text-slate-900">
        
        <nav class="sticky top-0 z-40 bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6">
            <Link :href="isAdmin ? route('admin.dashboard') : route('dashboard')" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black">C</div>
                <span class="font-black uppercase tracking-tighter text-sm">CPNS Nusantara</span>
            </Link>

            <button @click="logout" class="text-rose-500 p-2 hover:bg-rose-50 rounded-full transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </nav>

        <div class="flex">
            <aside class="hidden lg:flex w-64 flex-col bg-white border-r border-slate-200 h-[calc(100vh-4rem)] sticky top-16 shrink-0">
                <div class="p-4 space-y-1">
                    <template v-if="isAdmin">
                        <Link :href="route('admin.dashboard')" :class="[route().current('admin.dashboard') ? activeClass : inactiveClass]" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                            <span class="text-lg">📊</span>
                            <span class="text-xs font-black uppercase tracking-widest">Overview</span>
                        </Link>
                        <Link :href="route('admin.tryouts.index')" :class="[route().current('admin.tryouts.*') ? activeClass : inactiveClass]" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                            <span class="text-lg">📚</span>
                            <span class="text-xs font-black uppercase tracking-widest">Kelola Paket</span>
                        </Link>
                        <Link :href="route('admin.users.index')" :class="[route().current('admin.users.*') ? activeClass : inactiveClass]" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                            <span class="text-lg">👥</span>
                            <span class="text-xs font-black uppercase tracking-widest">Peserta</span>
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('dashboard')" :class="[route().current('dashboard') ? activeClass : inactiveClass]" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                            <span class="text-lg">🏠</span>
                            <span class="text-xs font-black uppercase tracking-widest">Dashboard</span>
                        </Link>
                        <Link :href="route('marketplace.index')" :class="[route().current('marketplace.*') ? activeClass : inactiveClass]" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                            <span class="text-lg">🛍️</span>
                            <span class="text-xs font-black uppercase tracking-widest">Tryout</span>
                        </Link>
                        <Link :href="route('exam.history')" :class="[route().current('exam.*') ? activeClass : inactiveClass]" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                            <span class="text-lg">📝</span>
                            <span class="text-xs font-black uppercase tracking-widest">Riwayat</span>
                        </Link>
                    </template>
                    <Link :href="route('profile.edit')" :class="[route().current('profile.edit') ? activeClass : inactiveClass]" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                        <span class="text-lg">👤</span>
                        <span class="text-xs font-black uppercase tracking-widest">Profil</span>
                    </Link>
                </div>
            </aside>

            <main class="flex-1 p-4 pb-24 lg:pb-8 overflow-hidden">
                <div class="max-w-5xl mx-auto">
                    <h1 v-if="$slots.header" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">
                        <slot name="header" />
                    </h1>
                    <slot />
                </div>
            </main>
        </div>

        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-6 py-2 flex justify-between items-center z-50">
            <template v-if="isAdmin">
                <Link :href="route('admin.dashboard')" :class="[route().current('admin.dashboard') ? 'text-indigo-600' : 'text-slate-400']" class="p-3 text-2xl">📊</Link>
                <Link :href="route('admin.tryouts.index')" :class="[route().current('admin.tryouts.*') ? 'text-indigo-600' : 'text-slate-400']" class="p-3 text-2xl">📚</Link>
                <Link :href="route('admin.users.index')" :class="[route().current('admin.users.*') ? 'text-indigo-600' : 'text-slate-400']" class="p-3 text-2xl">👥</Link>
            </template>
            <template v-else>
                <Link :href="route('dashboard')" :class="[route().current('dashboard') ? 'text-indigo-600' : 'text-slate-400']" class="p-3 text-2xl">🏠</Link>
                <Link :href="route('marketplace.index')" :class="[route().current('marketplace.*') ? 'text-indigo-600' : 'text-slate-400']" class="p-3 text-2xl">🛍️</Link>
                <Link :href="route('exam.history')" :class="[route().current('exam.*') ? 'text-indigo-600' : 'text-slate-400']" class="p-3 text-2xl">📝</Link>
            </template>
            <Link :href="route('profile.edit')" :class="[route().current('profile.edit') ? 'text-indigo-600' : 'text-slate-400']" class="p-3 text-2xl">👤</Link>
        </div>
    </div>
</template>