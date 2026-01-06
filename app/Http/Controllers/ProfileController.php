<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update profile information or picture (Optional expansion)
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|max:5120',
        ]);

        // Update name in profile
        if ($user->profile) {
            $user->profile->update([
                'full_name' => $request->full_name
            ]);
        }

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            if ($user->profile && $user->profile->profile_picture) {
                Storage::disk('public')->delete($user->profile->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile->update(['profile_picture' => $path]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }
}
