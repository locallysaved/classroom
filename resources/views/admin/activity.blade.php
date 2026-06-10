<x-app-layout>
    <x-slot name="header">
        <h1>Activity Log</h1>
    </x-slot>

    <div style="max-width:860px;margin:0 auto;">

        @if($standalone->isEmpty() && $classIds->isEmpty())
            <div class="card card-body" style="text-align:center;color:var(--muted);padding:48px;">
                <p>No activity recorded yet.</p>
            </div>
        @endif

        {{-- ── Standalone events ── --}}
        @foreach($standalone as $log)
            <div class="card card-body" style="margin-bottom:12px;display:flex;align-items:flex-start;gap:14px;">

                {{-- Icon --}}
                <div style="width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                    background:{{ $log->event === 'user_registered' ? '#EDE9FE' : '#DBEAFE' }};">
                    @if($log->event === 'user_registered')
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#6D28D9">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    @else
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#1D4ED8">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    @endif
                </div>

                {{-- Content --}}
                <div style="flex:1;min-width:0;">
                    @if($log->event === 'user_registered')
                        <p style="font-size:14px;font-weight:600;color:var(--text);">
                            New user registered
                        </p>
                        <p style="font-size:13px;color:var(--muted);margin-top:2px;">
                            <strong>{{ $log->meta['name'] ?? 'Unknown' }}</strong>
                            joined as <span style="text-transform:capitalize;">{{ $log->meta['role'] ?? '—' }}</span>
                        </p>
                    @elseif($log->event === 'role_changed')
                        <p style="font-size:14px;font-weight:600;color:var(--text);">
                            Role changed
                        </p>
                        <p style="font-size:13px;color:var(--muted);margin-top:2px;">
                            <strong>{{ $log->user->name ?? 'Unknown' }}</strong>
                            changed <strong>{{ $log->meta['target_user'] ?? '?' }}</strong>'s role
                            from <em>{{ $log->meta['old_role'] ?? '?' }}</em>
                            to <em>{{ $log->meta['new_role'] ?? '?' }}</em>
                        </p>
                    @endif
                    <p style="font-size:11px;color:var(--muted);margin-top:4px;">
                        {{ $log->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @endforeach

        {{-- ── Class tiles ── --}}
        @foreach($classIds as $classId)
            @php
                $class     = $allClasses[$classId] ?? null;
                $createdLog = $classCreatedLogs[$classId] ?? null;
                $subs      = $subEvents[$classId] ?? collect();
                $subCount  = $subs->count();
            @endphp

            <div class="card" style="margin-bottom:12px;overflow:visible;">

                {{-- Class tile header --}}
                <div class="card-body"
                     style="display:flex;align-items:flex-start;gap:14px;cursor:{{ $subCount > 0 ? 'pointer' : 'default' }};"
                     onclick="{{ $subCount > 0 ? 'toggleSubs(' . $classId . ', this)' : '' }}">

                    {{-- Icon --}}
                    <div style="width:38px;height:38px;border-radius:50%;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#D97706">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:14px;font-weight:600;color:var(--text);">
                            Class created —
                            <span style="color:var(--red);">{{ $class->name ?? 'Unknown Class' }}</span>
                        </p>
                        <p style="font-size:13px;color:var(--muted);margin-top:2px;">
                            Created by <strong>{{ $class->teacher->name ?? ($createdLog->user->name ?? 'Unknown') }}</strong>
                        </p>
                        @if($subCount > 0)
                            <p style="font-size:12px;color:var(--red);margin-top:4px;font-weight:500;">
                                {{ $subCount }} {{ Str::plural('activity', $subCount) }} logged
                            </p>
                        @endif
                        @if($createdLog)
                            <p style="font-size:11px;color:var(--muted);margin-top:4px;">
                                {{ $createdLog->created_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>

                    {{-- Chevron --}}
                    @if($subCount > 0)
                        <svg id="chevron-{{ $classId }}" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             style="color:var(--muted);flex-shrink:0;transition:transform .2s;margin-top:2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    @endif
                </div>

                {{-- Sub-events --}}
                @if($subCount > 0)
                    <div id="subs-{{ $classId }}" style="display:none;border-top:1px solid var(--border);">
                        @foreach($subs as $sub)
                            <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 24px 14px 70px;border-bottom:1px solid var(--border);last:border-0;">

                                {{-- Sub icon --}}
                                <div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                    background:{{ $sub->event === 'task_created' ? '#ECFDF5' : ($sub->event === 'student_joined' ? '#EFF6FF' : '#FFF7ED') }};">
                                    @if($sub->event === 'task_created')
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#059669">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    @elseif($sub->event === 'student_joined')
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#2563EB">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                    @else
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#EA580C">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                        </svg>
                                    @endif
                                </div>

                                {{-- Sub content --}}
                                <div style="flex:1;min-width:0;">
                                    @if($sub->event === 'task_created')
                                        <p style="font-size:13px;color:var(--text);">
                                            <strong>{{ $sub->user->name ?? 'Unknown' }}</strong>
                                            created task <em>{{ $sub->meta['task_name'] ?? '?' }}</em>
                                        </p>
                                    @elseif($sub->event === 'student_joined')
                                        <p style="font-size:13px;color:var(--text);">
                                            <strong>{{ $sub->user->name ?? 'Unknown' }}</strong>
                                            joined the class
                                        </p>
                                    @elseif($sub->event === 'file_uploaded')
                                        <p style="font-size:13px;color:var(--text);">
                                            <strong>{{ $sub->user->name ?? 'Unknown' }}</strong>
                                            uploaded <em>{{ $sub->meta['file_name'] ?? '?' }}</em>
                                            @if(!empty($sub->meta['task_name']))
                                                on task <em>{{ $sub->meta['task_name'] }}</em>
                                            @endif
                                        </p>
                                    @endif
                                    <p style="font-size:11px;color:var(--muted);margin-top:2px;">
                                        {{ $sub->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        @endforeach

    </div>

    <script>
        function toggleSubs(classId, el) {
            const subs    = document.getElementById('subs-' + classId);
            const chevron = document.getElementById('chevron-' + classId);
            const open    = subs.style.display === 'block';
            subs.style.display    = open ? 'none' : 'block';
            chevron.style.transform = open ? '' : 'rotate(180deg)';
        }
    </script>
</x-app-layout>