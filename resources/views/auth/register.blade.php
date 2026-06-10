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
                <h1 style="font-size:22px;font-weight:700;color:var(--text);margin:0;">Create an account</h1>
                <p style="font-size:13px;color:var(--muted);margin-top:4px;">Join and get started</p>
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

                    <form action="{{ route('register') }}" method="POST"
                          style="display:flex;flex-direction:column;gap:16px;">
                        @csrf

                        {{-- Name --}}
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="name"
                                   style="font-size:12px;font-weight:600;color:var(--text);
                                          text-transform:uppercase;letter-spacing:.05em;">
                                Full name
                            </label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name') }}"
                                   required autofocus autocomplete="name"
                                   placeholder="Jane Smith"
                                   style="width:100%;border:1.5px solid var(--border);border-radius:8px;
                                          padding:10px 14px;font-size:14px;font-family:inherit;
                                          color:var(--text);background:var(--bg);outline:none;
                                          transition:border-color .15s;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--red)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>

                        {{-- Email --}}
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="email"
                                   style="font-size:12px;font-weight:600;color:var(--text);
                                          text-transform:uppercase;letter-spacing:.05em;">
                                Email
                            </label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email') }}"
                                   required autocomplete="username"
                                   placeholder="you@example.com"
                                   style="width:100%;border:1.5px solid var(--border);border-radius:8px;
                                          padding:10px 14px;font-size:14px;font-family:inherit;
                                          color:var(--text);background:var(--bg);outline:none;
                                          transition:border-color .15s;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--red)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>

                        {{-- Role --}}
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label style="font-size:12px;font-weight:600;color:var(--text);
                                        text-transform:uppercase;letter-spacing:.05em;">
                                Role
                            </label>
                            <input type="hidden" name="role" id="role-input" value="{{ old('role', 'student') }}">
                            <div style="display:grid;grid-template-columns:1fr 1fr;
                                        border:1.5px solid var(--border);border-radius:8px;overflow:hidden;">
                                <button type="button" id="btn-student"
                                        onclick="selectRole('student')"
                                        style="padding:10px 14px;font-size:14px;font-weight:600;font-family:inherit;
                                            border:none;border-right:1px solid var(--border);cursor:pointer;
                                            transition:background .15s,color .15s;
                                            background:#e94d62;color:#fff;">
                                    Student
                                </button>
                                <button type="button" id="btn-teacher"
                                        onclick="selectRole('teacher')"
                                        style="padding:10px 14px;font-size:14px;font-weight:600;font-family:inherit;
                                            border:none;cursor:pointer;transition:background .15s,color .15s;
                                            background:#dddddd;color:var(--muted);">
                                    Teacher
                                </button>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="password"
                                   style="font-size:12px;font-weight:600;color:var(--text);
                                          text-transform:uppercase;letter-spacing:.05em;">
                                Password
                            </label>
                            <input type="password" name="password" id="password"
                                   required autocomplete="new-password"
                                   placeholder="••••••••"
                                   style="width:100%;border:1.5px solid var(--border);border-radius:8px;
                                          padding:10px 14px;font-size:14px;font-family:inherit;
                                          color:var(--text);background:var(--bg);outline:none;
                                          transition:border-color .15s;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--red)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>

                        {{-- Confirm Password --}}
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="password_confirmation"
                                   style="font-size:12px;font-weight:600;color:var(--text);
                                          text-transform:uppercase;letter-spacing:.05em;">
                                Confirm password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   required autocomplete="new-password"
                                   placeholder="••••••••"
                                   style="width:100%;border:1.5px solid var(--border);border-radius:8px;
                                          padding:10px 14px;font-size:14px;font-family:inherit;
                                          color:var(--text);background:var(--bg);outline:none;
                                          transition:border-color .15s;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--red)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                style="width:100%;background:#d94f4f;color:#fff;border:none;
                                       border-radius:8px;padding:11px;font-size:14px;font-weight:600;
                                       font-family:inherit;cursor:pointer;transition:opacity .15s;
                                       margin-top:4px;"
                                onmouseenter="this.style.opacity='.88'"
                                onmouseleave="this.style.opacity='1'">
                            Create account
                        </button>
                    </form>
                </div>
            </div>

            {{-- Login link --}}
            <p style="text-align:center;font-size:13px;color:var(--muted);">
                Already have an account?
                <a href="{{ route('login') }}"
                   style="color:#d94f4f;font-weight:600;text-decoration:none;"
                   onmouseenter="this.style.textDecoration='underline'"
                   onmouseleave="this.style.textDecoration='none'">
                    Sign in
                </a>
            </p>

        </div>
    </div>
    <script>
        function selectRole(role) {
            document.getElementById('role-input').value = role;
            const student = document.getElementById('btn-student');
            const teacher = document.getElementById('btn-teacher');
            if (role === 'student') {
                student.style.background = '#e94d62';
                student.style.color = '#fff';
                teacher.style.background = '#dddddd';
                teacher.style.color = 'var(--muted)';
            } else {
                teacher.style.background = '#e94d62';
                teacher.style.color = '#fff';
                student.style.background = '#dddddd';
                student.style.color = 'var(--muted)';
            }
        }
    </script>
</x-guest-layout>