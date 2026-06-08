<x-guest-layout>
    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg);">
        <div style="width:100%;max-width:400px;padding:16px;">

            {{-- Logo / Title --}}
            <div style="text-align:center;margin-bottom:28px;">
                <div style="width:48px;height:48px;background:var(--red);border-radius:12px;
                            margin:0 auto 12px;display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#fff">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h1 style="font-size:22px;font-weight:700;color:var(--text);">Welcome back</h1>
                <p style="font-size:13px;color:var(--muted);margin-top:4px;">Sign in to your account</p>
            </div>

            <div style="background:#fff;border-radius:14px;padding:28px;
                        box-shadow:0 2px 12px rgba(0,0,0,.07);border:1px solid var(--border);">

                @if (session('status'))
                    <div style="background:var(--red-light);color:var(--red);font-size:13px;
                                border-radius:8px;padding:10px 14px;margin-bottom:16px;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}"
                      style="display:flex;flex-direction:column;gap:16px;">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" style="display:block;font-size:13px;font-weight:600;
                                                   color:var(--text);margin-bottom:6px;">
                            Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               style="width:100%;border:1.5px solid var(--border);border-radius:8px;
                                      padding:10px 14px;font-size:14px;font-family:inherit;
                                      color:var(--text);background:#fff;outline:none;
                                      transition:border-color .15s;"
                               onfocus="this.style.borderColor='var(--red)'"
                               onblur="this.style.borderColor='var(--border)'">
                        @error('email')
                            <p style="font-size:12px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label for="password" style="font-size:13px;font-weight:600;color:var(--text);">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   style="font-size:12px;color:var(--red);text-decoration:none;">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password"
                               required autocomplete="current-password"
                               style="width:100%;border:1.5px solid var(--border);border-radius:8px;
                                      padding:10px 14px;font-size:14px;font-family:inherit;
                                      color:var(--text);background:#fff;outline:none;
                                      transition:border-color .15s;"
                               onfocus="this.style.borderColor='var(--red)'"
                               onblur="this.style.borderColor='var(--border)'">
                        @error('password')
                            <p style="font-size:12px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input id="remember_me" type="checkbox" name="remember"
                               style="width:15px;height:15px;accent-color:var(--red);cursor:pointer;">
                        <label for="remember_me" style="font-size:13px;color:var(--muted);cursor:pointer;">
                            Remember me
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            style="width:100%;background:var(--red);color:#fff;border:none;
                                   border-radius:8px;padding:11px;font-size:14px;font-weight:600;
                                   font-family:inherit;cursor:pointer;transition:opacity .15s;"
                            onmouseenter="this.style.opacity='.88'"
                            onmouseleave="this.style.opacity='1'">
                        Sign in
                    </button>
                </form>
            </div>

            @if (Route::has('register'))
                <p style="text-align:center;font-size:13px;color:var(--muted);margin-top:20px;">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                       style="color:var(--red);font-weight:600;text-decoration:none;">
                        Register
                    </a>
                </p>
            @endif

        </div>
    </div>
</x-guest-layout>
