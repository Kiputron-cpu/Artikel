<?php

namespace App\Http\Controllers\Artikel;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function createLike($id)
    {
        $user_id = Auth::user()->id;
        $create = Like::create([
            'user_id' => $user_id,
            'artikel_id' => $id
        ]);
        $artikels = Artikel::findOrFail($id);
        $artikel = like::where('artikel_id', $id)->get();
        $countlike = $artikel->count();
        $artikels->update([
            'like' => $countlike
        ]);
        if (!$create) return response()->json(['message' => 'gagl'], 402);
        return response()->json(['message' => 'success', 'data' => $countlike], 200);
    }
    public function deletelike($id)
    {
        $user_id = Auth::user()->id;
        $artikelLike = Like::where('artikel_id', $id)->where('user_id', $user_id)->delete();

        if (!$artikelLike) return response()->json(['message' => 'gagl'], 402);
        $artikel = like::where('artikel_id', $id)->get();
        $countlike = $artikel->count();
        $artikels = Artikel::findOrFail($id);
        $artikels->update([
            'like' => $countlike
        ]);
        return response()->json(['Message' => 'success'], 200);
    }
}
