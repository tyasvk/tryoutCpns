<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $tryout_id;

    /**
     * Menerima ID Tryout dari Controller
     */
    public function __construct($tryout_id)
    {
        $this->tryout_id = $tryout_id;
    }

    /**
     * Memetakan baris Excel ke Model Question
     */
    public function model(array $row)
    {
        // Standarisasi Jawaban Benar menjadi Huruf Kapital (A, B, C, D, E)
        $correct = strtoupper(trim($row['jawaban_benar']));
        $subject = strtoupper(trim($row['kategori']));

        return new Question([
            'tryout_id'     => $this->tryout_id,
            'subject'       => $subject,
            'question_text' => $row['pertanyaan'],
            'option_a'      => $row['opsi_a'],
            'option_b'      => $row['opsi_b'],
            'option_c'      => $row['opsi_c'],
            'option_d'      => $row['opsi_d'],
            'option_e'      => $row['opsi_e'],
            'correct_answer'=> $correct,
            'explanation'   => $row['pembahasan'] ?? null,
            
            // Logika Poin Otomatis:
            // Jika TKP, biasanya poin 1-5. Jika TWK/TIU, benar = 5, salah = 0.
            'points_a' => $row['poin_a'] ?? ($correct === 'A' ? 5 : 0),
            'points_b' => $row['poin_b'] ?? ($correct === 'B' ? 5 : 0),
            'points_c' => $row['poin_c'] ?? ($correct === 'C' ? 5 : 0),
            'points_d' => $row['poin_d'] ?? ($correct === 'D' ? 5 : 0),
            'points_e' => $row['poin_e'] ?? ($correct === 'E' ? 5 : 0),
        ]);
    }

    /**
     * Validasi data sebelum masuk ke Database
     */
    public function rules(): array
    {
        return [
            'kategori'      => 'required|in:TWK,TIU,TKP,twk,tiu,tkp',
            'pertanyaan'    => 'required',
            'opsi_a'        => 'required',
            'opsi_b'        => 'required',
            'opsi_c'        => 'required',
            'opsi_d'        => 'required',
            'opsi_e'        => 'required',
            'jawaban_benar' => 'required|in:A,B,C,D,E,a,b,c,d,e',
        ];
    }
}