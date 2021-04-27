<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;
    protected $fillable = ['artikel_id', 'user_id', 'komentar'];

    public function artikels()
    {
        return $this->belongsTo(Artikel::class, 'artikel_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
