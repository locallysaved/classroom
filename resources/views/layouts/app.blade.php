<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ClassRoom') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --red:      #D94F4F;
            --red-dark: #C13F3F;
            --red-light:#F2DADA;
            --sidebar:  #B03A3A;
            --sidebar-hover: #C13F3F;
            --text:     #1A1A2E;
            --muted:    #6B7280;
            --border:   #E5E7EB;
            --bg:       #F5F5F7;
            --white:    #FFFFFF;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Layout ── */
        .app-shell { display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: 220px;
            background: var(--sidebar);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
        }
        .sidebar-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(0,0,0,.15);
        }
        .sidebar-logo span {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.3px;
        }
        .sidebar-logo span em {
            color: rgba(255,255,255,.65);
            font-style: normal;
        }
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,.7);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: background .15s, color .15s;
        }
        .sidebar-nav a:hover { background: rgba(0,0,0,.15); color: #fff; }
        .sidebar-nav a.active { background: rgba(0,0,0,.2); color: #fff; }
        .sidebar-nav svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(0,0,0,.15);
        }
        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,.6);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: background .15s, color .15s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
        }
        .sidebar-logout:hover { background: rgba(0,0,0,.2); color: #fff; }

        /* ── Main ── */
        .main-wrap {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .page-header {
            background: var(--red);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .page-header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }
        /* make back-arrow links in header white too */
        .page-header a { color: rgba(255,255,255,.75); }
        .page-header a:hover { color: #fff; }
        .page-header p { color: rgba(255,255,255,.7); }
        .header-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: var(--red);
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
            text-decoration: none;
            transition: opacity .15s, box-shadow .15s;
        }
        .header-avatar:hover {
            opacity: .88;
            box-shadow: 0 0 0 3px rgba(255,255,255,.4);
        }
        .page-content {
            padding: 32px;
            flex: 1;
        }

        /* ── Cards ── */
        .card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .card-body { padding: 24px; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: background .15s, opacity .15s;
        }
        .btn-primary {
            background: var(--red);
            color: #fff;
        }
        .btn-primary:hover { background: var(--red-dark); }
        .btn-ghost {
            background: transparent;
            color: var(--muted);
        }
        .btn-ghost:hover { color: var(--text); }

        /* ── Alerts ── */
        .alert-success {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* ── Form controls ── */
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }
        .form-input, .form-textarea {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: var(--white);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-input:focus, .form-textarea:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(217,79,79,.12);
        }
        .form-textarea { resize: vertical; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-wrap { margin-left: 0; }
            .page-content { padding: 20px 16px; }
        }
    </style>
</head>
<body>
<div class="app-shell">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <span>Class<em>Room</em></span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('classes.index') }}"
               class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Classes
            </a>

            @if(auth()->user()->isStudent())
            <a href="{{ route('classes.join.form') }}"
               class="{{ request()->routeIs('classes.join.form') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Join a Class
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                    </svg>
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main ── --}}
    <div class="main-wrap">
        @isset($header)
            <header class="page-header">
                <div style="flex:1;">{{ $header }}</div>
                <a href="{{ route('profile.edit') }}"
                   class="header-avatar"
                   title="{{ Auth::user()->name }} — Profile">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>
            </header>
        @endisset

        <main class="page-content">
            {{ $slot }}
        </main>
    </div>

</div>
</body>
</html>