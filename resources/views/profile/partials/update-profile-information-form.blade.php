<section>
    <header style="margin-bottom:24px;">
        <h2 style="font-size:15px;font-weight:700;color:var(--text);">Profile Information</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:4px;">
            Update your account's profile information and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" style="display:flex;flex-direction:column;gap:18px;">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text"
                   class="form-input"
                   value="{{ old('name', $user->name) }}"
                   required autofocus autocomplete="name">
            @error('name')
                <p style="font-size:12px;color:var(--red);margin-top:5px;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email"
                   class="form-input"
                   value="{{ old('email', $user->email) }}"
                   required autocomplete="username">
            @error('email')
                <p style="font-size:12px;color:var(--red);margin-top:5px;">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top:8px;">
                    <p style="font-size:13px;color:var(--muted);">
                        Your email address is unverified.
                        <button form="send-verification"
                                style="background:none;border:none;cursor:pointer;
                                       font-size:13px;color:var(--red);text-decoration:underline;padding:0;">
                            Click here to re-send the verification email.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p style="font-size:13px;color:#059669;margin-top:6px;font-weight:500;">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display:flex;align-items:center;gap:12px;padding-top:4px;">
            <button type="submit" class="btn btn-primary">Save</button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   style="font-size:13px;color:#059669;font-weight:500;">Saved.</p>
            @endif
        </div>
    </form>
</section>