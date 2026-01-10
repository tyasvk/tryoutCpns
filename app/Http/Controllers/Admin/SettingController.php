<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        // Mengambil semua setting dan merubahnya menjadi key => value
        $settings = Setting::pluck('value', 'key')->all();

        return Inertia::render('Admin/Settings/Index', [
            'settings' => [
                'announcement' => $settings['announcement'] ?? '',
                'grand_to_start' => $settings['grand_to_start'] ?? '',
                'grand_to_end' => $settings['grand_to_end'] ?? '',
                'is_to_akbar_active' => $settings['is_to_akbar_active'] ?? '0',
            ]
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'announcement' => 'nullable|string',
            'grand_to_start' => 'nullable|string',
            'grand_to_end' => 'nullable|string',
            'is_to_akbar_active' => 'required|string',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Pengaturan situs berhasil diperbarui');
    }
}