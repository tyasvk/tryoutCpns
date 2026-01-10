<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Tryout;
use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamController extends Controller
{
    /**
     * Menampilkan Ruang Tunggu & Instruksi
     */
    public function waitingRoom(Tryout $tryout)
    {
        return Inertia::render('Tryouts/Wait', [
            'tryout' => $tryout->loadCount('questions')
        ]);
    }

    /**
     * Membuat Sesi Ujian Baru
     */
    public function start(Request $request)
    {
        $tryout = Tryout::findOrFail($request->tryout_id);

        $exam = Exam::create([
            'user_id' => Auth::id(),
            'tryout_id' => $tryout->id,
            'status' => 'ongoing',
            'answers' => [],
            'started_at' => now(),
        ]);

        return redirect()->route('exam.show', $exam->id);
    }

    /**
     * Menampilkan Halaman Ujian (CAT Interface)
     */
    public function show(Exam $exam)
    {
        if ($exam->user_id !== Auth::id() || $exam->status !== 'ongoing') {
            return redirect()->route('dashboard');
        }

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
     * Menghitung Skor & Submit Ujian
     */
    public function submit(Request $request, Exam $exam)
    {
        $userAnswers = $request->answers; // [question_id => 'A', ...]
        $questions = Question::where('tryout_id', $exam->tryout_id)->get();

        $twk = 0; $tiu = 0; $tkp = 0;

        foreach ($questions as $q) {
            $ans = $userAnswers[$q->id] ?? null;

            if ($q->subject === 'TKP') {
                if ($ans) {
                    $pointField = 'points_' . strtolower($ans);
                    $tkp += $q->$pointField;
                }
            } else {
                if ($ans === $q->correct_answer) {
                    $q->subject === 'TWK' ? $twk += 5 : $tiu += 5;
                }
            }
        }

        // Standard Passing Grade CPNS
        $isPassed = ($twk >= 65 && $tiu >= 80 && $tkp >= 166);

        $exam->update([
            'answers' => $userAnswers,
            'score_twk' => $twk,
            'score_tiu' => $tiu,
            'score_tkp' => $tkp,
            'total_score' => $twk + $tiu + $tkp,
            'status' => 'completed',
            'is_passed' => $isPassed,
            'completed_at' => now(),
        ]);

        return redirect()->route('exam.result', $exam->id);
    }

    /**
     * Menampilkan Peringkat Nasional per Paket
     */
    public function leaderboard(Tryout $tryout)
    {
        $rankings = Exam::where('tryout_id', $tryout->id)
            ->where('status', 'completed')
            ->with('user')
            ->select('user_id', DB::raw('MAX(total_score) as top_score'), DB::raw('MAX(score_twk) as twk'), DB::raw('MAX(score_tiu) as tiu'), DB::raw('MAX(score_tkp) as tkp'))
            ->groupBy('user_id')
            ->orderBy('top_score', 'desc')
            ->take(50)
            ->get();

        return Inertia::render('Exams/Leaderboard', [
            'tryout' => $tryout,
            'rankings' => $rankings
        ]);
    }

    /**
     * Menampilkan Riwayat Ujian User
     */
    public function history()
    {
        $exams = Exam::where('user_id', Auth::id())
            ->with('tryout')
            ->where('status', 'completed')
            ->latest()
            ->get();

        return Inertia::render('Exams/History', [
            'exams' => $exams
        ]);
    }

    public function result(Exam $exam)
    {
        return Inertia::render('Exams/Result', ['exam' => $exam->load('tryout')]);
    }

    public function review(Exam $exam)
    {
        return Inertia::render('Exams/Review', [
            'exam' => $exam,
            'tryout' => $exam->tryout,
            'questions' => Question::where('tryout_id', $exam->tryout_id)->get()
        ]);
    }

    public function analysis()
{
    $user = auth()->user();
    $exams = Exam::where('user_id', $user->id)->where('status', 'completed')->get();

    // Hitung rata-rata
    $avgTwk = $exams->avg('score_twk') ?? 0;
    $avgTiu = $exams->avg('score_tiu') ?? 0;
    $avgTkp = $exams->avg('score_tkp') ?? 0;

    // Tentukan materi paling lemah
    $scores = [
        'TWK' => ($avgTwk / 150) * 100, // Persentase terhadap skor maksimal TWK
        'TIU' => ($avgTiu / 175) * 100, // Persentase terhadap skor maksimal TIU
        'TKP' => ($avgTkp / 225) * 100  // Persentase terhadap skor maksimal TKP
    ];
    
    asort($scores); // Urutkan dari yang terkecil
    $weakest = key($scores);

    return Inertia::render('Exams/Analysis', [
        'stats' => [
            'twk' => round($avgTwk, 1),
            'tiu' => round($avgTiu, 1),
            'tkp' => round($avgTkp, 1),
            'total_exams' => $exams->count(),
            'weakest_subject' => $weakest
        ]
    ]);
}

public function certificate(Exam $exam)
{
    if ($exam->user_id !== auth()->id()) {
        abort(403);
    }

    $exam->load(['user', 'tryout']);

    $pdf = Pdf::loadView('pdf.certificate', [
        'exam' => $exam,
        'user' => $exam->user,
        'tryout' => $exam->tryout,
        'date' => $exam->completed_at->format('d F Y')
    ])->setPaper('a4', 'portrait'); // DIUBAH MENJADI PORTRAIT

    return $pdf->download('Sertifikat_CPNS_Nusantara_' . $exam->id . '.pdf');
}
}