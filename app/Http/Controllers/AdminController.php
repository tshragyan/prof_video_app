<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index ()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.signIn');
        } else {
            return redirect()->route('admin.users.index');
        }
    }

    public function signIn()
    {
        if (Auth::check()) {
            return redirect()->route('admin.users.index');
        }

        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $data = $request->validated();

        /** @var User $user */
        $user = User::query()
            ->where('email', '=', $data['email'])
            ->first();

        if (!$user) {
            return redirect()->route('admin.signIn')->withErrors(['email' => 'Invalid Email']);
        }

        if (!Hash::check($data['password'], $user->password)) {
            return redirect()->route('admin.signIn')->withErrors(['password' => 'Invalid Password']);
        }

        Auth::login($user);

        return redirect()->route('admin.users.index');
    }

    public function logout()
    {
        \auth()->logout();
        return redirect()->route('admin.signIn');
    }
}
