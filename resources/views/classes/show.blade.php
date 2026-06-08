<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('classes.index') }}"
               style="color:var(--muted);display:flex;align-items:center;text-decoration:none;
                      padding:6px;border-radius:6px;transition:background .15s;"
               onmouseenter="this.style.background='var(--bg)'"
               onmouseleave="this.style.background='transparent'">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 style="font-size:20px;">{{ $class->name }}</h1>
                <p style="font-size:13px;color:var(--muted);margin-top:2px;">
                    by {{ $class->teacher->name ?? 'Unknown' }}
                </p>
            </div>
        </div>
    </x-slot>

    <div style="max-width:800px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- Description --}}
        @if($class->description)
            <div class="card card-body" style="font-size:14px;color:var(--muted);line-height:1.6;">
                {{ $class->description }}
            </div>
        @endif

        {{-- Join code (teachers & admins) --}}
        @if((auth()->user()->isTeacher() || auth()->user()->isAdmin()) && $class->join_code)
            <div class="card card-body">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text);">Class Access Code</div>
                        <div style="font-size:12px;color:var(--muted);margin-top:2px;">
                            Share with students so they can join
                        </div>
                    </div>
                    <button onclick="toggleCode(this)"
                            class="btn btn-primary">
                        Show Code &amp; QR
                    </button>
                </div>

                <div id="code-reveal" style="display:none;margin-top:24px;
                     display:none;flex-direction:column;gap:20px;align-items:flex-start;">
                    <div>
                        <p style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;
                                  color:var(--muted);margin-bottom:10px;">Join Code</p>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <span id="join-code-display"
                                  style="font-family:monospace;font-size:28px;font-weight:800;
                                         letter-spacing:.15em;color:var(--red);
                                         background:#fff;padding:10px 18px;border-radius:10px;
                                         border:2px solid var(--red);">
                                {{ $class->join_code }}
                            </span>
                            <button onclick="copyCode('{{ $class->join_code }}')"
                                    title="Copy code"
                                    style="background:none;border:none;cursor:pointer;
                                           color:var(--muted);transition:color .15s;padding:4px;"
                                    onmouseenter="this.style.color='var(--red)'"
                                    onmouseleave="this.style.color='var(--muted)'">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                        <p id="copied-msg" style="display:none;font-size:12px;color:#059669;margin-top:6px;">
                            Copied to clipboard!
                        </p>
                    </div>

                    <div>
                        <p style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;
                                  color:var(--muted);margin-bottom:10px;">QR Code</p>
                        <div style="background:#fff;border:1px solid var(--border);
                                    border-radius:10px;padding:8px;display:inline-block;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode(url('/join/' . $class->join_code)) }}"
                                 alt="QR Code" style="width:140px;height:140px;border-radius:6px;display:block;">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tasks panel --}}
        <div class="card">
            <div style="padding:16px 24px;border-bottom:1px solid var(--border);
                        display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:15px;font-weight:700;">Tasks</span>
                    <span style="font-size:12px;background:var(--red);color:#fff;
                                 font-weight:600;padding:2px 10px;border-radius:20px;">
                        {{ $class->tasks->count() }} {{ Str::plural('task', $class->tasks->count()) }}
                    </span>
                </div>
                @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                    <a href="{{ route('tasks.create', ['class' => $class->id]) }}" class="btn btn-primary"
                       style="padding:7px 14px;font-size:13px;">
                        + Add Task
                    </a>
                @endif
            </div>

            @if($class->tasks->isEmpty())
                <div style="padding:48px 24px;text-align:center;color:var(--muted);">
                    <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         style="margin:0 auto 10px;opacity:.3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p style="font-size:14px;">No tasks yet.</p>
                </div>
            @else
                <ul style="list-style:none;">
    @foreach($class->tasks as $task)
        <li style="border-bottom:1px solid var(--border);">
            <a href="{{ route('tasks.show', $task) }}"
               style="display:block;padding:16px 24px;text-decoration:none;transition:background .15s;"
               onmouseenter="this.style.background='var(--red-light)'"
               onmouseleave="this.style.background=''">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <div style="width:8px;height:8px;border-radius:50%;background:var(--red);flex-shrink:0;"></div>
                            <h4 style="font-size:14px;font-weight:600;color:var(--text);">{{ $task->name }}</h4>
                            @if($task->files_count > 0)
                                <span style="display:flex;align-items:center;gap:3px;font-size:11px;
                                             color:var(--muted);background:var(--bg);
                                             padding:2px 8px;border-radius:20px;">
                                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828A4 4 0 1012.343 4.343L5.757 10.929"/>
                                    </svg>
                                    {{ $task->files_count }}
                                </span>
                            @endif
                        </div>
                        <p style="font-size:13px;color:var(--muted);line-height:1.5;padding-left:16px;
                                  overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                            {{ $task->content }}
                        </p>
                    </div>
                    <span style="flex-shrink:0;font-size:12px;color:var(--muted);white-space:nowrap;
                                 background:var(--bg);padding:3px 10px;border-radius:20px;">
                        {{ \Carbon\Carbon::parse($task->date_added)->format('M d, Y') }}
                    </span>
                </div>
            </a>
        </li>
    @endforeach
</ul>
            @endif
        </div>

    </div>

    <script>
        function toggleCode(btn) {
            const el = document.getElementById('code-reveal');
            const hidden = el.style.display === 'none' || el.style.display === '';
            el.style.display = hidden ? 'flex' : 'none';
            btn.textContent = hidden ? 'Hide Code & QR' : 'Show Code & QR';
        }
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                const msg = document.getElementById('copied-msg');
                msg.style.display = 'block';
                setTimeout(() => msg.style.display = 'none', 2000);
            });
        }
    </script>
</x-app-layout>