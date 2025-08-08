<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['login', 'password']);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Неверные учетные данные'], 401);
            }

            return back()->withErrors(['login' => 'Неверные учетные данные']);
        }

        if (! $request->wantsJson() && ! $request->is('api/*')) {
            $request->session()->regenerate();
        }

        $user = Auth::user()->load('position');

        if ($request->wantsJson() || $request->is('api/*')) {
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Успешный вход',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'position' => $user->position->name ?? null,
                ],
                'token' => $token,
            ]);
        }

        return redirect()->route('booking.index');
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }
        if (! $request->wantsJson() && ! $request->is('api/*')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login');
        }

        return response()->json(['message' => 'Успешный выход']);
    }
}
