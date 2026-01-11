<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    history: Array,
});

const user = usePage().props.auth.user;
const activeTab = ref('data'); // State untuk Tab ('data' atau 'history')

const form = useForm({
    name: user.name,
    phone: user.phone || '',
    address: user.address || '',
    password: '',
    photo: null,
});

// Default image jika user belum punya foto
const photoPreview = ref(user.photo ? `/storage/${user.photo}` : 'https://ui-avatars.com/api/?name=' + user.name);

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.photo = file;
        photoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('user.profile.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Profil Peserta" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profil Saya</h2>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl flex flex-col md:flex-row">
                    
                    <div class="w-full md:w-1/3 bg-gray-50 p-8 border-r border-gray-100 flex flex-col items-center text-center">
                        <div class="relative group mb-5">
                            <img 
                                :src="photoPreview" 
                                class="w-40 h-40 rounded-full object-cover shadow-md border-4 border-white"
                            />
                            <label class="absolute bottom-2 right-2 bg-green-500 text-white p-2 rounded-full cursor-pointer hover:bg-green-600 transition shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" class="hidden" @change="handlePhotoChange" accept="image/*" />
                            </label>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-800">{{ user.name }}</h2>
                        <p class="text-gray-500 mb-6 font-medium">Pejuang NIP 2025</p>
                        
                        <div class="w-full bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-left">
                            <div class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-2">Kontak</div>
                            <p class="text-sm text-gray-600 mb-1 flex items-center">
                                <span class="mr-2">📧</span> {{ user.email }}
                            </p>
                            <p class="text-sm text-gray-600 flex items-center">
                                <span class="mr-2">📱</span> {{ user.phone || '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full md:w-2/3 p-8">
                        
                        <div class="flex space-x-6 border-b border-gray-200 mb-6">
                            <button 
                                @click="activeTab = 'data'"
                                :class="{'border-b-2 border-green-500 text-green-600': activeTab === 'data', 'text-gray-500 hover:text-gray-700': activeTab !== 'data'}"
                                class="pb-2 font-medium text-lg transition-colors duration-200"
                            >
                                Edit Data Diri
                            </button>
                            <button 
                                @click="activeTab = 'history'"
                                :class="{'border-b-2 border-green-500 text-green-600': activeTab === 'history', 'text-gray-500 hover:text-gray-700': activeTab !== 'history'}"
                                class="pb-2 font-medium text-lg transition-colors duration-200"
                            >
                                Riwayat Tryout
                            </button>
                        </div>

                        <div v-if="activeTab === 'data'">
                            <form @submit.prevent="submit" class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" v-model="form.name" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition bg-gray-50 focus:bg-white" />
                                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">No. WhatsApp</label>
                                        <input type="text" v-model="form.phone" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition bg-gray-50 focus:bg-white" placeholder="0812..." />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                                        <input type="password" v-model="form.password" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition bg-gray-50 focus:bg-white" placeholder="(Opsional)" />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat Domisili</label>
                                    <textarea v-model="form.address" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition bg-gray-50 focus:bg-white"></textarea>
                                </div>

                                <div class="pt-2">
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition transform active:scale-95"
                                    >
                                        {{ form.processing ? 'Menyimpan...' : 'Simpan Profil' }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div v-if="activeTab === 'history'">
                            <div v-if="history.length > 0" class="space-y-3">
                                <div v-for="item in history" :key="item.id" class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-200 hover:shadow-md transition">
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-green-100 p-3 rounded-lg text-green-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ item.name }}</h4>
                                            <p class="text-xs text-gray-500">{{ item.date }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-400 uppercase font-bold">Skor</div>
                                        <div class="text-xl font-extrabold" :class="item.score >= 400 ? 'text-green-600' : 'text-yellow-500'">
                                            {{ item.score }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else class="text-center py-10">
                                <div class="bg-gray-100 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-gray-500 font-medium">Belum ada riwayat tryout</h3>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>