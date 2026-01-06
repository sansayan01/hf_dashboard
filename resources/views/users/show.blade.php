@extends('layouts.app')

@section('title', 'Member Profile')
@section('header_title', 'Member Profile')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8">
        <!-- Header Section Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="h-32 bg-primary relative">
                <div class="absolute inset-0 bg-gradient-to-r from-accent/20 to-transparent"></div>
            </div>
            <div class="px-8 pb-8">
                <div
                    class="relative flex flex-col md:flex-row md:items-end -mt-16 mb-6 space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Profile Picture -->
                    <div class="w-32 h-32 rounded-3xl bg-white p-2 shadow-xl ring-1 ring-slate-100">
                        <div class="w-full h-full rounded-2xl overflow-hidden bg-slate-50 flex items-center justify-center">
                            @if($user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span
                                    class="text-4xl font-black text-accent">{{ substr($user->profile->full_name, 0, 1) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h2 class="text-3xl font-black text-slate-800">{{ $user->profile->full_name }}</h2>
                            <span
                                class="px-3 py-1 bg-accent/10 text-accent rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ $user->getDesignationLabel() }}
                            </span>
                            @if($user->status === 'active')
                                <span
                                    class="px-3 py-1 bg-success/10 text-success rounded-full text-[10px] font-black uppercase tracking-widest">Active</span>
                            @else
                                <span
                                    class="px-3 py-1 bg-warning/10 text-warning rounded-full text-[10px] font-black uppercase tracking-widest">Pending</span>
                            @endif
                        </div>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">{{ $user->employee_id }}</p>
                    </div>

                    <div class="flex flex-wrap gap-2 md:gap-3">
                        @if(auth()->user()->canAccess($user) && auth()->user()->id !== $user->id)
                            <a href="{{ route('dashboard', ['as_user' => $user->id]) }}"
                               class="px-5 py-3 bg-indigo-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <span>View Dashboard</span>
                            </a>
                        @endif

                        @if(auth()->user()->canEdit($user))
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-50 transition">Edit
                                Profile</a>
                        @endif
                        @if($user->status === 'pending' && auth()->user()->canApprove($user))
                            <form action="{{ route('users.approve', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-3 bg-success text-white font-bold rounded-xl text-sm shadow-lg shadow-success/20 hover:opacity-90 transition">Approve
                                    Member</button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Vital Stats Row -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pt-6 border-t border-slate-50">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Upline Manager</p>
                        <p class="text-sm font-bold text-slate-700">
                            {{ $user->parent?->profile?->full_name ?? 'ROOT / Super Admin' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Direct Downline</p>
                        <p class="text-sm font-bold text-slate-700">{{ $user->children->count() }} Members</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Date Joined</p>
                        <p class="text-sm font-bold text-slate-700">{{ $user->created_at->format('d M, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Contact No.</p>
                        <p class="text-sm font-bold text-slate-700">{{ $user->profile->phone_number }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Personal Information -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Identity Details</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-y-8 gap-x-12">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Address
                            </p>
                            <p class="font-bold text-slate-800">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Blood Group</p>
                            <p class="font-bold text-slate-800">{{ $user->profile->blood_group ?? 'Not Specified' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Aadhaar Card No.
                            </p>
                            <p class="font-bold text-slate-800">{{ $user->profile->aadhaar_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">PAN Card No.</p>
                            <p class="font-bold text-slate-800 uppercase">{{ $user->profile->pan_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Current Address
                            </p>
                            <p class="font-bold text-slate-800 leading-relaxed">{{ $user->profile->address }}</p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $user->profile->gram_panchayat }}, {{ $user->profile->block }},
                                {{ $user->profile->district }}, {{ $user->profile->state }} - {{ $user->profile->pin_code }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Banking Section -->
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-success/5 rounded-full -mr-16 -mt-16"></div>

                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-success/10 text-success rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Financial Records</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-4 bg-slate-50 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Bank Name</p>
                            <p class="font-bold text-slate-700">{{ $user->bankDetails?->bank_name ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">A/C Number</p>
                            <p class="font-bold text-slate-700 italic">••••
                                {{ substr($user->bankDetails?->account_number ?? '0000', -4) }}
                            </p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">IFSC Code</p>
                            <p class="font-bold text-slate-700">{{ $user->bankDetails?->ifsc_code ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hierarchy Context -->
            <div class="space-y-8">


                <!-- Activity Summary -->
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-6">Recent Actions</h3>
                    <div class="space-y-6">
                        @forelse($user->activityLogs()->latest()->take(3)->get() as $log)
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0 w-1 bg-slate-100 rounded-full"></div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700 leading-tight mb-1">{{ $log->description }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 italic">No recent activity logs.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection