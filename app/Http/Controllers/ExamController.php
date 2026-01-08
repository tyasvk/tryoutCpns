<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Tryout; // <--- Pastikan Model Tryout di-import
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function start()
    {
        // 1. Cari Tryout Gratis sebagai default
        $tryout = Tryout::where('is_free', true)->first();

        // Jika tidak ada tryout gratis, ambil tryout apa saja yang ada (Fallback)
        if (!$tryout) {
            $tryout = Tryout::first();
        }

        // Jika database tryout kosong sama sekali, kembalikan ke dashboard
        if (!$tryout) {
            return redirect()->route('dashboard')->with('message', 'Belum ada paket simulasi yang tersedia.');
        }

        // 2. Buat Sesi Ujian Baru
        $exam = Exam::create([
            'user_id' => auth()->id(),
            'tryout_id' => $tryout->id, // Sekarang $tryout sudah terdefinisi
            'status' => 'active',
            'started_at' => now(),
            'answers' => [], // Inisialisasi array kosong
            'violations' => 0
        ]);

        // 3. Redirect ke halaman pengerjaan soal
        return redirect()->route('exam.show', $exam->id);
    }

    public function show(Exam $exam)
    {
        // Pastikan user hanya bisa membuka ujian miliknya sendiri
        if ($exam->user_id !== auth()->id()) {
            abort(403);
        }

        // Load relasi tryout
        $exam->load('tryout');

        // Ambil soal-soal terkait tryout ini
        // Mengurutkan berdasarkan tipe (TWK, TIU, TKP) agar rapi
        $questions = Question::where('tryout_id', $exam->tryout_id)
            ->orderByRaw("FIELD(type, 'TWK', 'TIU', 'TKP')")
            ->inRandomOrder() // Acak urutan soal dalam kategori
            ->get();

        return Inertia::render('Exam/Show', [
            'exam' => $exam,
            'questions' => $questions
        ]);
    }
    
    // ... method submit, result, dll tetap sama ...
    
    public function submit(Request $request, Exam $exam)
    {
        // Hitung Skor (Sederhana)
        $answers = $request->answers;
        $scoreTwk = 0;
        $scoreTiu = 0;
        $scoreTkp = 0;
        
        // Ambil semua soal untuk validasi jawaban
        $questions = Question::where('tryout_id', $exam->tryout_id)->get()->keyBy('id');

        foreach ($answers as $qId => $userAns) {
            if (isset($questions[$qId])) {
                $q = $questions[$qId];

                if ($q->type === 'TKP') {
                    // Logika Nilai TKP (1-5)
                    $col = 'point_' . strtolower($userAns); // misal point_a
                    $points = $q->$col ?? 0;
                    $scoreTkp += $points;
                } else {
                    // Logika Nilai TWK & TIU (Benar=5, Salah=0)
                    if ($q->answer === $userAns) {
                        if ($q->type === 'TWK') $scoreTwk += 5;
                        if ($q->type === 'TIU') $scoreTiu += 5;
                    }
                }
            }
        }

        $exam->update([
            'answers' => $answers,
            'score_twk' => $scoreTwk,
            'score_tiu' => $scoreTiu,
            'score_tkp' => $scoreTkp,
            'total_score' => $scoreTwk + $scoreTiu + $scoreTkp,
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->route('exam.result', $exam->id);
    }

    public function result(Exam $exam)
    {
        $exam->load('tryout');
        
        // Data Rata-rata Nasional (Dummy/Real)
        $averages = [
            'twk' => round(Exam::where('tryout_id', $exam->tryout_id)->avg('score_twk') ?? 0),
            'tiu' => round(Exam::where('tryout_id', $exam->tryout_id)->avg('score_tiu') ?? 0),
            'tkp' => round(Exam::where('tryout_id', $exam->tryout_id)->avg('score_tkp') ?? 0),
        ];

        return Inertia::render('Exam/Result', [
            'exam' => $exam,
            'averages' => $averages
        ]);
    }
    
    // Method untuk log violation anti-cheat
    public function logViolation(Exam $exam)
    {
        $exam->increment('violations');
        if ($exam->violations >= 3) {
            $exam->update(['status' => 'completed', 'completed_at' => now()]);
            return response()->json(['status' => 'forced_stop', 'current_violations' => $exam->violations]);
        }
        return response()->json(['status' => 'logged', 'current_violations' => $exam->violations]);
    }
    
    // Method Review Pembahasan
    public function review(Exam $exam)
    {
        if ($exam->user_id !== auth()->id()) abort(403);
        
        $exam->load('tryout');
        $questions = Question::where('tryout_id', $exam->tryout_id)
            ->orderByRaw("FIELD(type, 'TWK', 'TIU', 'TKP')")
            ->get();

        return Inertia::render('Exam/Review', [
            'exam' => $exam,
            'questions' => $questions
        ]);
    }
}