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
                        <input type="file" name="avatar" accept="image/*"
                        onchange="previewAvatar(this)"
                        style="font-size:14px;color:var(--muted);">
                        <button type="submit"
                                class="btn btn-primary">
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
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>