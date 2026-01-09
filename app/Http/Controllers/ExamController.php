<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Tryout;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class ExamController extends Controller
{
    /**
     * Inisiasi sesi ujian baru.
     */
    public function start(Request $request)
    {
        $tryout = Tryout::findOrFail($request->tryout_id);

        // Buat record ujian baru
        $exam = Exam::create([
            'user_id' => Auth::id(),
            'tryout_id' => $tryout->id,
            'status' => 'ongoing',
            'answers' => [], // Inisialisasi array kosong
            'started_at' => Carbon::now(),
        ]);

        return redirect()->route('exam.show', $exam->id);
    }

    /**
     * Tampilan ruang ujian (Halaman pengerjaan soal).
     */
    public function show(Exam $exam)
{
    // Pastikan ujian milik user yang sedang login
    if ($exam->user_id !== auth()->id()) {
        abort(403);
    }

    // Load data tryout dan soalnya
    // Perhatikan: Kita gunakan 'content' bukan 'question_text'
    $exam->load(['tryout.questions' => function($query) {
        $query->select('id', 'tryout_id', 'subject', 'content', 'image', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e');
    }]);

    return Inertia::render('Exams/Show', [
        'exam' => $exam,
        'tryout' => $exam->tryout,
        'questions' => $exam->tryout->questions,
    ]);
}

    /**
     * Menyimpan jawaban dan menghitung skor akhir.
     */
    public function submit(Request $request, Exam $exam)
{
    $userAnswers = $request->answers; // Format: [question_id => 'A', ...]
    $questions = $exam->tryout->questions;

    $twk = 0; $tiu = 0; $tkp = 0;

    foreach ($questions as $q) {
        $answer = $userAnswers[$q->id] ?? null;

        if ($q->subject === 'TKP') {
            // TKP: Mengambil poin berdasarkan pilihan (1-5)
            if ($answer) {
                $column = 'points_' . strtolower($answer);
                $tkp += $q->$column;
            }
        } else {
            // TWK & TIU: Benar 5, Salah 0
            if ($answer === $q->correct_answer) {
                if ($q->subject === 'TWK') $twk += 5;
                if ($q->subject === 'TIU') $tiu += 5;
            }
        }
    }

    $total = $twk + $tiu + $tkp;

    // Logika Passing Grade CPNS Terbaru
    // TWK: 65, TIU: 80, TKP: 166 (Nilai ini bisa disesuaikan)
    $passed = ($twk >= 65 && $tiu >= 80 && $tkp >= 166);

    $exam->update([
        'score_twk' => $twk,
        'score_tiu' => $tiu,
        'score_tkp' => $tkp,
        'total_score' => $total,
        'is_passed' => $passed,
        'status' => 'completed',
        'completed_at' => now(),
    ]);

    return redirect()->route('exam.result', $exam->id);
}


    public function waitingRoom(Tryout $tryout)
{
    $now = now();
    
    // Status: upcoming (belum mulai), ongoing (sedang berlangsung), ended (berakhir)
    $status = 'ongoing';
    if ($tryout->start_at && $now->lt($tryout->start_at)) {
        $status = 'upcoming';
    } elseif ($tryout->end_at && $now->gt($tryout->end_at)) {
        $status = 'ended';
    }

    return Inertia::render('Exams/WaitingRoom', [
        'tryout' => $tryout,
        'status' => $status,
        'serverTime' => $now->toIso8601String(),
    ]);
}
    /**
     * Halaman Analisis Hasil Ujian (Deep Analysis & Radar Chart).
     */
    public function result(Exam $exam)
    {
        if ($exam->user_id !== Auth::id()) abort(403);

        $exam->load(['tryout.questions', 'user']);

        // 1. Hitung Rata-rata Global (Radar Chart Data)
        $globalAvg = Exam::where('tryout_id', $exam->tryout_id)
            ->where('status', 'completed')
            ->selectRaw('AVG(score_twk) as avg_twk, AVG(score_tiu) as avg_tiu, AVG(score_tkp) as avg_tkp')
            ->first();

        // 2. Analisis Per Topik (Berdasarkan sub_category)
        $analysis = $exam->tryout->questions->groupBy('sub_category')
            ->map(function ($questions, $subCategory) use ($exam) {
                $total = $questions->count();
                $correct = $questions->filter(function ($q) use ($exam) {
                    return isset($exam->answers[$q->id]) && $exam->answers[$q->id] === $q->answer;
                })->count();

                return [
                    'topic' => $subCategory ?? 'Umum',
                    'correct' => $correct,
                    'total' => $total,
                    'percentage' => $total > 0 ? round(($correct / $total) * 100) : 0
                ];
            })->values();

        // 3. Global Leaderboard (Top 10)
        $leaderboard = Exam::with('user')
            ->where('tryout_id', $exam->tryout_id)
            ->where('status', 'completed')
            ->orderByDesc('total_score')
            ->orderBy('completed_at', 'asc')
            ->take(10)
            ->get();

        // 4. Ranking User
        $userRank = Exam::where('tryout_id', $exam->tryout_id)
            ->where('status', 'completed')
            ->where('total_score', '>', $exam->total_score)
            ->count() + 1;

        return Inertia::render('Exams/Result', [
            'exam' => $exam,
            'globalAvg' => [
                'twk' => round($globalAvg->avg_twk ?? 0),
                'tiu' => round($globalAvg->avg_tiu ?? 0),
                'tkp' => round($globalAvg->avg_tkp ?? 0),
            ],
            'analysis' => $analysis,
            'leaderboard' => $leaderboard,
            'userRank' => $userRank
        ]);
    }

    /**
     * Menampilkan daftar riwayat ujian peserta.
     */
    public function history()
    {
        $exams = Exam::with('tryout')
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->paginate(10);

        return Inertia::render('Exams/History', [
            'exams' => $exams
        ]);
    }

    /**
     * Halaman Review Pembahasan Soal demi Soal.
     */
    public function userDashboard()
{
    $user = auth()->user();

    // 1. Statistik Nilai Rata-rata
    $averages = [
        'twk' => round(Exam::where('user_id', $user->id)->avg('score_twk') ?? 0),
        'tiu' => round(Exam::where('user_id', $user->id)->avg('score_tiu') ?? 0),
        'tkp' => round(Exam::where('user_id', $user->id)->avg('score_tkp') ?? 0),
    ];

    // 2. Ujian Terakhir (Untuk fitur "Lanjutkan" atau "Lihat Hasil")
    $recentExams = Exam::with('tryout')
        ->where('user_id', $user->id)
        ->latest()
        ->take(3)
        ->get();

    // 3. Paket Rekomendasi (Yang belum dikerjakan)
    $recommended = Tryout::where('is_published', true)
        ->whereDoesntHave('exams', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->withCount('questions')
        ->take(2)
        ->get();

    return Inertia::render('Dashboard', [
        'stats' => $averages,
        'recentExams' => $recentExams,
        'recommended' => $recommended,
        'totalExams' => Exam::where('user_id', $user->id)->count()
    ]);
}

    /**
     * Log Pelanggaran (Anti-Cheat).
     */
    public function logViolation(Exam $exam)
    {
        $exam->increment('violations');
        return response()->json(['message' => 'Violation logged']);
    }
}