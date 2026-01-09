<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const isSidebarOpen = ref(true);
const isMobileMenuOpen = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex font-sans">
        
        <aside 
            :class="[isSidebarOpen ? 'w-72' : 'w-24']"
            class="hidden lg:flex flex-col bg-white border-r border-slate-200 transition-all duration-300 sticky top-0 h-screen z-40 shadow-sm"
        >
            <div class="h-20 flex items-center px-6 border-b border-slate-50 overflow-hidden shrink-0">
                <Link :href="user.is_admin ? route('admin.dashboard') : route('dashboard')" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                        <span class="text-white font-black italic text-xl">N</span>
                    </div>
                    <h1 v-show="isSidebarOpen" class="text-lg font-black italic tracking-tighter text-slate-900 uppercase whitespace-nowrap">
                        Nusantara<span class="text-indigo-600 font-black">CAT</span>
                    </h1>
                </Link>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-2 custom-scrollbar">
                
                <template v-if="user.is_admin">
                    <div v-show="isSidebarOpen" class="px-4 py-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Admin Control</p>
                    </div>
                    
                    <SidebarLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard')" :expanded="isSidebarOpen">
                        <template #icon>📊</template> Overview
                    </SidebarLink>
                    
                    <SidebarLink :href="route('admin.tryouts.index')" :active="route().current('admin.tryouts.*') || route().current('admin.questions.*')" :expanded="isSidebarOpen">
                        <template #icon>📚</template> Kelola Paket
                    </SidebarLink>
                    
                    <SidebarLink :href="route('admin.users.index')" :active="route().current('admin.users.*')" :expanded="isSidebarOpen">
                        <template #icon>👥</template> Data Peserta
                    </SidebarLink>
                </template>

                <template v-else>
                    <div v-show="isSidebarOpen" class="px-4 py-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Menu Peserta</p>
                    </div>
                    
                    <SidebarLink :href="route('dashboard')" :active="route().current('dashboard')" :expanded="isSidebarOpen">
                        <template #icon>🏠</template> Dashboard
                    </SidebarLink>
                    
                    <SidebarLink :href="route('marketplace.index')" :active="route().current('marketplace.*') || route().current('tryout.wait')" :expanded="isSidebarOpen">
                        <template #icon>🛍️</template> Beli Paket
                    </SidebarLink>
                    
                    <SidebarLink :href="route('exam.history')" :active="route().current('exam.*')" :expanded="isSidebarOpen">
                        <template #icon>📝</template> Riwayat Ujian
                    </SidebarLink>
                </template>

                <div class="pt-4 border-t border-slate-50 mt-4">
                     <SidebarLink :href="route('profile.edit')" :active="route().current('profile.edit')" :expanded="isSidebarOpen">
                        <template #icon>⚙️</template> Pengaturan
                    </SidebarLink>
                </div>
            </nav>

            <div class="p-4 border-t border-slate-50 bg-slate-50/30">
                <Dropdown align="top" width="48">
                    <template #trigger>
                        <button class="w-full flex items-center gap-3 p-2 rounded-2xl hover:bg-white hover:shadow-sm transition-all text-left">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center font-black text-white uppercase italic shrink-0 shadow-sm">
                                {{ user.name.substring(0,2) }}
                            </div>
                            <div v-show="isSidebarOpen" class="overflow-hidden">
                                <p class="text-[11px] font-black text-slate-800 truncate uppercase italic leading-none mb-1">{{ user.name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 truncate uppercase tracking-tighter">{{ user.is_admin ? 'Administrator' : 'Peserta' }}</p>
                            </div>
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('logout')" method="post" as="button" class="text-rose-600 font-bold uppercase text-[10px] tracking-widest"> 
                            🚪 Keluar Sistem
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 lg:px-8 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="toggleSidebar" class="hidden lg:flex w-10 h-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-slate-900 transition-all">
                        <svg v-if="isSidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                    </button>
                    
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                    </button>

                    <h2 v-if="$slots.header" class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] italic hidden sm:block">
                        <slot name="header" />
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col text-right">
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1 text-right">Server Time</p>
                        <p class="text-[10px] font-black text-slate-800 uppercase italic">{{ new Date().toLocaleTimeString('id-ID') }} WIB</p>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto bg-slate-50/50 p-4 lg:p-10">
                <slot />
            </div>
        </main>

        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 -translate-x-10" enter-to-class="opacity-100 translate-x-0" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 translate-x-0" leave-to-class="opacity-0 -translate-x-10">
            <div v-show="isMobileMenuOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] lg:hidden" @click="isMobileMenuOpen = false">
                <div class="w-72 h-full bg-white p-6 shadow-2xl" @click.stop>
                    <div class="flex justify-between items-center mb-10 pb-6 border-b border-slate-50">
                        <h2 class="text-xl font-black italic uppercase tracking-tighter">Nusantara<span class="text-indigo-600">CAT</span></h2>
                        <button @click="isMobileMenuOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400">✕</button>
                    </div>
                    <nav class="space-y-2">
                        <template v-if="user.is_admin">
                            <Link :href="route('admin.dashboard')" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 font-black text-[10px] uppercase tracking-widest text-slate-900 hover:bg-indigo-600 hover:text-white transition-all">📊 Overview</Link>
                            <Link :href="route('admin.tryouts.index')" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 font-black text-[10px] uppercase tracking-widest text-slate-900 hover:bg-indigo-600 hover:text-white transition-all">📚 Kelola Paket</Link>
                            <Link :href="route('admin.users.index')" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 font-black text-[10px] uppercase tracking-widest text-slate-900 hover:bg-indigo-600 hover:text-white transition-all">👥 Data Peserta</Link>
                        </template>
                        <template v-else>
                            <Link :href="route('dashboard')" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 font-black text-[10px] uppercase tracking-widest text-slate-900 hover:bg-indigo-600 hover:text-white transition-all">🏠 Dashboard</Link>
                            <Link :href="route('marketplace.index')" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 font-black text-[10px] uppercase tracking-widest text-slate-900 hover:bg-indigo-600 hover:text-white transition-all">🛍️ Beli Paket</Link>
                            <Link :href="route('exam.history')" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 font-black text-[10px] uppercase tracking-widest text-slate-900 hover:bg-indigo-600 hover:text-white transition-all">📝 Riwayat Ujian</Link>
                        </template>
                    </nav>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
const SidebarLink = {
    props: ['href', 'active', 'expanded'],
    template: `
        <Link :href="href" 
            :class="[active ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 scale-105' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']"
            class="flex items-center h-12 rounded-2xl transition-all duration-300 overflow-hidden relative group"
        >
            <div class="w-16 h-12 flex items-center justify-center shrink-0 text-xl group-hover:scale-125 transition-transform duration-300">
                <slot name="icon" />
            </div>
            <span v-show="expanded" class="text-[10px] font-black uppercase tracking-[0.2em] whitespace-nowrap pr-6 italic">
                <slot />
            </span>
            <div v-if="active && expanded" class="absolute right-4 w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
        </Link>
    `
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #f1f5f9;
    border-radius: 10px;
}
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>