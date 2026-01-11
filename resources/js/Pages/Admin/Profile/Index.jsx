import React, { useState } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
// Asumsi Anda pakai Layout bawaan Breeze/Jetstream, sesuaikan path jika beda
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'; 

export default function AdminProfile({ auth, stats }) {
    const user = auth.user;
    
    // Setup Form Inertia
    const { data, setData, put, processing, errors, reset } = useForm({
        name: user.name,
        email: user.email,
        password: '',
        photo: null,
    });

    const [preview, setPreview] = useState(
        user.photo ? `/storage/${user.photo}` : '/img/default-profile.png'
    );

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('photo', file);
            setPreview(URL.createObjectURL(file));
        }
    };

    const submit = (e) => {
        e.preventDefault();
        // Mengirim request PUT ke route admin.profile.update
        put(route('admin.profile.update'), {
            onSuccess: () => reset('password'),
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Admin Profile</h2>}
        >
            <Head title="Admin Profile" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="flex flex-col md:flex-row gap-6">
                        
                        {/* Kiri: Kartu Foto */}
                        <div className="w-full md:w-1/3 bg-white shadow rounded-lg p-6 text-center">
                            <div className="mb-4 flex justify-center">
                                <img 
                                    src={preview} 
                                    alt="Admin Avatar" 
                                    className="w-32 h-32 rounded-full object-cover border-4 border-blue-500"
                                />
                            </div>
                            <h3 className="text-xl font-bold text-gray-800">{user.name}</h3>
                            <span className="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">Administrator</span>
                            
                            <div className="mt-6 text-left space-y-2 text-sm text-gray-600">
                                <p>📧 {user.email}</p>
                                <p>📅 Join: {new Date(user.created_at).toLocaleDateString()}</p>
                                <p>💻 IP: {stats.last_ip}</p>
                            </div>
                        </div>

                        {/* Kanan: Form Edit */}
                        <div className="w-full md:w-2/3 bg-white shadow rounded-lg p-6">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">Edit Informasi Akun</h3>
                            
                            <form onSubmit={submit} encType="multipart/form-data">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <input 
                                            type="text" 
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                        />
                                        {errors.name && <div className="text-red-500 text-sm mt-1">{errors.name}</div>}
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Email</label>
                                        <input 
                                            type="email" 
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                        />
                                        {errors.email && <div className="text-red-500 text-sm mt-1">{errors.email}</div>}
                                    </div>
                                </div>

                                <div className="mb-4">
                                    <label className="block text-sm font-medium text-gray-700">Ganti Password (Opsional)</label>
                                    <input 
                                        type="password" 
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        placeholder="Kosongkan jika tidak ingin mengubah"
                                    />
                                </div>

                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-gray-700">Foto Profil</label>
                                    <input 
                                        type="file" 
                                        className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        onChange={handleImageChange}
                                    />
                                    {errors.photo && <div className="text-red-500 text-sm mt-1">{errors.photo}</div>}
                                </div>

                                <div className="text-right">
                                    <button 
                                        type="submit" 
                                        disabled={processing}
                                        className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}