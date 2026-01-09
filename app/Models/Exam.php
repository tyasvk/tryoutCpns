<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Menambahkan semua kolom skor dan status agar sinkron dengan fitur penilaian.
     */
    protected $fillable = [
        'user_id',
        'tryout_id',
        'status',          // pending, ongoing, completed
        'answers',         // Menyimpan JSON jawaban peserta
        'score_twk',
        'score_tiu',
        'score_tkp',
        'total_score',
        'is_passed',       // Status kelulusan berdasarkan Passing Grade
        'started_at',
        'completed_at',
    ];

    /**
     * Casting tipe data kolom.
     * Penting: 'answers' harus di-cast ke array agar Laravel otomatis
     * mengubahnya dari format JSON di database menjadi Array PHP.
     */
    protected $casts = [
        'answers' => 'array',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score_twk' => 'integer',
        'score_tiu' => 'integer',
        'score_tkp' => 'integer',
        'total_score' => 'integer',
    ];

    /**
     * Relasi ke User (Peserta)
     * Menghubungkan hasil ujian dengan siapa yang mengerjakannya.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Tryout (Paket Soal)
     * Menghubungkan hasil ujian dengan paket soal yang dipilih.
     */
    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }

    /**
     * Scope untuk mengambil ujian yang sudah selesai.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope untuk mengambil ujian yang sedang berlangsung.
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }
}