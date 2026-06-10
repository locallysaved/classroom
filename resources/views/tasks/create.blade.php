<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('classes.show', $class) }}"
               style="color:var(--muted);display:flex;align-items:center;text-decoration:none;
                      padding:6px;border-radius:6px;transition:background .15s;"
               onmouseenter="this.style.background='var(--bg)'"
               onmouseleave="this.style.background='transparent'">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 style="font-size:20px;">Add Task</h1>
                <p style="font-size:13px;color:var(--muted);margin-top:2px;">
                    to <span style="color:var(--red);font-weight:600;">{{ $class->name }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div style="max-width:560px;margin:0 auto;">

        @if($errors->any())
            <div class="alert-error" style="margin-bottom:16px;">
                <ul style="list-style:disc;padding-left:16px;display:flex;flex-direction:column;gap:4px;">
                    @foreach($errors->all() as $error)
                        <li style="font-size:13px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Red card matching wireframe --}}
        <div style="background:var(--red);border-radius:12px;padding:24px;
                    display:flex;flex-direction:column;gap:14px;">

            <form action="{{ route('tasks.store') }}" method="POST"
      enctype="multipart/form-data"
      style="display:flex;flex-direction:column;gap:12px;">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">

                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       placeholder="add task name"
                       required
                       style="width:100%;border:none;border-radius:8px;
                              padding:11px 14px;font-size:14px;font-family:inherit;
                              color:var(--text);background:#fff;outline:none;
                              box-shadow:0 1px 3px rgba(0,0,0,.08);">

                <textarea name="content" id="content" rows="4"
                          placeholder="access for this content info"
                          required
                          style="width:100%;border:none;border-radius:8px;
                                 padding:11px 14px;font-size:14px;font-family:inherit;
                                 color:var(--text);background:#fff;outline:none;
                                 resize:vertical;box-shadow:0 1px 3px rgba(0,0,0,.08);">{{ old('content') }}</textarea>

<input type="date" name="due_date" id="due_date"
       value="{{ old('due_date') }}"
       min="{{ date('Y-m-d') }}"
       style="width:100%;border:none;border-radius:8px;
              padding:11px 14px;font-size:14px;font-family:inherit;
              color:var(--text);background:#fff;outline:none;
              box-shadow:0 1px 3px rgba(0,0,0,.08);">                
<div>
                    {{-- File upload --}}
                <div>
                    <label for="files"
                           style="display:flex;align-items:center;justify-content:center;gap:8px;
                                  background:#fff;border-radius:8px;padding:11px 14px;
                                  font-size:13px;color:var(--muted);cursor:pointer;
                                  border:2px dashed rgba(0,0,0,.15);
                                  box-shadow:0 1px 3px rgba(0,0,0,.08);transition:border-color .15s;"
                           onmouseenter="this.style.borderColor='var(--red-dark)'"
                           onmouseleave="this.style.borderColor='rgba(0,0,0,.15)'">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828A4 4 0 1012.343 4.343L5.757 10.929"/>
                        </svg>
                        add files
                    </label>
                    <input type="file" name="files[]" id="files" multiple
                           style="display:none;" onchange="showFiles(this)">
                    <ul id="file-list" style="margin-top:8px;display:flex;flex-direction:column;gap:4px;"></ul>
                </div>
                <br>
                    <button type="submit"
                            style="background:var(--red-dark);color:#fff;border:none;
                                   border-radius:8px;padding:9px 20px;font-size:13px;
                                   font-weight:600;font-family:inherit;cursor:pointer;
                                   transition:opacity .15s;"
                            onmouseenter="this.style.opacity='.85'"
                            onmouseleave="this.style.opacity='1'">
                        add task
                    </button>
                </div>
            </form>

        </div>
    </div>
    <script>
    function showFiles(input) {
        const list = document.getElementById('file-list');
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