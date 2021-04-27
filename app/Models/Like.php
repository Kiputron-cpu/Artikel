<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'artikel_id'];

    public function artikels()
    {
        return $this->BelongsTo(Artikel::class, 'artikel_id');
    }
    public function users()
    {
        return $this->BelongsTo(User::class, 'user_id');
    }
}
