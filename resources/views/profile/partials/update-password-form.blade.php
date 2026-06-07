<section>
    <header style="margin-bottom:24px;">
        <h2 style="font-size:15px;font-weight:700;color:var(--text);">Update Password</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:4px;">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="display:flex;flex-direction:column;gap:18px;">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="form-input"
                   autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <p style="font-size:12px;color:var(--red);margin-top:5px;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="form-label">New Password</label>
            <input id="update_password_password" name="password" type="password"
                   class="form-input"
                   autocomplete="new-password">
            @error('password', 'updatePassword')
                <p style="font-size:12px;color:var(--red);margin-top:5px;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="form-input"
                   autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <p style="font-size:12px;color:var(--red);margin-top:5px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display:flex;align-items:center;gap:12px;padding-top:4px;">
            <button type="submit" class="btn btn-primary">Save</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   style="font-size:13px;color:#059669;font-weight:500;">Saved.</p>
            @endif
        </div>
    </form>
</section>