<x-app-layout>
    <x-slot name="header">
        <h1>Activity Log</h1>
    </x-slot>

    <div class="card">
        @if($logs->isEmpty())
            <div style="padding:48px;text-align:center;color:var(--muted);">
                No activity yet.
            </div>
        @else
            <ul style="list-style:none;">
                @foreach($logs as $log)
                    <li style="padding:14px 24px;border-bottom:1px solid var(--border);
                                display:flex;align-items:center;gap:14px;">

                        {{-- Icon --}}
                        <div style="width:34px;height:34px;border-radius:50%;flex-shrink:0;
                                    background:var(--red-light);display:flex;align-items:center;
                                    justify-content:center;">
                            @if($log->type === 'class_created')
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="var(--red)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            @elseif($log->type === 'task_created')
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="var(--red)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            @elseif($log->type === 'class_joined')
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="var(--red)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Text --}}
                        <div style="flex:1;min-width:0;">
                            <p style="font-size:14px;color:var(--text);">
                                <span style="font-weight:600;">{{ $log->user->name }}</span>
                                {{ $log->description }}
                            </p>
                        </div>

                        {{-- Time --}}
                        <span style="font-size:12px;color:var(--muted);white-space:nowrap;flex-shrink:0;">
                            {{ $log->created_at->diffForHumans() }}
                        </span>

                    </li>
                @endforeach
            </ul>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div style="padding:16px 24px;">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>