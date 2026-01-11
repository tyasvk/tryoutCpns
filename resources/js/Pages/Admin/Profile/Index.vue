<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// Mengambil props dari Controller & Shared Data (Auth)
const props = defineProps({
    stats: Object,
});

const user = usePage().props.auth.user;

// Setup Form Inertia
const form = useForm({
    name: user.name,
    email: user.email,
    password: '',
    photo: null,
});

// Setup Preview Gambar
const photoPreview = ref(user.photo ? `/storage/${user.photo}` : '/img/default-profile.png');

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.photo = file;
        photoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    // Mengirim form dengan method POST (tapi _method: PUT ditangani Laravel otomatis jika pakai router resource, 
    // atau kita pakai post dengan FormData untuk upload file)
    form.post(route('admin.profile.update'), {
        onSuccess: () => form.reset('password'),
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Admin Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-6">
                    
                    <div class="w-full md:w-1/3 bg-white shadow-sm rounded-lg p-6 text-center border-t-4 border-blue-600">
                        <div class="mb-4 flex justify-center">
                            <img 
                                :src="photoPreview" 
                                class="w-32 h-32 rounded-full object-cover border-4 border-gray-200"
                            />
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ user.name }}</h3>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-1">Administrator</span>

                        <div class="mt-6 text-left space-y-3 text-sm text-gray-600 border-t pt-4">
                            <div class="flex items-center">
                                <span class="font-bold w-24">Email:</span>
                                <span>{{ user.email }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-bold w-24">IP Login:</span>
                                <span>{{ stats.last_ip }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-bold w-24">Total Login:</span>
                                <span>{{ stats.login_count }} kali</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-2/3 bg-white shadow-sm rounded-lg p-6">
                        <div class="border-b pb-3 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Pengaturan Akun</h3>
                            <p class="text-sm text-gray-500">Update profil dan keamanan akun admin.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input 
                                    type="text" 
                                    v-model="form.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                />
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Institusi</label>
                                <input 
                                    type="email" 
                                    v-model="form.email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                />
                                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input 
                                    type="password" 
                                    v-model="form.password"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ganti Foto</label>
                                <input 
                                    type="file" 
                                    @change="handlePhotoChange"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                />
                                <div v-if="form.errors.photo" class="text-red-500 text-xs mt-1">{{ form.errors.photo }}</div>
                            </div>

                            <div class="pt-4 text-right">
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>