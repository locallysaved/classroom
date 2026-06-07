<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h1>Classes</h1>
            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                <a href="{{ route('classes.create') }}" class="btn btn-primary">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Class
                </a>
            @endif
        </div>
    </x-slot>

    @if($classes->isEmpty())
        <div class="card card-body" style="text-align:center;padding:60px 24px;color:var(--muted);">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                 style="margin:0 auto 12px;opacity:.35">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p style="font-size:15px;font-weight:500;">No classes yet.</p>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
            @foreach($classes as $class)
                <a href="{{ route('classes.show', $class) }}" style="text-decoration:none;">
                    <div class="card" style="transition:box-shadow .2s,transform .2s;cursor:pointer;"
                         onmouseenter="this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'"
                         onmouseleave="this.style.boxShadow='';this.style.transform=''">

                        {{-- Red header bar --}}
                        <div style="background:var(--red);padding:20px 20px 16px;position:relative;">
                            <div style="font-size:16px;font-weight:700;color:#fff;line-height:1.3;">
                                {{ $class->name }}
                            </div>
                            @if($class->description)
                                <p style="font-size:12px;color:rgba(255,255,255,.7);margin-top:4px;
                                          overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                    {{ $class->description }}
                                </p>
                            @endif
                        </div>

                        {{-- Card footer --}}
                        <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;">
                            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $class->teacher->name ?? 'Unknown' }}
                            </div>
                            <div style="display:flex;align-items:center;gap:4px;font-size:12px;
                                        color:var(--red);font-weight:600;
                                        background:var(--red-light);padding:3px 10px;border-radius:20px;">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $class->tasks_count }} {{ Str::plural('task', $class->tasks_count) }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-app-layout>
