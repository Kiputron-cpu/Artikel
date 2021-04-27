<?php

namespace App\Http\Controllers\Artikel;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function show()
    {
        $user_id = Auth::user()->id;
        $data = User::with('bookmarks', 'bookmarks.artikels')->where('id', $user_id)->get();
        $response = [
            "message" => 'show bookmarks',
            "data" => $data
        ];
        $d = "lsadasdad";
        return response()->json($response);
    }
    public function createBookmark($id)
    {
        $user_id = Auth::user()->id;
        $data = Bookmark::create([
            'user_id' => $user_id,
            'artikel_id' => $id
        ]);
        $response = [
            "message" => 'success add bookmark',
            "data" => $data
        ];
        return response()->json($response, 200);
    }

    public function deleteBookmark($id)
    {
        $user_id = Auth::user()->id;
        Bookmark::where('user_id', $user_id)->where('artikel_id', $id)->delete();
        $response = ['message' => 'bookmark was deleted'];
        return response()->json($response, 200);
    }
}
