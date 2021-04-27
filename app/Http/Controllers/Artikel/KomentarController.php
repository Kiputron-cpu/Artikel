<?php

namespace App\Http\Controllers\Artikel;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function createKomentar(Request $request, $slug)
    {
        $artikel = Artikel::where('slug', $slug)->first();
        $artikel_id = $artikel->id;
        $user_id = Auth::user()->id;
        $komentar = Komentar::create([
            'artikel_id' => $artikel_id,
            'user_id' => $user_id,
            'komentar' => $request->komentar
        ]);
        $response = [
            'message' => 'komentar was created',
            'data' => $komentar
        ];
        return response()->json($response, 200);
    }
    public function deleteKomentar($id)
    {
        $user_id = Auth::user()->id;
        $delete  = Komentar::where('id', $id)->where('user_id', $user_id)->delete();
        $response = [
            'message' => 'komentar was deleted'
        ];
        return response()->json($response, 200);
    }
}
