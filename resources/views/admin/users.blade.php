<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h1>Users</h1>
            <span style="font-size:13px;color:var(--muted);background:var(--bg);
                         padding:4px 12px;border-radius:20px;border:1px solid var(--border);">
                {{ $users->count() }} total
            </span>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif

    <div class="card">
        <ul style="list-style:none;">
            @foreach($users as $user)
                <li style="padding:14px 24px;border-bottom:1px solid var(--border);
                            display:flex;align-items:center;gap:16px;flex-wrap:wrap;">

                    {{-- Avatar --}}
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--red);
                                display:flex;align-items:center;justify-content:center;
                                color:#fff;font-size:13px;font-weight:700;flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:14px;font-weight:600;color:var(--text);
                                  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                                <span style="font-size:11px;color:var(--muted);font-weight:400;">(you)</span>
                            @endif
                        </p>
                        <p style="font-size:12px;color:var(--muted);margin-top:2px;
                                  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $user->email }}
                        </p>
                    </div>

                    {{-- Role selector --}}
                    <form action="{{ route('admin.users.role', $user) }}" method="POST"
                          style="display:flex;align-items:center;gap:8px;">
                        @csrf @method('PATCH')
                        <select name="role"
                                onchange="this.form.submit()"
                                style="border:1.5px solid var(--border);border-radius:8px;
                                       padding:6px 10px;font-size:13px;font-family:inherit;
                                       color:var(--text);background:#fff;cursor:pointer;
                                       transition:border-color .15s;"
                                onfocus="this.style.borderColor='var(--red)'"
                                onblur="this.style.borderColor='var(--border)'">
                            <option value="student"  {{ $user->role === 'student'  ? 'selected' : '' }}>Student</option>
                            <option value="teacher"  {{ $user->role === 'teacher'  ? 'selected' : '' }}>Teacher</option>
                            <option value="admin"    {{ $user->role === 'admin'    ? 'selected' : '' }}>Admin</option>
                        </select>
                    </form>

                    {{-- Delete --}}
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="background:none;border:none;cursor:pointer;
                                           color:var(--muted);padding:6px;border-radius:6px;
                                           transition:color .15s,background .15s;"
                                    onmouseenter="this.style.color='var(--red)';this.style.background='var(--red-light)'"
                                    onmouseleave="this.style.color='var(--muted)';this.style.background='none'"
                                    title="Delete user">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    @endif

                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>