<?php

namespace App\Http\Controllers\Artikel;

use App\Models\User;
use App\Models\Artikel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\view;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ArtikelController extends Controller
{
    public function show()
    {
        $artikel = Artikel::with('users', 'kategories')->orderByDesc('created_at')->get();
        return response()->json($artikel, 200);
    }
    public function showArtikel($slug)
    {
        $artikel = Artikel::with('users', 'kategories', 'komentars.users')->where('slug', $slug)->first();
        $user_id = Auth::user()->id;
        if ($user_id == null) {
            $response = [
                'message' => 'show artikel',
                'data' => $artikel
            ];
            return response()->json($response, 200);
        } else {
            $artikel_id = $artikel->id;
            try {
                $viewCek = view::where('user_id', $user_id)->where('artikel_id', $artikel_id)->first();
                $response = [
                    'message' => 'show artikel',
                    'data' => $artikel
                ];
                if ($viewCek == null) {
                    view::create([
                        'user_id' => $user_id,
                        'artikel_id' => $artikel_id
                    ]);
                    // menghitung view
                    $view = view::where('artikel_id', $artikel_id)->get();
                    $countView = $view->count();
                    $artikel->update(['view' => $countView]);
                    $response = [
                        'message' => 'show artikel with add view',
                        'data' => $artikel
                    ];
                    return response()->json($response, 200);
                }
                return response()->json($response, 200);
            } catch (QueryException $e) {
                return response()->json($e->error);
            }
        }
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required|min:5',
            'img' => $request->img ? 'image|mimes:jpeg,png,jpg,gif' : ''
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        $artikel = Artikel::create([
            'user_id' => Auth::user()->id,
            'slug' => Str::slug($request->title),
            'title' => $request->title,
            'body' => $request->body,
            'img' => $request->img ? request()->file('img')->store('images/artikel') : null
        ]);
        $artikel->kategories()->sync($request->kategori);
        return response()->json('success', 201);
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::with('kategories')->where('id', $id)->where('user_id', Auth::user()->id)->get();
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required|min:5',
            'img' => $request->img ? 'image|mimes:jpeg,png,jpg,gif' : ''
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        if ($request->img) {
            Storage::delete($artikel[0]->img);
            $img = request()->file('img')->store('images/artikel');
        } else if ($artikel[0]->img) {
            $img = $artikel[0]->img;
        } else {
            $img = null;
        }
        $artikel[0]->update([
            'user_id' => Auth::user()->id,
            'slug' => Str::slug($request->title),
            'title' => $request->title,
            'body' => $request->body,
            'img' => $img
        ]);
        $artikel[0]->kategories()->sync($request->kategori);
        return response()->json(['success' => 'Artikel was updated'], 200);
    }
    public function destroy($id)
    {
        $artikel = Artikel::where('id', $id)->where('user_id', Auth::user()->id)->get();
        Storage::delete($artikel[0]->img);
        Artikel::with('kategories')->where('id', $id)->where('user_id', Auth::user()->id)->delete();
        return response()->json(['message' => 'Artikel was Deleted'], 200);
    }
    public function showMyArtikel()
    {
        $artikel = Artikel::with('kategories')->where('user_id', Auth::user()->id)->get();
        return response()->json($artikel, 200);
    }
    public function showMyArtikelSpecific($slug)
    {
        $artikel = Artikel::with('kategories')->where('slug', $slug)->where('user_id', Auth::user()->id)->get();
        return response()->json($artikel, 200);
    }
}
