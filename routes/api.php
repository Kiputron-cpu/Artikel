<?php

use App\Http\Controllers\Artikel\ArtikelController;
use App\Http\Controllers\Artikel\BookmarkController;
use App\Http\Controllers\Artikel\KomentarController;
use App\Http\Controllers\Artikel\LikeController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::get('/my-artikel', [ArtikelController::class, 'showMyArtikel']);
Route::prefix('artikel')->group(function () {
    Route::get('/', [ArtikelController::class, 'show']);
    Route::get('/show/{slug}', [ArtikelController::class, 'showArtikel']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    Route::middleware('auth:user')->group(function () {
        // bookmark
        Route::get('/bookmark', [BookmarkController::class, 'show']);
        Route::post('/bookmark/{id}', [BookmarkController::class, 'createBookmark']);
        Route::delete('/bookmark/{id}', [BookmarkController::class, 'deleteBookmark']);
        // like
        Route::post('/like/{id}', [LikeController::class, 'createlike']);
        Route::delete('/like/{id}', [LikeController::class, 'deletelike']);

        //komentar
        Route::post('/komentar/{slug}', [KomentarController::class, 'createKomentar']);
        Route::delete('/komentar/{id}', [KomentarController::class, 'deleteKomentar']);
        // 
        Route::get('/my-artikel', [ArtikelController::class, 'showMyArtikel']);
        Route::get('/my-artikel/{slug}', [ArtikelController::class, 'showMyArtikelSpecific']);
        Route::post('/create-artikel', [ArtikelController::class, 'store']);
        Route::put('/update-artikel/{id}', [ArtikelController::class, 'update']);
        Route::delete('/delete-artikel/{id}', [ArtikelController::class, 'destroy']);

        // edit profile
        Route::put('/edit-profile/{id}', [UserController::class, 'update']);
    });
});
