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
        'content',
        'type',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'option_e',
        'point_a', // Poin TKP
        'point_b',
        'point_c',
        'point_d',
        'point_e',
        'answer',      // Kunci Jawaban
        'explanation', // Pembahasan
    ];

    /**
     * Relasi ke Tryout
     */
    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }
}