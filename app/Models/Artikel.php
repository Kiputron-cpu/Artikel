<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'body',
        'img',
        'like',
        'view'
    ];

    public function kategories()
    {
        return $this->belongsToMany(kategori::class);
    }
    public function user_bookmark()
    {
        return $this->belongsToMany(User::class);
    }
    public function komentars()
    {
        return $this->hasMany(Komentar::class, 'artikel_id');
    }
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'artikel_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function likes()
    {
        return $this->hasMany(like::class);
    }
}
