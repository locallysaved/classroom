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
            <h1>Create Class</h1>
        </div>
    </x-slot>

    <div style="max-width:600px;margin:0 auto;">
        <div class="card card-body">

            @if($errors->any())
                <div class="alert-error">
                    <ul style="list-style:disc;padding-left:16px;display:flex;flex-direction:column;gap:4px;">
                        @foreach($errors->all() as $error)
                            <li style="font-size:13px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('classes.store') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf

                <div>
                    <label for="name" class="form-label">Class Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="form-input"
                           placeholder="e.g. Mathematics 101" required>
                </div>

                <div>
                    <label for="description" class="form-label">
                        Description
                        <span style="color:var(--muted);font-weight:400;margin-left:4px;">(optional)</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="form-textarea"
                              placeholder="Brief description of the class...">{{ old('description') }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:4px;">
                    <a href="{{ route('classes.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Class</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
