<x-guest-layout>
    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;
                background:var(--bg);padding:24px;">

        <div style="width:100%;max-width:400px;display:flex;flex-direction:column;gap:20px;">

            {{-- Logo / Brand --}}
            <div style="text-align:center;">
                <div style="display:inline-flex;align-items:center;justify-content:center;
                            width:48px;height:48px;background:var(--red);border-radius:12px;
                            margin-bottom:14px;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                 M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h1 style="font-size:22px;font-weight:700;color:var(--text);margin:0;">Welcome back</h1>
                <p style="font-size:13px;color:var(--muted);margin-top:4px;">Sign in to your account</p>
            </div>

            {{-- Card --}}
            <div class="card" style="border-radius:14px;overflow:hidden;">

                {{-- Red top bar --}}
                <div style="height:5px;background:var(--red);"></div>

                <div class="card-body" style="padding:28px;">

                    @if($errors->any())
                        <div class="alert-error" style="margin-bottom:20px;">
                            <ul style="list-style:disc;padding-left:16px;
                                       display:flex;flex-direction:column;gap:4px;">
                                @foreach($errors->all() as $error)
                                    <li style="font-size:13px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert-success" style="margin-bottom:20px;font-size:13px;">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST"
                          style="display:flex;flex-direction:column;gap:16px;">
                        @csrf

                        {{-- Email --}}
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="email"
                                   style="font-size:12px;font-weight:600;color:var(--text);
                                          text-transform:uppercase;letter-spacing:.05em;">
                                Email
                            </label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email') }}"
                                   required autofocus autocomplete="username"
                                   placeholder="you@example.com"
                                   style="width:100%;border:1.5px solid var(--border);border-radius:8px;
                                          padding:10px 14px;font-size:14px;font-family:inherit;
                                          color:var(--text);background:var(--bg);outline:none;
                                          transition:border-color .15s;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--red)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>

                        {{-- Password --}}
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                <label for="password"
                                       style="font-size:12px;font-weight:600;color:var(--text);
                                              text-transform:uppercase;letter-spacing:.05em;">
                                    Password
                                </label>
                                @if(Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       style="font-size:12px;color:var(--red);text-decoration:none;"
                                       onmouseenter="this.style.textDecoration='underline'"
                                       onmouseleave="this.style.textDecoration='none'">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                            <input type="password" name="password" id="password"
                                   required autocomplete="current-password"
                                   placeholder="••••••••"
                                   style="width:100%;border:1.5px solid var(--border);border-radius:8px;
                                          padding:10px 14px;font-size:14px;font-family:inherit;
                                          color:var(--text);background:var(--bg);outline:none;
                                          transition:border-color .15s;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--red)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>

                        {{-- Remember me --}}
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="remember"
                                   style="width:15px;height:15px;accent-color:var(--red);cursor:pointer;">
                            <span style="font-size:13px;color:var(--muted);">Remember me</span>
                        </label>

                        {{-- Submit --}}
                        <button type="submit"
                                style="width:100%;background:var(--red);color:#fff;border:none;
                                       border-radius:8px;padding:11px;font-size:14px;font-weight:600;
                                       font-family:inherit;cursor:pointer;transition:opacity .15s;
                                       margin-top:4px;"
                                onmouseenter="this.style.opacity='.88'"
                                onmouseleave="this.style.opacity='1'">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>

            {{-- Register link --}}
            @if(Route::has('register'))
                <p style="text-align:center;font-size:13px;color:var(--muted);">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                       style="color:var(--red);font-weight:600;text-decoration:none;"
                       onmouseenter="this.style.textDecoration='underline'"
                       onmouseleave="this.style.textDecoration='none'">
                        Create one
                    </a>
                </p>
            @endif

        </div>
    </div>
</x-guest-layout>