<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Tryout;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionController extends Controller
{
    /**
     * Tampilkan halaman kelola soal berdasarkan Tryout tertentu
     */
    public function manage(Tryout $tryout)
    {
        return Inertia::render('Admin/Tryouts/ManageQuestions', [
            'tryout' => $tryout,
            'questions' => Question::where('tryout_id', $tryout->id)
                            ->latest()
                            ->get(),
        ]);
    }

    /**
     * Simpan soal baru
     */
    public function store(Request $request)
    {
        $rules = [
            'tryout_id' => 'required|exists:tryouts,id',
            'content'   => 'required|string',
            'type'      => 'required|in:TWK,TIU,TKP',
            'option_a'  => 'required|string',
            'option_b'  => 'required|string',
            'option_c'  => 'required|string',
            'option_d'  => 'required|string',
            'option_e'  => 'required|string',
            'explanation' => 'nullable|string',
        ];

        // Logika Validasi Berdasarkan Tipe Soal
        if ($request->type === 'TKP') {
            $rules['point_a'] = 'required|numeric|min:1|max:5';
            $rules['point_b'] = 'required|numeric|min:1|max:5';
            $rules['point_c'] = 'required|numeric|min:1|max:5';
            $rules['point_d'] = 'required|numeric|min:1|max:5';
            $rules['point_e'] = 'required|numeric|min:1|max:5';
            // TKP tidak butuh kunci jawaban tunggal, set default A agar database tidak error
            $request->merge(['answer' => 'A']); 
        } else {
            $rules['answer'] = 'required|in:A,B,C,D,E';
        }

        $validated = $request->validate($rules);

        Question::create($validated);

        return back()->with('message', 'Soal berhasil ditambahkan!');
    }

    /**
     * Update soal yang sudah ada
     */
    public function update(Request $request, Question $question)
    {
        $rules = [
            'content'   => 'required|string',
            'type'      => 'required|in:TWK,TIU,TKP',
            'option_a'  => 'required|string',
            'option_b'  => 'required|string',
            'option_c'  => 'required|string',
            'option_d'  => 'required|string',
            'option_e'  => 'required|string',
            'explanation' => 'nullable|string',
        ];

        if ($request->type === 'TKP') {
            $rules['point_a'] = 'required|numeric';
            $rules['point_b'] = 'required|numeric';
            $rules['point_c'] = 'required|numeric';
            $rules['point_d'] = 'required|numeric';
            $rules['point_e'] = 'required|numeric';
            $request->merge(['answer' => 'A']);
        } else {
            $rules['answer'] = 'required|in:A,B,C,D,E';
        }

        $validated = $request->validate($rules);
        $question->update($validated);

        return back()->with('message', 'Soal berhasil diperbarui!');
    }

    /**
     * Hapus soal
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('message', 'Soal berhasil dihapus.');
    }
}