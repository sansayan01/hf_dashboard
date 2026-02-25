<div class="grid grid-cols-1 md:grid-cols-2 {{ $user->isRO() ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }} gap-6">
    <div class="summary-card p-6 flex flex-col items-center">
        <span class="text-emerald-600 text-3xl font-bold">{{ $summary['present'] }}</span>
        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Present Days</span>
    </div>
    <div class="summary-card p-6 flex flex-col items-center">
        <span class="text-rose-600 text-3xl font-bold">{{ $summary['absent'] }}</span>
        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Absent Days</span>
    </div>
    <div class="summary-card p-6 flex flex-col items-center">
        <span class="text-accent text-3xl font-bold">₹{{ number_format($summary['total_incentives'], 0) }}</span>
        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Incentives</span>
    </div>
    <div class="summary-card p-6 flex flex-col items-center">
        <span class="text-amber-600 text-3xl font-bold">₹{{ number_format($summary['ta'], 0) }}</span>
        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">TA Earned</span>
    </div>
    <div
        class="summary-card p-6 flex flex-col @if(app()->getLocale() == 'en') lg:col-span-1 @endif items-center bg-accent/5 !border-accent/20">
        <span class="text-accent text-3xl font-extrabold">₹{{ number_format($summary['total'], 0) }}</span>
        <span class="text-sm font-bold text-accent uppercase tracking-wider mt-2">Total Earned</span>
    </div>
</div>

<!-- Calendar Section -->
<div class="summary-card p-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight">
                    {{ $targetDate->format('F Y') }}
                </h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Attendance Sheet</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @php
                $prevMonth = $targetDate->copy()->subMonth();
                $nextMonth = $targetDate->copy()->addMonth();
            @endphp
            <a href="{{ request()->fullUrlWithQuery(['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                onclick="loadCalendar(event, this.href)"
                class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <a href="{{ request()->fullUrlWithQuery(['month' => now()->month, 'year' => now()->year]) }}"
                onclick="loadCalendar(event, this.href)"
                class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-200 transition-all">
                Today
            </a>

            <a href="{{ request()->fullUrlWithQuery(['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                onclick="loadCalendar(event, this.href)"
                class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            @if(!empty($viewableUsers) && count($viewableUsers) > 0 && auth()->user()->designation !== 'ro')
                @php
                    $usersJson = $viewableUsers->map(function ($u) use ($user) {
                        return [
                            'id' => $u->id,
                            'name' => $u->profile->full_name ?? $u->employee_id,
                            'eid' => $u->employee_id,
                            'phone' => $u->phone ?? '',
                            'desig' => str_replace('_', ' ', $u->designation),
                            'label' => ($u->profile->full_name ?? $u->employee_id) . ' · ' . $u->employee_id,
                            'selected' => $user->id == $u->id,
                        ];
                    })->values();
                @endphp

                <div class="ml-4 relative" id="user-search-wrapper">
                    <!-- Input -->
                    <div
                        class="flex items-center bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl px-3 py-1.5 focus-within:ring-2 focus-within:ring-accent/30 transition-all min-w-[200px] md:min-w-[260px]">
                        <svg class="w-3.5 h-3.5 text-slate-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="user-search-input" placeholder="Search by name, ID, phone…"
                            autocomplete="off"
                            value="{{ $user->id !== auth()->id() ? ($user->profile->full_name ?? $user->employee_id) . ' · ' . $user->employee_id : '' }}"
                            class="bg-transparent border-none p-0 text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-0 placeholder:text-slate-400 placeholder:font-normal w-full">
                        <button id="user-search-reset" onclick="resetUserSearch()"
                            class="{{ $user->id !== auth()->id() ? '' : 'hidden' }} ml-1 text-slate-300 hover:text-slate-500 transition flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Dropdown -->
                    <div id="user-search-dropdown"
                        class="hidden absolute top-full left-0 mt-2 w-80 bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 rounded-2xl shadow-2xl shadow-slate-900/20 z-50 overflow-hidden">
                        <!-- My attendance shortcut -->
                        <div onclick="selectUserSearch({{ auth()->id() }}, 'View My Attendance')"
                            class="px-4 py-3 text-xs font-bold text-accent hover:bg-accent/5 cursor-pointer border-b border-slate-50 dark:border-white/5 flex items-center space-x-2 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>View My Attendance</span>
                        </div>
                        <div id="user-search-results"
                            class="max-h-60 overflow-y-auto divide-y divide-slate-50 dark:divide-white/5"></div>
                        <div id="user-search-empty"
                            class="hidden px-4 py-6 text-center text-xs text-slate-400 font-bold uppercase tracking-wider">
                            No users found</div>
                    </div>
                </div>

                <script>
                    (function () {
                        const allUsers = @json($usersJson);
                        const dashboardUrl = "{{ route('attendance.dashboard') }}";
                        const input = document.getElementById('user-search-input');
                        const dropdown = document.getElementById('user-search-dropdown');
                        const resultsBox = document.getElementById('user-search-results');
                        const emptyBox = document.getElementById('user-search-empty');
                        const resetBtn = document.getElementById('user-search-reset');
                        let isOpen = false;

                        function renderResults(query) {
                            const q = query.toLowerCase().trim();
                            const filtered = q.length === 0
                                ? allUsers.slice(0, 10)
                                : allUsers.filter(u =>
                                    u.name.toLowerCase().includes(q) ||
                                    u.eid.toLowerCase().includes(q) ||
                                    (u.phone && u.phone.toLowerCase().includes(q))
                                ).slice(0, 10);

                            resultsBox.innerHTML = '';
                            if (filtered.length === 0) {
                                emptyBox.classList.remove('hidden');
                            } else {
                                emptyBox.classList.add('hidden');
                                filtered.forEach(u => {
                                    const el = document.createElement('div');
                                    el.className = 'px-4 py-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-white/5 transition-colors flex items-center justify-between group';
                                    el.innerHTML = `
                                                    <div>
                                                        <p class="text-xs font-black text-slate-700 dark:text-slate-200">${highlight(u.name, q)}</p>
                                                        <p class="text-[10px] text-slate-400 font-bold mt-0.5">${highlight(u.eid, q)}${u.phone ? ' &nbsp;·&nbsp; ' + highlight(u.phone, q) : ''}</p>
                                                    </div>
                                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-300 dark:text-slate-600 group-hover:text-accent transition-colors">${u.desig}</span>
                                                `;
                                    el.addEventListener('click', () => selectUserSearch(u.id, u.label));
                                    resultsBox.appendChild(el);
                                });
                            }
                        }

                        function highlight(text, q) {
                            if (!q || !text) return text;
                            const idx = text.toLowerCase().indexOf(q.toLowerCase());
                            if (idx === -1) return text;
                            return text.slice(0, idx) + '<mark class="bg-accent/20 text-accent rounded px-0.5">' + text.slice(idx, idx + q.length) + '</mark>' + text.slice(idx + q.length);
                        }

                        window.selectUserSearch = function (userId, label) {
                            input.value = label === 'View My Attendance' ? '' : label;
                            input.placeholder = label === 'View My Attendance' ? 'Search by name, ID, phone…' : label;
                            closeDropdown();
                            if (label !== 'View My Attendance') {
                                resetBtn.classList.remove('hidden');
                            } else {
                                resetBtn.classList.add('hidden');
                            }
                            const url = `${dashboardUrl}?user_id=${userId}`;
                            loadCalendar(null, url);
                        };

                        window.resetUserSearch = function () {
                            input.value = '';
                            resetBtn.classList.add('hidden');
                            loadCalendar(null, `${dashboardUrl}?user_id={{ auth()->id() }}`);
                        };

                        function openDropdown() {
                            renderResults(input.value);
                            dropdown.classList.remove('hidden');
                            isOpen = true;
                        }

                        function closeDropdown() {
                            dropdown.classList.add('hidden');
                            isOpen = false;
                        }

                        input.addEventListener('focus', () => openDropdown());
                        input.addEventListener('input', () => {
                            renderResults(input.value);
                            if (!isOpen) openDropdown();
                        });

                        document.addEventListener('click', (e) => {
                            const wrapper = document.getElementById('user-search-wrapper');
                            if (wrapper && !wrapper.contains(e.target)) {
                                closeDropdown();
                            }
                        });

                        input.addEventListener('keydown', (e) => {
                            if (e.key === 'Escape') closeDropdown();
                        });
                    })();
                </script>
            @endif

            <a href="{{ route('attendance.reports') }}"
                class="ml-4 px-4 py-2 bg-accent text-white text-xs font-bold rounded-xl hover:shadow-lg transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span>Advanced Report</span>
            </a>
        </div>
    </div>

    <div class="calendar-container">
        @php
            $startOfMonth = $targetDate->copy()->startOfMonth();
            $endOfMonth = $targetDate->copy()->endOfMonth();
            $daysInMonth = $targetDate->daysInMonth;
            $startDay = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
            $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        @endphp

        @foreach($days as $day)
            <div class="calendar-day-label">{{ $day }}</div>
        @endforeach

        @for($i = 0; $i < $startDay; $i++)
            <div></div>
        @endfor

        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $currentDate = $startOfMonth->copy()->addDays($day - 1);
                $dateStr = $currentDate->format('Y-m-d');

                // Robust matching: ensure we compare date strings
                $attendance = $allAttendances->first(function ($item) use ($dateStr) {
                    return $item->date->format('Y-m-d') === $dateStr;
                });

                $isToday = $currentDate->isToday();
                $isFuture = $currentDate->isFuture() && !$isToday;

                $statusClass = 'status-future';
                if ($attendance) {
                    $statusClass = $attendance->status === 'present' ? 'status-present' : 'status-absent';
                }
            @endphp

            <div class="calendar-day {{ $statusClass }} {{ $isToday ? 'ring-4 ring-accent ring-offset-2' : '' }}"
                @if($attendance)
                    onclick="showDetails('{{ $currentDate->format('Y-m-d') }}', '{{ $currentDate->format('d M Y') }}', '{{ ucfirst($attendance->status) }}', '{{ $attendance->incentive_amount }}', '{{ $attendance->ta_amount }}', '{{ $attendance->medicines_amount }}', '{{ $attendance->pathology_amount }}', '{{ $attendance->membership_amount }}', '{{ $attendance->ots_amount }}', '{{ $attendance->total_amount }}', '{{ $attendance->markedBy->profile->full_name ?? 'System' }}', '{{ $attendance->created_at->format('H:i') }}', {{ $user->id }})"
                @elseif(!$isFuture)
                    onclick="showDetails('{{ $currentDate->format('Y-m-d') }}', '{{ $currentDate->format('d M Y') }}', 'Pending/Absent', 0, 0, 0, 0, 0, 0, 0, 'N/A', 'N/A', {{ $user->id }})"
                @endif>
                {{ $day }}
                @if($isToday)
                    <span class="text-[8px] absolute top-1 font-black opacity-60">TODAY</span>
                @endif
                @if($attendance && $attendance->status === 'present')
                    <div class="indicator"></div>
                @endif
            </div>
        @endfor
    </div>
</div>