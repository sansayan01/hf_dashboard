@extends('layouts.app')

@section('title', 'All Appointments')
@section('header_title', 'Appointment Central')

@section('content')
    <div class="space-y-8">
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                    @if(request('view') === 'successful')
                        Successful Appointments
                    @elseif(request('view') === 'not_attended')
                        Not Attended Registry
                    @else
                        Scheduled Appointments
                    @endif
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest mt-1 italic">
                    @if(request('view') === 'successful')
                        History of completed clinical visits
                    @elseif(request('view') === 'not_attended')
                        Records of missed or unfulfilled appointments
                    @else
                        Managing upcoming clinic visits across all registry
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Registry Filter Dropdown -->
                <div class="relative">
                    <button type="button" 
                        onclick="toggleDropdown('appointment-filter-dropdown')"
                        class="px-5 py-3 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-[10px] font-black uppercase tracking-widest rounded-xl border border-slate-200/10 dark:border-white/5 shadow-sm flex items-center gap-2 hover:border-accent transition-all active:scale-95">
                        <span class="w-2 h-2 rounded-full {{ request('view') === 'successful' ? 'bg-emerald-500' : (request('view') === 'not_attended' ? 'bg-rose-500' : 'bg-accent') }}"></span>
                        @if(request('view') === 'successful') 
                            View: Successful 
                        @elseif(request('view') === 'not_attended') 
                            View: Not Attended 
                        @else 
                            View: Scheduled 
                        @endif
                        <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    
                    <div id="appointment-filter-dropdown" 
                        class="hidden absolute right-0 mt-2 w-72 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-white/5 p-4 z-[100] transition-all transform origin-top-right opacity-0 invisible scale-95 translate-y-2">
                        <div class="grid grid-cols-1 gap-3">
                            <!-- Scheduled Tile -->
                            <a href="{{ route('appointments.all', ['view' => 'scheduled', 'search' => request('search'), 'date' => request('date')]) }}" 
                                class="group/tile flex items-center p-3 rounded-2xl bg-accent/5 hover:bg-accent border border-accent/10 hover:border-accent transition-all duration-300">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm group-hover/tile:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-accent group-hover/tile:text-white transition-colors">Scheduled</p>
                                    <p class="text-[8px] font-bold text-slate-400 group-hover/tile:text-white/70 transition-colors">Upcoming clinical visits</p>
                                </div>
                            </a>
                            
                            <!-- Successful Tile -->
                            <a href="{{ route('appointments.all', ['view' => 'successful', 'search' => request('search'), 'date' => request('date')]) }}" 
                                class="group/tile flex items-center p-3 rounded-2xl bg-emerald-500/5 hover:bg-emerald-500 border border-emerald-500/10 hover:border-emerald-500 transition-all duration-300">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm group-hover/tile:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 group-hover/tile:text-white transition-colors">Successful</p>
                                    <p class="text-[8px] font-bold text-slate-400 group-hover/tile:text-white/70 transition-colors">Completed health checkups</p>
                                </div>
                            </a>

                            <!-- Not Attended Tile -->
                            <a href="{{ route('appointments.all', ['view' => 'not_attended', 'search' => request('search'), 'date' => request('date')]) }}" 
                                class="group/tile flex items-center p-3 rounded-2xl bg-rose-500/5 hover:bg-rose-500 border border-rose-500/10 hover:border-rose-500 transition-all duration-300">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm group-hover/tile:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-600 group-hover/tile:text-white transition-colors">Not Attended</p>
                                    <p class="text-[8px] font-bold text-slate-400 group-hover/tile:text-white/70 transition-colors">Missed or skipped visits</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <span class="px-4 py-3 bg-slate-100 dark:bg-slate-800 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-xl border border-slate-200/10 dark:border-white/5">
                    {{ $appointments->total() }} Records
                </span>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm">
            <form action="{{ route('appointments.all') }}" method="GET" class="no-loader flex flex-col lg:flex-row items-center gap-4">
                <div class="flex-1 w-full relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search Patient Name, ID, or Clinic Type..."
                        class="w-full pl-12 pr-4 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="w-full lg:w-48 relative">
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white">
                </div>
                
                <div class="flex items-center space-x-3 w-full lg:w-auto">
                    <input type="hidden" name="view" value="{{ request('view', 'scheduled') }}">
                    <button type="submit" class="flex-1 lg:flex-none px-8 py-4 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition-all">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'date']))
                        <a href="{{ route('appointments.all', ['view' => request('view', 'scheduled')]) }}" class="px-6 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-danger rounded-2xl transition-all text-[10px] font-black uppercase tracking-widest border border-transparent hover:border-danger/20">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($appointments->isEmpty())
            <div class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl p-20 text-center">
                <div class="w-24 h-24 bg-accent/10 text-accent rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white mb-3">No Appointments Found</h4>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto font-medium leading-relaxed">
                    There are no clinic visits scheduled matching your criteria. You can schedule new ones from individual patient profiles.
                </p>
            </div>
        @else
            <div class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Appt ID</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Patient</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Schedule</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Clinic Type</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Location</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Recorded By</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @foreach($appointments as $appointment)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="p-6">
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $appointment->appointment_id }}</span>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center space-x-4">
                                            @if($appointment->survey)
                                                <div class="w-10 h-10 bg-accent/10 text-accent dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black">
                                                    {{ substr($appointment->survey->full_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-black text-slate-800 dark:text-white text-sm">{{ $appointment->survey->full_name }}</h4>
                                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight mt-0.5">{{ $appointment->survey->patient_id }}</p>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center text-sm font-black">
                                                    ?
                                                </div>
                                                <div>
                                                    <h4 class="font-black text-slate-400 text-sm">Deleted Patient</h4>
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">N/A</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 flex flex-col items-center justify-center text-[8px] font-black uppercase">
                                                <span class="text-accent">{{ $appointment->appointment_date->format('M') }}</span>
                                                <span class="text-slate-600 dark:text-white text-xs">{{ $appointment->appointment_date->format('d') }}</span>
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-slate-800 dark:text-white">{{ $appointment->appointment_date->format('Y') }}</p>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <span class="px-3 py-1.5 bg-indigo-500/10 text-indigo-500 rounded-lg text-[10px] font-black uppercase tracking-wider border border-indigo-500/10">
                                            {{ $appointment->doctor_type }}
                                        </span>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center space-x-2 text-slate-600 dark:text-slate-300">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-xs font-bold">{{ $appointment->location }}</span>
                                        </div>
                                        @if($appointment->status === 'missed_reported')
                                            <span class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-amber-100 text-amber-700 uppercase tracking-tighter">
                                                Pending Confirmation
                                            </span>
                                        @elseif($appointment->status === 'not_attended')
                                            <span class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-rose-100 text-rose-700 uppercase tracking-tighter">
                                                Confirmed Missed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg overflow-hidden ring-2 ring-slate-100 dark:ring-slate-800">
                                                @if($appointment->creator && $appointment->creator->profile && $appointment->creator->profile->profile_picture)
                                                    <img src="{{ $appointment->creator->profile->getProfilePictureUrl() }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-[10px] font-black">
                                                        {{ substr($appointment->creator->profile->full_name ?? ($appointment->creator->employee_id ?? 'U'), 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                @if(auth()->user()->isSuperAdmin() && $appointment->creator)
                                                    <a href="{{ route('users.show', $appointment->creator->id) }}" class="text-xs font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                                                        <span>{{ $appointment->creator->profile->full_name ?? ($appointment->creator->employee_id ?? 'Unknown User') }}</span>
                                                        <svg class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $appointment->creator->profile->full_name ?? ($appointment->creator->employee_id ?? 'Unknown User') }}</p>
                                                @endif
                                                <p class="text-[10px] font-medium text-slate-400">{{ $appointment->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6 text-right space-x-2 whitespace-nowrap">
                                        @if($appointment->status === 'scheduled' && (auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge()))
                                            <form action="{{ route('appointments.complete', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to mark this appointment as successful?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 hover:text-white text-emerald-500 transition-all border border-emerald-500/10" title="Mark as Successful">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('appointments.report_missed', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Report this appointment as missed?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 transition-all border border-rose-500/10" title="Report Missed">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if($appointment->status === 'missed_reported' && (auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge()))
                                            <form action="{{ route('appointments.complete', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirm this appointment as SUCCESSFUL?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-all shadow-lg shadow-emerald-500/20" title="Confirm Success">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('appointments.confirm_missed', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirm this appointment as NOT ATTENDED?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition-all shadow-lg shadow-rose-500/20" title="Confirm Not Attended">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($appointment->status === 'scheduled' || $appointment->status === 'missed_reported')
                                            <a href="{{ route('patients.appointments.edit', $appointment->id) }}" 
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500/10 hover:bg-amber-500 hover:text-white text-amber-500 transition-all border border-amber-500/10" 
                                                title="Edit Appointment">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('patients.appointments.destroy', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this appointment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 transition-all border border-rose-500/10" title="Delete Appointment">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('patients.show', $appointment->survey_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-accent hover:text-white dark:bg-slate-800 dark:hover:bg-accent text-slate-400 transition-all border border-transparent" title="View Patient Profile">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($appointments->hasPages())
                    <div class="p-8 border-t border-slate-100 dark:border-white/5">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
@section('js')
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const arrow = document.getElementById('dropdown-arrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'invisible', 'translate-y-2', 'scale-95');
                    arrow.classList.add('rotate-180');
                }, 10);
            } else {
                dropdown.classList.add('opacity-0', 'invisible', 'translate-y-2', 'scale-95');
                arrow.classList.remove('rotate-180');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('appointment-filter-dropdown');
            const button = dropdown.previousElementSibling;
            
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                if (!dropdown.classList.contains('hidden')) {
                    toggleDropdown('appointment-filter-dropdown');
                }
            }
        });
    </script>
@endsection
