<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String,
    active: Boolean,
    expanded: Boolean,
    icon: String,
});
</script>

<template>
    <Link :href="href" 
        :class="[active ? 'bg-indigo-600 text-white font-black shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:text-white hover:bg-white/5']"
        class="flex items-center h-12 rounded-xl transition-colors duration-200"
    >
        <div class="w-16 h-12 flex items-center justify-center shrink-0 text-lg">
            {{ icon }}
        </div>
        <span v-show="expanded" class="text-[10px] uppercase tracking-[0.15em] whitespace-nowrap pr-4 italic">
            <slot />
        </span>
    </Link>
</template>