import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function UserProfile({ auth, history }) {
    const user = auth.user;
    const [activeTab, setActiveTab] = useState('data'); // State untuk Tab (Data vs History)

    // Setup Form
    const { data, setData, put, processing, errors } = useForm({
        name: user.name,
        phone: user.phone || '',
        address: user.address || '',
        password: '',
        photo: null,
    });

    const [preview, setPreview] = useState(
        user.photo ? `/storage/${user.photo}` : '/img/default-user.png'
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
        put(route('user.profile.update'));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Profil Peserta</h2>}
        >
            <Head title="Profil Saya" />

            <div className="py-12">
                <div className="max-w-5xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col md:flex-row">
                        
                        {/* Kolom Kiri: Ringkasan */}
                        <div className="w-full md:w-1/3 bg-gray-50 p-8 border-r border-gray-200 flex flex-col items-center text-center">
                            <div className="relative group mb-4">
                                <img 
                                    src={preview} 
                                    className="w-40 h-40 rounded-full object-cover shadow-lg border-4 border-white"
                                />
                                <label className="absolute bottom-2 right-2 bg-green-500 text-white p-2 rounded-full cursor-pointer hover:bg-green-600 transition shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fillRule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0011.586 3H8.414a1 1 0 00-.707.293L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clipRule="evenodd" />
                                    </svg>
                                    <input type="file" className="hidden" onChange={handleImageChange} accept="image/*" />
                                </label>
                            </div>
                            
                            <h2 className="text-2xl font-bold text-gray-800">{user.name}</h2>
                            <p className="text-gray-500 mb-4">Peserta Tryout</p>
                            
                            <div className="w-full text-left mt-4 pl-4 border-l-4 border-green-500 bg-white p-3 shadow-sm rounded">
                                <p className="text-sm text-gray-600 mb-1"><span className="font-bold">Email:</span> {user.email}</p>
                                <p className="text-sm text-gray-600"><span className="font-bold">HP:</span> {user.phone || '-'}</p>
                            </div>
                        </div>

                        {/* Kolom Kanan: Tabs & Content */}
                        <div className="w-full md:w-2/3 p-8">
                            {/* Navigasi Tab */}
                            <div className="flex border-b border-gray-200 mb-6">
                                <button 
                                    onClick={() => setActiveTab('data')}
                                    className={`py-2 px-4 font-medium transition-colors duration-200 ${activeTab === 'data' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700'}`}
                                >
                                    Edit Data Diri
                                </button>
                                <button 
                                    onClick={() => setActiveTab('history')}
                                    className={`py-2 px-4 font-medium transition-colors duration-200 ${activeTab === 'history' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700'}`}
                                >
                                    Riwayat Tryout
                                </button>
                            </div>

                            {/* Konten Tab: Data Form */}
                            {activeTab === 'data' && (
                                <form onSubmit={submit}>
                                    <div className="space-y-4">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                            <input type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                value={data.name} onChange={(e) => setData('name', e.target.value)} />
                                            {errors.name && <div className="text-red-500 text-sm">{errors.name}</div>}
                                        </div>

                                        <div className="grid grid-cols-2 gap-4">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700">No. HP / WA</label>
                                                <input type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                    value={data.phone} onChange={(e) => setData('phone', e.target.value)} />
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700">Password Baru</label>
                                                <input type="password" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                    value={data.password} onChange={(e) => setData('password', e.target.value)} placeholder="(Opsional)" />
                                            </div>
                                        </div>

                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Alamat</label>
                                            <textarea className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" rows="3"
                                                value={data.address} onChange={(e) => setData('address', e.target.value)}></textarea>
                                        </div>

                                        <div className="pt-4">
                                            <button type="submit" disabled={processing} className="w-full flex justify-center py-2 px-4 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                Simpan Profil
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            )}

                            {/* Konten Tab: History */}
                            {activeTab === 'history' && (
                                <div className="space-y-4">
                                    {history.length > 0 ? (
                                        history.map((item) => (
                                            <div key={item.id} className="flex justify-between items-center bg-gray-50 p-4 rounded-lg border border-gray-200">
                                                <div>
                                                    <h4 className="font-bold text-gray-800">{item.name}</h4>
                                                    <p className="text-sm text-gray-500">📅 {item.date}</p>
                                                </div>
                                                <div className="text-right">
                                                    <span className="block text-xs text-gray-500 uppercase font-bold">Skor</span>
                                                    <span className={`text-xl font-bold ${item.score >= 400 ? 'text-green-600' : 'text-yellow-600'}`}>
                                                        {item.score}
                                                    </span>
                                                </div>
                                            </div>
                                        ))
                                    ) : (
                                        <div className="text-center py-8 text-gray-500">
                                            <p>Belum ada riwayat tryout.</p>
                                        </div>
                                    )}
                                </div>
                            )}

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}