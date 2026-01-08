<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Carbon\Carbon;
use Inertia\Inertia;

class MonitoringController extends Controller
{
    public function index()
    {
        // Ambil ujian yang sedang berlangsung (belum selesai)
        $activeExams = Exam::with(['user', 'tryout'])
            ->whereNull('completed_at')
            ->where('status', 'active') // Pastikan status saat start diset 'active'
            ->latest()
            ->get()
            ->map(function ($exam) {
                $startTime = Carbon::parse($exam->started_at);
                $now = Carbon::now();
                
                // Hitung sisa waktu (asumsi durasi dari tryout)
                $duration = $exam->tryout->duration ?? 100;
                $elapsed = $startTime->diffInMinutes($now);
                $remaining = max(0, $duration - $elapsed);
                
                return [
                    'id' => $exam->id,
                    'user_name' => $exam->user->name,
                    'tryout_name' => $exam->tryout->name,
                    'started_at' => $startTime->format('H:i:s'),
                    'remaining_time' => $remaining . ' Min',
                    'progress' => round(($elapsed / $duration) * 100),
                ];
            });

        return Inertia::render('Admin/Monitoring/Index', [
            'activeExams' => $activeExams
        ]);
    }

    public function forceStop(Exam $exam)
    {
        $exam->update([
            'completed_at' => now(),
            'status' => 'completed'
        ]);
        return back()->with('message', 'Ujian berhasil dihentikan paksa.');
    }
}