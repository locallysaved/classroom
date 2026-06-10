<x-app-layout>
    <x-slot name="header">
        <h1>Profile</h1>
    </x-slot>

    <div style="max-width:800px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">

        {{-- Avatar --}}
        <div class="card card-body">
            <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:16px;">Profile Picture</div>

            <div style="display:flex;align-items:center;gap:24px;">
            <img id="avatar-preview"
                src="{{ auth()->user()->avatarUrl() }}"
                alt="{{ auth()->user()->name }}"
                style="width:80px;height:80px;border-radius:50%;object-fit:cover;flex-shrink:0;">

                <div style="flex:1;">
                    @if(session('status') === 'avatar-updated')
                        <p style="font-size:14px;color:var(--success);margin-bottom:8px;">Avatar updated successfully.</p>
                    @endif

                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" style="display:flex;align-items:center;gap:12px;">
                        @csrf
                        @method('PATCH')
                        <label for="avatar"
                            style="display:flex;align-items:center;gap:8px;cursor:pointer;
                                    border:1.5px solid var(--border);border-radius:8px;
                                    padding:9px 14px;font-size:13px;color:var(--muted);
                                    font-family:inherit;transition:border-color .15s;background:var(--bg);"
                            onmouseenter="this.style.borderColor='var(--orange)'"
                            onmouseleave="this.style.borderColor='var(--border)'">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828A4 4 0 1012.343 4.343L5.757 10.929"/>
                            </svg>
                            <span id="avatar-label">Choose image</span>
                        </label>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                            style="display:none;" onchange="previewAvatar(this)">
                            <button type="submit" id="avatar-submit"
                                    class="btn btn-primary"
                                    disabled
                                    style="opacity:.4;cursor:not-allowed;">
                                Upload
                            </button>
                        </form>
                    @error('avatar')
                        <p style="font-size:14px;color:var(--danger);margin-top:8px;">{{ $message }}</p>
                    @enderror
                    <p style="font-size:12px;color:var(--muted);margin-top:8px;">JPG, PNG, GIF or WebP. Max 2MB.</p>
                </div>
            </div>
        </div>

        <div class="card card-body">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card card-body">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card card-body">
            @include('profile.partials.delete-user-form')
        </div>

    </div>

    <script>
        function previewAvatar(input) {
            if (!input.files || !input.files[0]) return;
            const preview = document.getElementById('avatar-preview');
            const submit  = document.getElementById('avatar-submit');
            preview.src = URL.createObjectURL(input.files[0]);
            preview.style.border = '2.5px solid #e8621a';
            document.getElementById('avatar-label').textContent = input.files[0].name;
            submit.disabled = false;
            submit.style.opacity = '1';
            submit.style.cursor = 'pointer';
        }
    </script>
</x-app-layout>