<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Classroom') }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --red:       #e03131;
            --red-dark:  #c92a2a;
            --red-light: #fff5f5;
            --text:      #1a1a1a;
            --muted:     #868e96;
            --bg:        #f8f9fa;
            --border:    #dee2e6;
        }
    </style>
</head>
<body style="margin:0;min-height:100vh;background:var(--bg);display:flex;flex-direction:column;">

    {{-- Nav --}}
    <nav style="padding:16px 32px;display:flex;align-items:center;justify-content:space-between;
                border-bottom:1px solid var(--border);background:#fff;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;background:var(--red);border-radius:9px;
                        display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fff">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span style="font-size:16px;font-weight:700;color:var(--text);">
                {{ config('app.name', 'Classroom') }}
            </span>
        </div>

        <div style="display:flex;align-items:center;gap:10px;">
            @auth
                <a href="{{ url('/dashboard') }}"
                   style="font-size:13px;font-weight:600;color:var(--text);text-decoration:none;
                          padding:8px 18px;border-radius:8px;border:1.5px solid var(--border);
                          transition:border-color .15s;"
                   onmouseenter="this.style.borderColor='var(--red)'"
                   onmouseleave="this.style.borderColor='var(--border)'">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   style="font-size:13px;font-weight:600;color:var(--text);text-decoration:none;
                          padding:8px 18px;border-radius:8px;border:1.5px solid var(--border);
                          transition:border-color .15s;"
                   onmouseenter="this.style.borderColor='var(--red)'"
                   onmouseleave="this.style.borderColor='var(--border)'">
                    Log in
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       style="font-size:13px;font-weight:600;color:#fff;text-decoration:none;
                              padding:8px 18px;border-radius:8px;background:var(--red);
                              border:1.5px solid var(--red);transition:opacity .15s;"
                       onmouseenter="this.style.opacity='.88'"
                       onmouseleave="this.style.opacity='1'">
                        Register
                    </a>
                @endif
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <main style="flex:1;display:flex;align-items:center;justify-content:center;padding:60px 24px;">
        <div style="max-width:640px;width:100%;text-align:center;">

            <div style="display:inline-flex;align-items:center;gap:6px;background:var(--red-light);
                        color:var(--red);font-size:12px;font-weight:600;padding:5px 14px;
                        border-radius:20px;margin-bottom:24px;">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                For teachers & students
            </div>

            <h1 style="font-size:48px;font-weight:800;color:var(--text);line-height:1.15;margin-bottom:20px;">
                Your classroom,<br>
                <span style="color:var(--red);">all in one place.</span>
            </h1>

            <p style="font-size:16px;color:var(--muted);line-height:1.7;margin-bottom:36px;max-width:480px;margin-left:auto;margin-right:auto;">
                Create classes, assign tasks with files, collect student submissions,
                and keep everyone on the same page — simply and beautifully.
            </p>

            <div style="display:flex;align-items:center;justify-content:center;gap:12px;flex-wrap:wrap;">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       style="font-size:14px;font-weight:600;color:#fff;text-decoration:none;
                              padding:12px 28px;border-radius:10px;background:var(--red);
                              transition:opacity .15s;"
                       onmouseenter="this.style.opacity='.88'"
                       onmouseleave="this.style.opacity='1'">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       style="font-size:14px;font-weight:600;color:#fff;text-decoration:none;
                              padding:12px 28px;border-radius:10px;background:var(--red);
                              transition:opacity .15s;"
                       onmouseenter="this.style.opacity='.88'"
                       onmouseleave="this.style.opacity='1'">
                        Get started free
                    </a>
                    <a href="{{ route('login') }}"
                       style="font-size:14px;font-weight:600;color:var(--text);text-decoration:none;
                              padding:12px 28px;border-radius:10px;border:1.5px solid var(--border);
                              transition:border-color .15s;"
                       onmouseenter="this.style.borderColor='var(--red)'"
                       onmouseleave="this.style.borderColor='var(--border)'">
                        Log in
                    </a>
                @endauth
            </div>

            {{-- Feature pills --}}
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;
                        flex-wrap:wrap;margin-top:48px;">
                @foreach(['Create classes', 'Assign tasks', 'Attach files', 'Student submissions', 'Comments'] as $feature)
                    <span style="font-size:12px;color:var(--muted);background:#fff;
                                 padding:5px 14px;border-radius:20px;border:1px solid var(--border);">
                        {{ $feature }}
                    </span>
                @endforeach
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer style="padding:20px 32px;border-top:1px solid var(--border);
                   display:flex;align-items:center;justify-content:center;">
        <p style="font-size:12px;color:var(--muted);">
            {{ config('app.name', 'Classroom') }} &copy; {{ date('Y') }}
        </p>
    </footer>

</body>
</html>