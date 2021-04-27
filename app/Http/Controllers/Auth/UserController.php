<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|min:4|max:24',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $regis = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            $response = [
                'message' => 'User Registered',
                'data' => $regis
            ];
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'failed' . $e->errorInfo
            ]);
        }
    }
    public function login(Request $request)
    {
        $auth_data = $request->only('email', 'password');

        try {
            $data_user = User::where('email', $request->email)->first();

            $payload = ['guard' => 'user'];
            if (!$token = auth()->claims($payload)->attempt($auth_data))
                return response()->json('data tidak ditemukan', Response::HTTP_UNAUTHORIZED);
            $response = [
                'message' => 'Login Success',
                'data' => $data_user,
                'token' => $token,
                'expires_in' => Auth::factory()->getTTL() * 60
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validate = Validator::make($request->all(), [
            'name' => 'required|min:4|max:24',
            'img' => $request->img ? 'image|mimes:jpeg,png,jpg,gif' : ''
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        if ($request->img) {
            Storage::delete($user->img);
            $img = request()->file('img')->store('images/user');
        } else if ($user->img) {
            $img = $user->img;
        } else {
            $img = null;
        }
        $user->update([
            'name' => $request->name,
            'img' => $img,
        ]);
        $response = [
            'message' => 'user was updated',
            'data' => $user
        ];
        return response()->json($response, 200);
    }
}
