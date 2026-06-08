<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('classes.show', $task->class) }}"
               style="color:rgba(255,255,255,.75);display:flex;align-items:center;text-decoration:none;
                      padding:6px;border-radius:6px;transition:background .15s;"
               onmouseenter="this.style.background='rgba(0,0,0,.15)'"
               onmouseleave="this.style.background='transparent'">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 style="font-size:20px;">{{ $task->name }}</h1>
                <p style="font-size:13px;color:rgba(255,255,255,.65);margin-top:2px;">
                    {{ $task->class->name }}
                </p>
            </div>
        </div>
    </x-slot>

    <style>
        .task-grid {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 16px;
            align-items: start;
        }
        @media (max-width: 768px) {
            .task-grid { grid-template-columns: 1fr; }
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            margin-bottom: 12px;
        }
        .file-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px;
            text-decoration: none;
            color: var(--text);
            font-size: 13px;
            transition: border-color .15s, background .15s;
        }
        .file-pill:hover { border-color: var(--red); background: var(--red-light); }
        .comment-item { padding: 10px 0; border-bottom: 1px solid var(--border); }
        .comment-item:last-child { border-bottom: none; }
    </style>

    <div class="task-grid">

        {{-- ── LEFT COLUMN ── --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Task info card --}}
            <div class="card">
                <div style="background:var(--red);padding:16px 20px;border-radius:12px 12px 0 0;">
                    <p style="font-size:16px;font-weight:700;color:#fff;">{{ $task->name }}</p>
                    <p style="font-size:12px;color:rgba(255,255,255,.65);margin-top:3px;">
                        {{ $task->class->name }} &mdash; {{ \Carbon\Carbon::parse($task->date_added)->format('d M Y') }}
                    </p>
                </div>
                <div class="card-body">
                    <p style="font-size:14px;color:var(--text);line-height:1.7;">{{ $task->content }}</p>
                </div>
            </div>

            {{-- Teacher's files --}}
            <div class="card card-body">
                <p class="section-title">Attached Files</p>
                @if($task->files->isEmpty())
                    <p style="font-size:13px;color:var(--muted);">No files attached.</p>
                @else
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        @foreach($task->files as $file)
                            <a href="{{ route('task-files.download', $file) }}" class="file-pill">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;color:var(--red)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $file->original_name }}
                                </span>
                                @if($file->size)
                                    <span style="font-size:11px;color:var(--muted);flex-shrink:0;">
                                        {{ round($file->size / 1024) }} KB
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Teacher/Admin: all submissions --}}
            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                <div class="card card-body">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                        <p class="section-title" style="margin-bottom:0;">Student Submissions</p>
                        @if($allSolutions->count() > 0)
                            <span style="background:var(--red);color:#fff;font-size:11px;font-weight:700;
                                         padding:2px 8px;border-radius:20px;">
                                {{ $allSolutions->count() }}
                            </span>
                        @endif
                    </div>

                    @if($allSolutions->isEmpty())
                        <p style="font-size:13px;color:var(--muted);">No submissions yet.</p>
                    @else
                        <div style="display:flex;flex-direction:column;gap:14px;">
                            @foreach($allSolutions->groupBy('user_id') as $userId => $solutions)
                                <div>
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                                        <div style="width:26px;height:26px;border-radius:50%;
                                                    background:var(--red);color:#fff;
                                                    display:flex;align-items:center;justify-content:center;
                                                    font-size:11px;font-weight:700;flex-shrink:0;">
                                            {{ strtoupper(substr($solutions->first()->user->name, 0, 1)) }}
                                        </div>
                                        <p style="font-size:13px;font-weight:600;color:var(--text);">
                                            {{ $solutions->first()->user->name }}
                                        </p>
                                    </div>
                                    <div style="display:flex;flex-direction:column;gap:5px;padding-left:34px;">
                                        @foreach($solutions as $sol)
                                            <a href="{{ route('solutions.download', $sol) }}" class="file-pill">
                                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;color:var(--red)">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {{ $sol->original_name }}
                                                </span>
                                                <span style="font-size:11px;color:var(--muted);flex-shrink:0;">
                                                    {{ $sol->created_at->format('d M') }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- Student: submit solution --}}
            @if(auth()->user()->isStudent())
                <div class="card card-body">
                    <p class="section-title">Submit Your Work</p>

                    <form action="{{ route('tasks.submitSolution', $task) }}" method="POST"
                          enctype="multipart/form-data"
                          style="display:flex;flex-direction:column;gap:10px;">
                        @csrf

                        <label for="solution_files"
                               style="display:flex;align-items:center;justify-content:center;gap:6px;
                                      border:2px dashed var(--border);border-radius:8px;
                                      padding:14px;font-size:13px;color:var(--muted);cursor:pointer;
                                      transition:border-color .15s,color .15s;"
                               onmouseenter="this.style.borderColor='var(--red)';this.style.color='var(--red)'"
                               onmouseleave="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828A4 4 0 1012.343 4.343L5.757 10.929"/>
                            </svg>
                            Choose files to upload
                        </label>
                        <input type="file" name="solution_files[]" id="solution_files" multiple
                               style="display:none;" onchange="showSolutionFiles(this)">

                        <ul id="solution-file-list" style="display:flex;flex-direction:column;gap:4px;"></ul>

                        <button type="submit" class="btn btn-primary" style="align-self:flex-start;">
                            Submit
                        </button>
                    </form>

                    @if($mySolutions->isNotEmpty())
                        <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border);">
                            <p class="section-title">Your Submissions</p>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                @foreach($mySolutions as $sol)
                                    <a href="{{ route('solutions.download', $sol) }}" class="file-pill">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:var(--red)">
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
            @endif

        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Comments --}}
            <div class="card card-body">
                <p class="section-title">Comments</p>

                <div style="display:flex;flex-direction:column;">
                    @forelse($task->comments as $comment)
                        <div class="comment-item">
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
                                <div style="width:22px;height:22px;border-radius:50%;background:var(--red);
                                            color:#fff;display:flex;align-items:center;justify-content:center;
                                            font-size:10px;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <p style="font-size:12px;font-weight:600;color:var(--text);">
                                    {{ $comment->user->name }}
                                    <span style="font-weight:400;color:var(--muted);margin-left:4px;">
                                        &middot; {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </p>
                            </div>
                            <p style="font-size:13px;color:var(--text);line-height:1.5;padding-left:28px;">
                                {{ $comment->body }}
                            </p>
                        </div>
                    @empty
                        <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">No comments yet.</p>
                    @endforelse
                </div>

                <form action="{{ route('tasks.comment', $task) }}" method="POST"
                      style="margin-top:12px;display:flex;flex-direction:column;gap:8px;">
                    @csrf
                    <textarea name="body" rows="3" placeholder="Write a comment…" required
                              class="form-textarea"
                              style="font-size:13px;"></textarea>
                    <button type="submit" class="btn btn-primary" style="align-self:flex-end;padding:7px 16px;font-size:13px;">
                        Post
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
            li.style.cssText = 'font-size:12px;color:var(--text);background:var(--bg);border:1px solid var(--border);'
                             + 'border-radius:6px;padding:5px 10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;';
            li.textContent = f.name;
            list.appendChild(li);
        });
    }
    </script>
</x-app-layout>