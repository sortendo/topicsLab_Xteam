<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // deleted_atの値
        $deleted_at = DB::table('users')
            ->where('email', $request->email)
            ->exists();

        if (Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'success'
            ], 200);
        } elseif ($deleted_at) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }
}
