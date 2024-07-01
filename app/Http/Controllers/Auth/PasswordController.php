<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

// バリデーションのために追加
use App\Http\Requests\UpdatePasswordRequest;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    // パスワードリクエストが来た場合
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated()['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
