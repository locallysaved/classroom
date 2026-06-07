<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('classes.show', $task->class) }}"
               style="color:var(--muted);display:flex;align-items:center;text-decoration:none;
                      padding:6px;border-radius:6px;transition:background .15s;"
               onmouseenter="this.style.background='var(--bg)'"
               onmouseleave="this.style.background='transparent'">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 style="font-size:20px;">{{ $task->name }}</h1>
                <p style="font-size:13px;color:var(--muted);margin-top:2px;">
                    {{ $task->class->name }}
                </p>
            </div>
        </div>
    </x-slot>

    <div style="max-width:900px;margin:0 auto;display:grid;
                grid-template-columns:1fr 260px;gap:16px;align-items:start;">

        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:14px;">

            {{-- Task info --}}
            <div style="background:var(--red);border-radius:12px;padding:20px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                    <div>
                        <p style="font-size:15px;font-weight:600;color:#fff;">{{ $task->name }}</p>
                        <p style="font-size:13px;color:rgba(255,255,255,.75);margin-top:4px;">
                            {{ $task->class->name }} &mdash; {{ \Carbon\Carbon::parse($task->date_added)->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <p style="font-size:14px;color:#fff;margin-top:14px;line-height:1.6;">
                    {{ $task->content }}
                </p>
            </div>

            {{-- Teacher's files --}}
            <div style="background:var(--red);border-radius:12px;padding:20px;">
                <p style="font-size:13px;font-weight:600;color:#fff;margin-bottom:12px;">files</p>

                @if($task->files->isEmpty())
                    <p style="font-size:13px;color:rgba(255,255,255,.6);">No files attached.</p>
                @else
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @foreach($task->files as $file)
                            <a href="{{ route('task-files.download', $file) }}"
                               style="display:flex;align-items:center;gap:8px;
                                      background:rgba(255,255,255,.15);border-radius:8px;
                                      padding:8px 12px;text-decoration:none;color:#fff;
                                      font-size:13px;transition:background .15s;"
                               onmouseenter="this.style.background='rgba(255,255,255,.25)'"
                               onmouseleave="this.style.background='rgba(255,255,255,.15)'">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $file->original_name }}
                                </span>
                                <span style="font-size:11px;color:rgba(255,255,255,.6);flex-shrink:0;">
                                    {{ round($file->size / 1024) }} KB
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:14px;">

            {{-- Submit solution files --}}
            <div style="background:var(--red);border-radius:12px;padding:20px;">
                <p style="font-size:13px;font-weight:600;color:#fff;margin-bottom:12px;">
                    add files to solutions
                </p>

                <form action="{{ route('tasks.submitSolution', $task) }}" method="POST"
                      enctype="multipart/form-data"
                      style="display:flex;flex-direction:column;gap:10px;">
                    @csrf

                    <label for="solution_files"
                           style="display:flex;align-items:center;justify-content:center;gap:6px;
                                  background:#fff;border-radius:8px;padding:10px 14px;
                                  font-size:13px;color:var(--muted);cursor:pointer;
                                  border:2px dashed rgba(0,0,0,.15);transition:border-color .15s;"
                           onmouseenter="this.style.borderColor='var(--red-dark)'"
                           onmouseleave="this.style.borderColor='rgba(0,0,0,.15)'">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828A4 4 0 1012.343 4.343L5.757 10.929"/>
                        </svg>
                        choose files
                    </label>
                    <input type="file" name="solution_files[]" id="solution_files" multiple
                           style="display:none;" onchange="showSolutionFiles(this)">

                    <ul id="solution-file-list" style="display:flex;flex-direction:column;gap:4px;"></ul>

                    <button type="submit"
                            style="background:var(--red-dark);color:#fff;border:none;border-radius:8px;
                                   padding:8px 16px;font-size:13px;font-weight:600;font-family:inherit;
                                   cursor:pointer;transition:opacity .15s;"
                            onmouseenter="this.style.opacity='.85'"
                            onmouseleave="this.style.opacity='1'">
                        submit
                    </button>
                </form>

                @if($mySolutions->isNotEmpty())
                    <div style="margin-top:14px;border-top:1px solid rgba(255,255,255,.2);padding-top:12px;">
                        <p style="font-size:12px;color:rgba(255,255,255,.7);margin-bottom:8px;">submitted</p>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            @foreach($mySolutions as $sol)
                                <a href="{{ route('solutions.download', $sol) }}"
                                   style="display:flex;align-items:center;gap:6px;font-size:12px;
                                          color:#fff;text-decoration:none;background:rgba(0,0,0,.15);
                                          border-radius:6px;padding:5px 10px;">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828A4 4 0 1012.343 4.343L5.757 10.929"/>
                                    </svg>
                                    {{ $sol->original_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Comments --}}
            <div style="background:var(--red);border-radius:12px;padding:20px;">
                <p style="font-size:13px;font-weight:600;color:#fff;margin-bottom:12px;">comments</p>

                @forelse($task->comments as $comment)
                    <div style="margin-bottom:10px;">
                        <p style="font-size:12px;font-weight:600;color:rgba(255,255,255,.85);">
                            {{ $comment->user->name }}
                            <span style="font-weight:400;color:rgba(255,255,255,.5);">
                                &middot; {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </p>
                        <p style="font-size:13px;color:#fff;margin-top:2px;">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p style="font-size:13px;color:rgba(255,255,255,.6);margin-bottom:12px;">No comments yet.</p>
                @endforelse

                <form action="{{ route('tasks.comment', $task) }}" method="POST"
                      style="margin-top:4px;display:flex;flex-direction:column;gap:8px;">
                    @csrf
                    <textarea name="body" rows="2" placeholder="write a comment…" required
                              style="width:100%;border:none;border-radius:8px;padding:9px 12px;
                                     font-size:13px;font-family:inherit;color:var(--text);
                                     background:#fff;outline:none;resize:none;
                                     box-shadow:0 1px 3px rgba(0,0,0,.08);"></textarea>
                    <button type="submit"
                            style="align-self:flex-end;background:var(--red-dark);color:#fff;border:none;
                                   border-radius:8px;padding:7px 16px;font-size:13px;font-weight:600;
                                   font-family:inherit;cursor:pointer;transition:opacity .15s;"
                            onmouseenter="this.style.opacity='.85'"
                            onmouseleave="this.style.opacity='1'">
                        post
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
    function showSolutionFiles(input) {
        const list = document.getElementById('solution-file-list');
        list.innerHTML = '';
        Array.from(input.files).forEach(f => {
            const li = document.createElement('li');
            li.style = 'font-size:12px;color:#fff;background:rgba(0,0,0,.18);border-radius:6px;'
                     + 'padding:5px 10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;';
            li.textContent = f.name;
            list.appendChild(li);
        });
    }
    </script>
</x-app-layout>