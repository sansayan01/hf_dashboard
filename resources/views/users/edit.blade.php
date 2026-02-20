@extends('layouts.app')

@section('title', 'Edit Member')
@section('header_title', 'Edit ' . $user->profile->full_name)

@section('css')
<style>
    /* 3D Dial Toggle Styling */
    .dial-container {
        display: inline-flex !important;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
        gap: 2px !important;
    }

    .dial-label {
        margin: 0 !important;
        padding: 0 !important;
        cursor: pointer;
    }

    .dial-input {
        display: none !important;
    }

    .dial-btn {
        width: 65px;
        height: 36px;
        line-height: 36px;
        text-align: center;
        font-size: 10px;
        font-weight: 900;
        border-radius: 10px;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .dial-input-on:checked + .dial-btn-on {
        background: #22c55e;
        color: white;
        box-shadow: 
            0 4px 0 #15803d,
            0 8px 20px rgba(34, 197, 94, 0.3);
        transform: translateY(-2px);
    }

    .dial-input-off:checked + .dial-btn-off {
        background: #ef4444;
        color: white;
        box-shadow: 
            0 4px 0 #b91c1c,
            0 8px 20px rgba(239, 68, 68, 0.3);
        transform: translateY(-2px);
    }

    .dial-btn:active {
        transform: translateY(2px) !important;
        box-shadow: none !important;
    }
</style>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h3 class="font-bold text-xl text-slate-800">Edit Member Details</h3>
                <p class="text-sm text-slate-500 mt-1">Update the profile and professional information for
                    {{ $user->employee_id }}.</p>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-10">
                @csrf
                @method('PUT')

                <!-- Section: Administrative Role Management (Admins Only) -->
                <!-- Section: Administrative Role Management (Admins Only) -->
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge())
                <div class="mb-8">
                    <div class="flex items-center space-x-2 mb-6 text-accent">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Role & Hierarchy Management</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Role/Designation</label>
                            <select name="designation" id="designation-select"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                @foreach($allDesignations ?? [] as $val => $label)
                                    <option value="{{ $val }}" {{ $user->designation == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="post-selection-wrapper" class="{{ $user->designation === 'super_admin' ? '' : 'hidden' }}">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Assign Post (Manual Entry)</label>
                            <input type="text" name="post" id="post-input" value="{{ old('post', $user->post) }}" placeholder="e.g. Secretary, Vice President"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                        </div>
                        <div id="parent-selection-wrapper">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Reports To (Parent)</label>
                            <select name="parent_id" id="parent-select"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Parent</option>
                                @if($user->parent)
                                    <option value="{{ $user->parent_id }}" selected>{{ $user->parent->profile->full_name ?? $user->parent->employee_id }} (Current)</option>
                                @endif
                            </select>
                        </div>
                        
                        <!-- Camp Selection (Pharmacist Only) -->
                        <div id="camp-selection-wrapper" class="hidden">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Assign Camp Location</label>
                            <select name="camp_id" id="camp-select"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Camp</option>
                                @if(isset($camps))
                                    @foreach($camps as $camp)
                                        <option value="{{ $camp->id }}" {{ old('camp_id', $user->camp_id) == $camp->id ? 'selected' : '' }}>
                                            {{ $camp->name }} {{ $camp->location ? '('.$camp->location.')' : '' }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Pharmacist will be assigned to this camp location.</p>
                        </div>
                    </div>

                    <!-- Office In-Charge Upline Selection (Only for Super Admin) -->
                    @if(auth()->user()->isSuperAdmin())
                    <div id="office-in-charge-upline-section" class="hidden mt-6">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                            <p class="text-sm text-blue-800 font-semibold">
                                <svg class="inline w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Office In-Charge / Camp Organizer Configuration: Select the upline this user will represent
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Upline's Designation</label>
                                <select name="upline_designation" id="upline-designation-select"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                    <option value="">Select Upline Designation</option>
                                    <option value="super_admin" {{ ($user->upline_designation ?? old('upline_designation')) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="hs" {{ ($user->upline_designation ?? old('upline_designation')) == 'hs' ? 'selected' : '' }}>Head of State (HS)</option>
                                    <option value="dm" {{ ($user->upline_designation ?? old('upline_designation')) == 'dm' ? 'selected' : '' }}>District Manager (DM)</option>
                                    <option value="bm" {{ ($user->upline_designation ?? old('upline_designation')) == 'bm' ? 'selected' : '' }}>Block Manager (BM)</option>
                                    <option value="rm" {{ ($user->upline_designation ?? old('upline_designation')) == 'rm' ? 'selected' : '' }}>Relationship Manager (RM)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Select Upline Person</label>
                                <select name="upline_id" id="upline-person-select"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                    <option value="">Select Upline Designation First</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <p class="text-[10px] text-accent font-bold mt-4 uppercase italic">Warning: changing designation requires re-assigning valid parent.</p>
                </div>
                @endif

                <!-- Section: Account Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Account Credentials</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 id-label">{{ in_array($user->designation, ['office_in_charge', 'staff', 'camp_organizer']) ? 'Employee ID' : 'Volunteer ID' }}</label>
                            @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge())
                                <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <p class="text-[10px] text-accent font-bold mt-1 uppercase italic">Admin changes allowed</p>
                            @else
                                <input type="text" value="{{ $user->employee_id }}" disabled
                                    class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 outline-none">
                                <p class="text-[10px] text-bodydark font-bold mt-1 uppercase italic">{{ in_array($user->designation, ['office_in_charge', 'staff', 'camp_organizer']) ? 'Employee ID' : 'Volunteer ID' }} cannot be changed</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                        </div>

                        <!-- Password Reset Section (Optional) -->
                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge() || auth()->id() === $user->id)
                        <div class="col-span-1 md:col-span-2 border-t border-slate-100 pt-6 mt-2">
                             <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
                                <h5 class="text-sm font-bold text-orange-800 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-11V7a4 4 0 11-8 0v4h8z" /></svg>
                                    Reset Password (Optional)
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="relative">
                                        <label class="block text-xs font-bold text-slate-600 mb-1.5">New Password</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="new_password" placeholder="Leave blank to keep current"
                                                class="w-full pl-4 pr-10 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all outline-none text-sm">
                                            <button type="button" onclick="togglePassword('new_password')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-accent">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Confirm Password</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="confirm_password" placeholder="Confirm new password"
                                                class="w-full pl-4 pr-10 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all outline-none text-sm">
                                            <button type="button" onclick="togglePassword('confirm_password')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-accent">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section: Per-User Permission Overrides (Admins Only) -->
                @if(auth()->user()->isSuperAdmin())
                <div id="per-user-permissions-section" class="{{ in_array($user->designation, ['hs', 'dm', 'rm']) ? '' : 'hidden' }}">
                    <div class="flex items-center space-x-2 mb-6 text-accent">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Per-User Permission Overrides</h4>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <p class="text-xs text-blue-800">
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            These permissions take precedence even if role-level permissions are disabled.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="group flex items-center justify-between p-5 bg-slate-50 border border-slate-100 rounded-3xl cursor-pointer hover:border-accent/30 hover:bg-white transition-all shadow-sm">
                            <div class="flex-1 pr-4">
                                <p class="text-xs font-black text-slate-800 uppercase tracking-widest mb-1">CAN CREATE USERS</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Grant Access</p>
                            </div>
                            <x-dial-toggle 
                                name="can_create_users" 
                                id="can_create_users_toggle"
                                :checked="old('can_create_users', $user->can_create_users)" 
                            />
                        </label>

                        <label class="group flex items-center justify-between p-5 bg-slate-50 border border-slate-100 rounded-3xl cursor-pointer hover:border-accent/30 hover:bg-white transition-all shadow-sm">
                            <div class="flex-1 pr-4">
                                <p class="text-xs font-black text-slate-800 uppercase tracking-widest mb-1">CAN EDIT USER DETAILS</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Grant Access</p>
                            </div>
                            <x-dial-toggle 
                                name="can_edit_user_details" 
                                id="can_edit_user_details_toggle"
                                :checked="old('can_edit_user_details', $user->can_edit_user_details)" 
                            />
                        </label>
                    </div>
                </div>
                @endif

                <!-- Section: Personal Profile -->
                <div>
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Personal Profile</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->profile->full_name) }}"
                                required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone_number"
                                value="{{ old('phone_number', $user->profile->phone_number) }}" required maxlength="10"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Blood Group</label>
                            <select name="blood_group"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <option value="{{ $group }}" {{ old('blood_group', $user->profile->blood_group) == $group ? 'selected' : '' }}>{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Profile Picture</label>
                            <div class="flex flex-col sm:flex-row items-center gap-6 p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-dashed border-slate-300 dark:border-white/10">
                                <div class="relative w-24 h-24 flex-shrink-0 group cursor-pointer" onclick="handlePreviewClick('profile_picture_input', 'profile-preview')">
                                    @if($user->profile->profile_picture)
                                        <img id="profile-preview" src="{{ $user->profile->getProfilePictureUrl() }}"
                                            class="w-full h-full rounded-2xl object-cover border-2 border-accent/20 shadow-lg transition-all group-hover:scale-105">
                                    @else
                                        <div class="initials-placeholder w-full h-full rounded-2xl bg-accent/10 border-2 border-accent/20 flex items-center justify-center text-accent text-2xl font-bold transition-all group-hover:scale-105">
                                            {{ substr($user->profile->full_name ?? $user->employee_id, 0, 1) }}
                                        </div>
                                        <img id="profile-preview" src="#" alt="Preview" class="hidden w-full h-full rounded-2xl object-cover border-2 border-accent/20 shadow-lg transition-all group-hover:scale-105">
                                    @endif
                                    <div class="absolute -bottom-2 -right-2 bg-accent text-white p-1.5 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*"
                                        class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus:ring-4 focus:ring-accent/10 transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-accent/10 file:text-accent hover:file:bg-accent/20 cursor-pointer"
                                        onchange="initCropper(this, document.getElementById('profile-preview'))">
                                    <p class="mt-2 text-[11px] text-slate-500 dark:text-slate-400 font-medium">Capture a new picture or upload a file. JPG/PNG, Max 10MB.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Detailed Address</label>
                            <textarea name="address" required rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">{{ old('address', $user->profile->address) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">State</label>
                            <select name="state" id="state-select" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select State</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">District</label>
                            <select name="district" id="district-select" required disabled
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none disabled:opacity-50">
                                <option value="">Select District</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Block</label>
                            <select name="block" id="block-select" required disabled
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none disabled:opacity-50">
                                <option value="">Select Block</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Gram Panchayat</label>
                            <select name="gram_panchayat" id="gp-select" required disabled
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none disabled:opacity-50">
                                <option value="">Select GP</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pin Code</label>
                            <input type="text" name="pin_code" value="{{ old('pin_code', $user->profile->pin_code) }}"
                                required maxlength="6"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Aadhaar Number</label>
                            <input type="text" name="aadhaar_number"
                                value="{{ old('aadhaar_number', $user->profile->aadhaar_number) }}" required maxlength="12"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">PAN Number</label>
                            <input type="text" name="pan_number" value="{{ old('pan_number', $user->profile->pan_number) }}"
                                maxlength="10"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="validatePAN(this)">
                        </div>
                    </div>
                </div>

                <!-- Section: Bank Account -->
                <div>
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Banking Information</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bank Name</label>
                            <select name="bank_name" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Bank</option>
                                @php
                                    $selectedBank = old('bank_name', $user->bankDetails->bank_name ?? '');
                                @endphp
                                @foreach([
                                    'State Bank of India', 'HDFC Bank', 'ICICI Bank', 'Axis Bank', 'Punjab National Bank',
                                    'Bank of Baroda', 'Canara Bank', 'Union Bank of India', 'Bank of India', 'Indian Bank',
                                    'Central Bank of India', 'Indian Overseas Bank', 'UCO Bank', 'Bank of Maharashtra',
                                    'Punjab & Sind Bank', 'IDBI Bank', 'Yes Bank', 'Kotak Mahindra Bank', 'Federal Bank',
                                    'IndusInd Bank', 'South Indian Bank', 'Karnataka Bank', 'City Union Bank', 'Karur Vysya Bank',
                                    'Tamilnad Mercantile Bank', 'RBL Bank', 'Bandhan Bank', 'IDFC First Bank', 'AU Small Finance Bank',
                                    'Equitas Small Finance Bank', 'India Post Payments Bank', 'Paytm Payments Bank',
                                    'Airtel Payments Bank', 'Jio Payments Bank', 'Rajnagar Samabaya Krishi Unnayan Samity Ltd', 'Bangiya Gramin Vikash Bank'
                                ] as $bank)
                                    <option value="{{ $bank }}" {{ $selectedBank == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Account Number</label>
                            <input type="text" name="account_number"
                                value="{{ old('account_number', $user->bankDetails->account_number ?? '') }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">IFSC Code</label>
                            <input type="text" name="ifsc_code"
                                value="{{ old('ifsc_code', $user->bankDetails->ifsc_code ?? '') }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')">
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-10 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('users.show', $user->id) }}"
                        class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition">Cancel</a>
                    <button type="submit"
                        class="px-10 py-4 bg-accent text-white font-bold rounded-xl shadow-xl shadow-accent/20 hover:shadow-md hover:-translate-y-0.5 transition-all">
                        Update Member Details
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/locations.js') }}"></script>
    <script>
        function initStateLogic() {
            const stateSelect = document.getElementById('state-select');
            const districtSelect = document.getElementById('district-select');
            const blockSelect = document.getElementById('block-select');
            const gpSelect = document.getElementById('gp-select');

            // Current Values
            const currentState = @json(old('state', $user->profile->state ?? ''));
            const currentDistrict = @json(old('district', $user->profile->district ?? ''));
            const currentBlock = @json(old('block', $user->profile->block ?? ''));
            const currentGP = @json(old('gram_panchayat', $user->profile->gram_panchayat ?? ''));

            // 1. Populate States (Improved robustness)
            function populateStates() {
                if (!window.locationData) {
                    console.warn("locationData not found, retrying in 100ms...");
                    setTimeout(populateStates, 100);
                    return;
                }
                
                stateSelect.innerHTML = '<option value="">Select State</option>';
                for (const state in window.locationData) {
                    const option = new Option(state, state);
                    if (state === currentState) option.selected = true;
                    stateSelect.add(option);
                }
                
                // Initial trigger if we have a current state
                if (currentState) {
                    updateDistricts(currentState, currentDistrict);
                }
            }

            populateStates();

            // Admin Role Management Logic
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge())
            const designationSelect = document.getElementById('designation-select');
            const parentSelect = document.getElementById('parent-select');
            const potentialParents = @json($potentialParents ?? []);
            const currentParentId = "{{ $user->parent_id }}";

            @if(auth()->user()->isSuperAdmin())
            const officeInChargeUplineSection = document.getElementById('office-in-charge-upline-section');
            const uplineDesignationSelect = document.getElementById('upline-designation-select');
            const uplinePersonSelect = document.getElementById('upline-person-select');
            const potentialUplines = @json($potentialUplines ?? []);
            const currentUplineId = "{{ $user->upline_id }}";

            // Handle upline designation change
            uplineDesignationSelect.addEventListener('change', function() {
                const uplineDesignation = this.value;
                uplinePersonSelect.innerHTML = '<option value="">Select Person</option>';

                if (uplineDesignation && potentialUplines[uplineDesignation]) {
                    potentialUplines[uplineDesignation].forEach(upline => {
                        const name = upline.profile ? upline.profile.full_name : upline.email;
                        const option = new Option(`${name} (${upline.employee_id})`, upline.id);
                        if (upline.id == currentUplineId) {
                            option.selected = true;
                        }
                        uplinePersonSelect.add(option);
                    });
                }
                // Trigger change to sync parent if OIC
                uplinePersonSelect.dispatchEvent(new Event('change'));
            });

            // Handle Upline Person change -> Sync to Parent Select
            uplinePersonSelect.addEventListener('change', function() {
                if (designationSelect.value === 'office_in_charge' || designationSelect.value === 'camp_organizer') {
                    const selectedOption = this.options[this.selectedIndex];
                    parentSelect.innerHTML = '';
                    if (this.value) {
                        parentSelect.add(new Option(selectedOption.text, selectedOption.value, true, true));
                    } else {
                        parentSelect.add(new Option("Auto-assigned from Upline", ""));
                    }
                }
            });
            
            // Trigger initial load if set
            if (uplineDesignationSelect && uplineDesignationSelect.value) {
                uplineDesignationSelect.dispatchEvent(new Event('change'));
            }
            @endif

            // Function to populate parents based on designation
            function updateParents(designation) {
                const postWrapper = document.getElementById('post-selection-wrapper');
                if (postWrapper) {
                    if (designation === 'super_admin') {
                        postWrapper.classList.remove('hidden');
                    } else {
                        postWrapper.classList.add('hidden');
                        document.getElementById('post-input').value = '';
                    }
                }

                const parentWrapper = document.getElementById('parent-selection-wrapper');
                const campWrapper = document.getElementById('camp-selection-wrapper');
                const campSelect = document.getElementById('camp-select');

                if (['office_in_charge', 'camp_organizer'].includes(designation)) {
                    @if(auth()->user()->isSuperAdmin())
                        if (officeInChargeUplineSection) officeInChargeUplineSection.classList.remove('hidden');
                    @endif
                    if (parentWrapper) parentWrapper.classList.remove('hidden');
                    if (parentSelect) {
                        parentSelect.innerHTML = '<option value="">Auto-assigned from Upline</option>';
                        parentSelect.disabled = true;
                        const parentDiv = parentSelect.closest('div');
                        if (parentDiv) parentDiv.classList.add('opacity-50');
                    }
                } else if (designation === 'staff') {
                    if (parentWrapper) parentWrapper.classList.add('hidden');
                    if (campWrapper) campWrapper.classList.remove('hidden');
                    if (campSelect) campSelect.required = true;
                    @if(auth()->user()->isSuperAdmin())
                        if (officeInChargeUplineSection) officeInChargeUplineSection.classList.add('hidden');
                    @endif
                } else {
                    @if(auth()->user()->isSuperAdmin())
                        if (officeInChargeUplineSection) officeInChargeUplineSection.classList.add('hidden');
                    @endif
                    if (parentWrapper) parentWrapper.classList.remove('hidden');
                    if (campWrapper) campWrapper.classList.add('hidden');
                    if (campSelect) campSelect.required = false;

                    const targetMap = {
                        'hs': 'super_admin',
                        'dm': 'hs',
                        'bm': 'dm',
                        'rm': 'bm',
                        'ro': 'rm'
                    };

                    const targetParentDesignation = targetMap[designation];
                    if (parentSelect) {
                        parentSelect.innerHTML = '<option value="">Select Parent</option>';
                        parentSelect.disabled = false;
                        const parentDiv = parentSelect.closest('div');
                        if (parentDiv) parentDiv.classList.remove('opacity-50');

                        if (targetParentDesignation && potentialParents[targetParentDesignation]) {
                            potentialParents[targetParentDesignation].forEach(parent => {
                                const name = parent.profile ? parent.profile.full_name : parent.email;
                                const option = new Option(`${name} (${parent.employee_id})`, parent.id);
                                if (parent.id == currentParentId) {
                                    option.selected = true;
                                }
                                parentSelect.add(option);
                            });
                        }
                    }
                }

                // Show/Hide Per-User Permissions Section
                const perUserPermissionsSection = document.getElementById('per-user-permissions-section');
                if (perUserPermissionsSection) {
                    if (['hs', 'dm', 'rm'].includes(designation)) {
                        perUserPermissionsSection.classList.remove('hidden');
                    } else {
                        perUserPermissionsSection.classList.add('hidden');
                        // Uncheck when hidden to avoid accidental permissions? 
                        // Actually, better to just hide it. The backend already handles saving only if Admin.
                    }
                }
            }

            if (designationSelect) {
                designationSelect.addEventListener('change', function() {
                    const designation = this.value;
                    
                    // Auto-update ID when designation changes
                    const idInput = document.querySelector('input[name="employee_id"]');
                    const idLabel = document.querySelector('.id-label');
                    
                    if (idInput && designation) {
                        // Update Label Text
                        if (['office_in_charge', 'staff', 'camp_organizer'].includes(designation)) {
                            if (idLabel) idLabel.innerText = 'Employee ID';
                        } else {
                            if (idLabel) idLabel.innerText = 'Volunteer ID';
                        }

                        fetch(`{{ route('users.next-id') }}?designation=${designation}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.id) {
                                    idInput.value = data.id;
                                }
                            })
                            .catch(error => console.error('Error fetching ID:', error));
                    }

                    updateParents(designation);
                });
                
                // Initial run to populate compatible list if not changed
                // Use setTimeout to ensure other scripts don't conflict? No need.
                // But we want to preserve current selection on load if it matches logic.
                updateParents(designationSelect.value);
            }
            @endif


            function updateDistricts(state, selectedDistrict = null) {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                blockSelect.innerHTML = '<option value="">Select Block</option>';
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (state && window.locationData[state]) {
                    districtSelect.disabled = false;
                    for (const district in window.locationData[state]) {
                        const option = new Option(district, district);
                        if (district === selectedDistrict) option.selected = true;
                        districtSelect.add(option);
                    }
                    if (selectedDistrict) updateBlocks(state, selectedDistrict, currentBlock);
                } else {
                    districtSelect.disabled = true;
                    blockSelect.disabled = true;
                    gpSelect.disabled = true;
                }
            }

            function updateBlocks(state, district, selectedBlock = null) {
                blockSelect.innerHTML = '<option value="">Select Block</option>';
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (district && window.locationData[state][district]) {
                    blockSelect.disabled = false;
                    for (const block in window.locationData[state][district]) {
                        const option = new Option(block, block);
                        if (block === selectedBlock) option.selected = true;
                        blockSelect.add(option);
                    }
                    if (selectedBlock) updateGPs(state, district, selectedBlock, currentGP);
                } else {
                    blockSelect.disabled = true;
                    gpSelect.disabled = true;
                }
            }

            function updateGPs(state, district, block, selectedGP = null) {
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (block && window.locationData[state][district][block]) {
                    gpSelect.disabled = false;
                    const gps = window.locationData[state][district][block];
                    gps.forEach(gp => {
                        const option = new Option(gp, gp);
                        if (gp === selectedGP) option.selected = true;
                        gpSelect.add(option);
                    });
                } else {
                    gpSelect.disabled = true;
                }
            }

            // Initial trigger moved to populateStates for robustness
            /* if (currentState) {
                updateDistricts(currentState, currentDistrict);
            } */

            stateSelect.addEventListener('change', function () {
                updateDistricts(this.value);
            });

            districtSelect.addEventListener('change', function () {
                updateBlocks(stateSelect.value, this.value);
            });

            blockSelect.addEventListener('change', function () {
                updateGPs(stateSelect.value, districtSelect.value, this.value);
            });

            // PAN Formatting helper
            window.validatePAN = function(input) {
                let val = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                let result = '';
                
                for (let i = 0; i < val.length && i < 10; i++) {
                    if (i < 5) {
                        if (/[A-Z]/.test(val[i])) result += val[i];
                    } else if (i < 9) {
                        if (/[0-9]/.test(val[i])) result += val[i];
                    } else {
                        if (/[A-Z]/.test(val[i])) result += val[i];
                    }
                }
                input.value = result;
            };

            // Auto-capitalization Title Case helper
            const titleCase = (str) => {
                return str.toLowerCase().split(' ').map(word => {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join(' ');
            };

            const fullNameInput = document.querySelector('input[name="full_name"]');
            const addressInput = document.querySelector('textarea[name="address"]');

            if (fullNameInput) {
                fullNameInput.addEventListener('blur', function() {
                    this.value = titleCase(this.value);
                });
            }

            if (addressInput) {
                addressInput.addEventListener('blur', function() {
                    this.value = titleCase(this.value);
                });
            }

            // Validations on Submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const panInput = document.querySelector('input[name="pan_number"]');
                const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                if (panInput && panInput.value && !panPattern.test(panInput.value)) {
                    e.preventDefault();
                    alert('Invalid PAN Card format!\n\nRules:\n1. First 5 must be LETTERS\n2. Next 4 must be DIGITS\n3. Last 1 must be a LETTER\n\nExample: ABCDE1234F');
                    panInput.focus();
                    return false;
                }

                const aadhaarInput = document.querySelector('input[name="aadhaar_number"]');
                if (aadhaarInput && aadhaarInput.value && aadhaarInput.value.length !== 12) {
                    e.preventDefault();
                    alert('Aadhaar Number must be exactly 12 digits.');
                    aadhaarInput.focus();
                    return false;
                }

                const phoneInput = document.querySelector('input[name="phone_number"]');
                if (phoneInput && phoneInput.value && phoneInput.value.length !== 10) {
                    e.preventDefault();
                    alert('Phone Number must be exactly 10 digits.');
                    phoneInput.focus();
                    return false;
                }

                const pinInput = document.querySelector('input[name="pin_code"]');
                if (pinInput && pinInput.value && pinInput.value.length !== 6) {
                    e.preventDefault();
                    alert('Pin Code must be exactly 6 digits.');
                    pinInput.focus();
                    return false;
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initStateLogic);
        } else {
            initStateLogic();
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
    @include('layouts.partials.image_cropper')
@endsection