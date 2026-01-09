<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Tryout;
use App\Imports\QuestionsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuestionController extends Controller
{
    /**
     * Menampilkan daftar soal berdasarkan Paket Tryout
     */
    public function manage(Tryout $tryout): Response
    {
        $questions = Question::where('tryout_id', $tryout->id)->latest()->get();

        return Inertia::render('Admin/Questions/Manage', [
            'tryout' => $tryout->loadCount('questions'),
            'questions' => $questions,
            'stats' => [
                'TWK' => $questions->where('subject', 'TWK')->count(),
                'TIU' => $questions->where('subject', 'TIU')->count(),
                'TKP' => $questions->where('subject', 'TKP')->count(),
            ]
        ]);
    }

    /**
     * Form Tambah Soal Baru
     */
    public function create(Tryout $tryout): Response
    {
        return Inertia::render('Admin/Questions/Create', [
            'tryout' => $tryout
        ]);
    }

    /**
     * Simpan Soal Baru ke Database
     */
    public function store(Request $request, Tryout $tryout)
    {
        $validated = $request->validate([
            'subject' => 'required|in:TWK,TIU,TKP',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'explanation' => 'nullable|string',
            'points_a' => 'required|integer',
            'points_b' => 'required|integer',
            'points_c' => 'required|integer',
            'points_d' => 'required|integer',
            'points_e' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('questions', 'public');
        }

        $tryout->questions()->create($validated);

        return redirect()->route('admin.questions.manage', $tryout->id)
            ->with('message', 'Soal berhasil disimpan!');
    }

    /**
     * Form Edit Soal (Memperbaiki error Undefined Method)
     */
    public function edit(Question $question): Response
    {
        return Inertia::render('Admin/Questions/Edit', [
            'question' => $question,
            'tryout' => $question->tryout // Pastikan relasi tryout() ada di model Question
        ]);
    }

    /**
     * Update Data Soal
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'subject' => 'required|in:TWK,TIU,TKP',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'explanation' => 'nullable|string',
            'points_a' => 'required|integer',
            'points_b' => 'required|integer',
            'points_c' => 'required|integer',
            'points_d' => 'required|integer',
            'points_e' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika user upload gambar baru
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
            $validated['image'] = $request->file('image')->store('questions', 'public');
        }

        $question->update($validated);

        return redirect()->route('admin.questions.manage', $question->tryout_id)
            ->with('message', 'Soal berhasil diperbarui!');
    }

    /**
     * Preview Tampilan Soal
     */
    public function preview(Question $question): Response
    {
        return Inertia::render('Admin/Questions/Preview', [
            'question' => $question->load('tryout')
        ]);
    }

    /**
     * Hapus Soal secara Permanen
     */
    public function destroy(Question $question)
    {
        $tryoutId = $question->tryout_id;
        
        if ($question->image) {
            Storage::disk('public')->delete($question->image);
        }
        
        $question->delete();

        return redirect()->route('admin.questions.manage', $tryoutId)
            ->with('message', 'Soal berhasil dihapus.');
    }

    /**
     * Fitur Bulk Import
     */
    public function showImport(Tryout $tryout): Response
    {
        return Inertia::render('Admin/Questions/Import', ['tryout' => $tryout]);
    }

    public function import(Request $request, Tryout $tryout)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:2048']);
        Excel::import(new QuestionsImport($tryout->id), $request->file('file'));
        return redirect()->route('admin.questions.manage', $tryout->id);
    }

    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_soal_nusantara.csv"',
        ];

        return response()->stream(function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['kategori', 'pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'jawaban_benar', 'pembahasan']);
            fclose($file);
        }, 200, $headers);
    }
}