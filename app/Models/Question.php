<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment)
     */
    protected $fillable = [
        'tryout_id',
    'subject',
    'content', // Ubah dari question_text ke content
    'image',   // Gunakan 'image' sesuai error log Anda (bukan image_path)
    'option_a',
    'option_b',
    'option_c',
    'option_d',
    'option_e',
    'correct_answer',
    'explanation',
    'points_a',
    'points_b',
    'points_c',
    'points_d',
    'points_e',
    ];

    /**
     * Relasi ke Tryout
     */
    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }
}