<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionController extends Controller
{
    // Menampilkan daftar soal berdasarkan paket tryout
    public function manage(Tryout $tryout)
    {
        return Inertia::render('Admin/Questions/Manage', [
            'tryout' => $tryout,
            'questions' => Question::where('tryout_id', $tryout->id)->latest()->get()
        ]);
    }

    // Form Tambah Soal
    public function create(Tryout $tryout)
    {
        return Inertia::render('Admin/Questions/Form', [
            'tryout' => $tryout,
            'question' => null
        ]);
    }

    // Simpan Soal Baru
    public function store(Request $request, Tryout $tryout)
    {
        $data = $request->validate([
            'subject' => 'required|in:TWK,TIU,TKP',
            'content' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'option_e' => 'required',
            'correct_answer' => 'required_if:subject,TWK,TIU',
            'points_a' => 'required_if:subject,TKP|numeric',
            'points_b' => 'required_if:subject,TKP|numeric',
            'points_c' => 'required_if:subject,TKP|numeric',
            'points_d' => 'required_if:subject,TKP|numeric',
            'points_e' => 'required_if:subject,TKP|numeric',
            'explanation' => 'nullable',
        ]);

        $data['tryout_id'] = $tryout->id;
        Question::create($data);

        return redirect()->route('admin.questions.manage', $tryout->id)
            ->with('success', 'Soal berhasil ditambahkan');
    }

    public function edit(Question $question)
    {
        return Inertia::render('Admin/Questions/Form', [
            'tryout' => $question->tryout,
            'question' => $question
        ]);
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'subject' => 'required|in:TWK,TIU,TKP',
            'content' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'option_e' => 'required',
            'correct_answer' => 'nullable',
            'points_a' => 'nullable|numeric',
            'points_b' => 'nullable|numeric',
            'points_c' => 'nullable|numeric',
            'points_d' => 'nullable|numeric',
            'points_e' => 'nullable|numeric',
            'explanation' => 'nullable',
        ]);

        $question->update($data);

        return redirect()->route('admin.questions.manage', $question->tryout_id)
            ->with('success', 'Soal berhasil diperbarui');
    }

    public function destroy(Question $question)
    {
        $tryoutId = $question->tryout_id;
        $question->delete();

        return redirect()->route('admin.questions.manage', $tryoutId)
            ->with('success', 'Soal berhasil dihapus');
    }
}