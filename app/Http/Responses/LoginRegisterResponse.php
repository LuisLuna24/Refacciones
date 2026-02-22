<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

class LoginRegisterResponse implements LoginResponse, RegisterResponse
{
    public function toResponse($request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('home');
        }

        return match ($user->type_user_id) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('admin.dashboard'),
            3 => redirect()->route('home'),
            default => abort(code: 404),
        };
    }
}
