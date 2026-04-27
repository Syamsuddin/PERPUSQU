<?php

namespace App\Modules\Profile\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load('roles');
        return view('modules.profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|min:3|max:150',
            'email' => "required|email:rfc|max:150|unique:users,email,{$user->id}",
        ]);

        $user->update($request->only('name', 'email'));

        activity('profile')->causedBy($user)->log('Profil diperbarui');

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function editPassword()
    {
        return view('modules.profile.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:8|max:255',
            'new_password' => 'required|string|min:8|max:255|different:current_password|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        activity('profile')->causedBy($user)->log('Password diubah');

        return back()->with('success', 'Password berhasil diubah.');
    }
}
