<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

use App\Models\RolePermission;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = auth()->user();
        $is_admin = $user->isSuperAdmin();

        if ($is_admin) {
            $rolePermissions = RolePermission::all()->groupBy('role');
            return view('profile.edit', compact('user', 'rolePermissions'));
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Update role permissions (Bulk update)
     */
    public function updatePermissions(Request $request)
    {
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin()) {
            abort(403);
        }

        $roles = ['office_in_charge', 'hs', 'dm', 'bm', 'rm', 'ro'];
        $permissionsBatch = $request->get('permissions', []);

        foreach ($roles as $role) {
            // First reset all to false for this role to handle unchecked checkboxes
            RolePermission::where('role', $role)->update(['is_enabled' => false]);

            // Then enable the ones that were checked for this role
            if (isset($permissionsBatch[$role])) {
                foreach ($permissionsBatch[$role] as $key => $value) {
                    RolePermission::where('role', $role)
                        ->where('permission_key', $key)
                        ->update(['is_enabled' => true]);
                }
            }
        }

        return back()->with('success', 'Role permissions updated successfully!');
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
