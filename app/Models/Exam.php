<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tryout_id',
        'score_twk',
        'score_tiu',
        'score_tkp',
        'total_score',
        'status',
        'started_at',
        'completed_at',
        'answers',
        'violations'
    ];

    /**
     * Casting otomatis tipe data
     */
    protected $casts = [
        'answers' => 'array',       // Penting agar JSON di database terbaca sebagai Array di PHP
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi: Sebuah Ujian dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Sebuah Ujian mengerjakan satu paket Tryout
     * (INI YANG MENYEBABKAN ERROR TADI)
     */
    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }
}