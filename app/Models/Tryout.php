<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tryout extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'duration', 
        'is_published', 
        'type'
    ];

    /**
     * Relasi ke Soal.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Relasi ke Pengerjaan Ujian.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}