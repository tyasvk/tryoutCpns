<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    protected $fillable = ['name', 'duration', 'is_free', 'price'];

public function questions() {
    return $this->hasMany(Question::class);
}
}
