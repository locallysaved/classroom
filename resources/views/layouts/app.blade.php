<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ClassRoom') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
       :root {
            --red:      #D94F4F;
            --red-dark: #C13F3F;
            --red-light:#F2DADA;
            --sidebar:  #B03A3A;
            --text:     #1A1A2E;
            --muted:    #7A4A4A;
            --border:   #E8C8C8;
            --bg:       #F7EDED;
            --white:    #FCEAEA;
        }
        .dark {
            --red:      #8B2525;
            --red-dark: #A83535;
            --red-light:#6B2F2F;
            --sidebar:  #2A0D0D;
            --text:     #FAE8E8;
            --muted:    #D4A0A0;
            --border:   #7A3535;
            --bg:       #3D1515;
            --white:    #521C1C;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            transition: background .2s, color .2s;
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
        .sidebar-logo a {
            text-decoration: none;
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
        .page-header a { color: rgba(255,255,255,.75); }
        .page-header a:hover { color: #fff; }
        .page-header p { color: rgba(255,255,255,.7); }

        /* ── Header avatar + dropdown ── */
        .header-avatar-wrap {
            position: relative;
            flex-shrink: 0;
        }
        .header-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,.2);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .15s, box-shadow .15s;
            border: 2px solid rgba(255,255,255,.4);
            text-decoration: none;
        }
        .header-avatar img {
            width: 100%; height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .header-avatar:hover,
        .header-avatar-wrap:hover .header-avatar {
            opacity: .88;
            box-shadow: 0 0 0 3px rgba(255,255,255,.4);
        }
        .header-dropdown {
            position: absolute;
            top: 100%;
            padding-top: 10px;
            right: 0;
            width: 220px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-6px);
            transition: opacity .15s ease, transform .15s ease;
        }
        .header-avatar-wrap:hover .header-dropdown {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }
        .header-dropdown-user {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-dropdown-user img {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .header-dropdown-user .avatar-initials {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--red);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700;
            flex-shrink: 0;
        }
        .header-dropdown-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .header-dropdown-email {
            font-size: 11px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .header-dropdown-role {
            display: inline-block;
            margin-top: 3px;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 99px;
            text-transform: capitalize;
        }
        .role-admin   { background: #EDE9FE; color: #6D28D9; }
        .role-teacher { background: #DBEAFE; color: #1D4ED8; }
        .role-student { background: #F3F4F6; color: #6B7280; }
        .header-dropdown-links {
            padding: 6px 0;
        }
        .header-dropdown-links a,
        .header-dropdown-links button {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            transition: background .12s;
            text-align: left;
            font-family: inherit;
        }
        .header-dropdown-links a:hover,
        .header-dropdown-links button:hover { background: var(--red-light); }
        .header-dropdown-links svg { width: 15px; height: 15px; color: var(--muted); flex-shrink: 0; }
        .header-dropdown-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 4px 0;
        }
        .header-dropdown-links .logout-btn { color: var(--red); }
        .header-dropdown-links .logout-btn svg { color: var(--red); }

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
        .btn-primary { background: var(--red); color: #fff; }
        .btn-primary:hover { background: var(--red-dark); }
        .btn-ghost { background: transparent; color: var(--muted); }
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

        /* ── Dark mode toggle ── */
        .dark-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,.15);
            border: none;
            cursor: pointer;
            color: #fff;
            flex-shrink: 0;
            transition: background .15s;
        }
        .dark-toggle:hover { background: rgba(255,255,255,.28); }
        .dark-toggle svg { width: 18px; height: 18px; }
        .icon-moon { display: block; }
        .icon-sun  { display: none; }
        .dark .icon-moon { display: none; }
        .dark .icon-sun  { display: block; }

        /* ── Mobile top bar ── */
        .mobile-topbar {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 56px;
            background: var(--sidebar);
            z-index: 60;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
        }
        .mobile-topbar-logo {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.3px;
            text-decoration: none;
        }
        .mobile-topbar-logo em { color: rgba(255,255,255,.65); font-style: normal; }
        .mobile-menu-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #fff;
            padding: 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            transition: background .15s;
        }
        .mobile-menu-btn:hover { background: rgba(0,0,0,.2); }

        /* ── Mobile drawer overlay ── */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 70;
        }
        .mobile-overlay.open { display: block; }

        /* ── Mobile drawer ── */
        .mobile-drawer {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 240px;
            background: var(--sidebar);
            z-index: 80;
            display: flex;
            flex-direction: column;
            transform: translateX(-100%);
            transition: transform .25s ease;
        }
        .mobile-drawer.open { transform: translateX(0); }
        .mobile-drawer-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(0,0,0,.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .mobile-drawer-close {
            background: none;
            border: none;
            color: rgba(255,255,255,.7);
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            display: flex;
            transition: color .15s;
        }
        .mobile-drawer-close:hover { color: #fff; }
        .mobile-drawer nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .mobile-drawer nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,.7);
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            transition: background .15s, color .15s;
        }
        .mobile-drawer nav a:hover,
        .mobile-drawer nav a.active { background: rgba(0,0,0,.2); color: #fff; }
        .mobile-drawer nav svg { width: 18px; height: 18px; flex-shrink: 0; }
        .mobile-drawer-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(0,0,0,.15);
        }
        .mobile-drawer-footer button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,.6);
            font-size: 15px;
            font-weight: 500;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: background .15s, color .15s;
        }
        .mobile-drawer-footer button:hover { background: rgba(0,0,0,.2); color: #fff; }

        /* ── Mobile drawer user info ── */
        .mobile-drawer-user {
            padding: 16px 16px 12px;
            border-bottom: 1px solid rgba(0,0,0,.15);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mobile-drawer-user img {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .mobile-drawer-user .name {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }
        .mobile-drawer-user .email {
            font-size: 11px;
            color: rgba(255,255,255,.55);
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .mobile-topbar { display: flex; }
            .main-wrap { margin-left: 0; padding-top: 56px; }
            .page-header { padding: 12px 16px; }
            .page-header h1 { font-size: 17px; }
            .page-content { padding: 16px; }
            .header-desktop-only { display: none !important; }
        }
    </style>
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body>

{{-- ── Mobile top bar ── --}}
<div class="mobile-topbar">
    <a href="{{ route('classes.index') }}" class="mobile-topbar-logo">Class<em>Room</em></a>
    <div style="display:flex;align-items:center;gap:8px;">
        <button class="dark-toggle" id="darkToggleMobile" aria-label="Toggle dark mode">
            <svg class="icon-moon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
            </svg>
            <svg class="icon-sun" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07l-.71.71M6.34 17.66l-.71.71M17.66 17.66l-.71-.71M6.34 6.34l-.71-.71M12 8a4 4 0 100 8 4 4 0 000-8z"/>
            </svg>
        </button>
        <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open menu">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</div>

{{-- ── Mobile overlay ── --}}
<div class="mobile-overlay" id="mobileOverlay"></div>

{{-- ── Mobile drawer ── --}}
<div class="mobile-drawer" id="mobileDrawer">
    <div class="mobile-drawer-header">
        <span style="font-size:16px;font-weight:700;color:#fff;letter-spacing:-.3px;">
            Class<em style="color:rgba(255,255,255,.65);font-style:normal;">Room</em>
        </span>
        <button class="mobile-drawer-close" id="mobileDrawerClose" aria-label="Close menu">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- User info in mobile drawer --}}
    <div class="mobile-drawer-user">
        <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}">
        <div>
            <div class="name">{{ Auth::user()->name }}</div>
            <div class="email">{{ Auth::user()->email }}</div>
        </div>
    </div>

    <nav>
        <a href="{{ route('classes.index') }}" class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Classes
        </a>
        @if(auth()->user()->isStudent())
        <a href="{{ route('classes.join.form') }}" class="{{ request()->routeIs('classes.join.form') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Join a Class
        </a>
        @endif
        @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
        <a href="{{ route('classes.create') }}" class="{{ request()->routeIs('classes.create') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Class
        </a>
        @endif
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Users
        </a>
        <a href="{{ route('admin.activity') }}" class="{{ request()->routeIs('admin.activity') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Activity
        </a>
        @endif
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
    </nav>
    <div class="mobile-drawer-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
                Log Out
            </button>
        </form>
    </div>
</div>

<div class="app-shell">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="{{ route('classes.index') }}">
                <span>Class<em>Room</em></span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('classes.index') }}" class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Classes
            </a>

            @if(auth()->user()->isStudent())
            <a href="{{ route('classes.join.form') }}" class="{{ request()->routeIs('classes.join.form') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Join a Class
            </a>
            @endif

            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
            <a href="{{ route('classes.create') }}" class="{{ request()->routeIs('classes.create') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Class
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Users
            </a>
            <a href="{{ route('admin.activity') }}" class="{{ request()->routeIs('admin.activity') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Activity
            </a>
            @endif
        </nav>
    </aside>

    {{-- ── Main ── --}}
    <div class="main-wrap">
        @isset($header)
            <header class="page-header">
                <div style="flex:1;">{{ $header }}</div>

                <button class="dark-toggle header-desktop-only" id="darkToggle" title="Toggle dark mode" aria-label="Toggle dark mode">
                    <svg class="icon-moon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                    <svg class="icon-sun" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07l-.71.71M6.34 17.66l-.71.71M17.66 17.66l-.71-.71M6.34 6.34l-.71-.71M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </button>

                {{-- Avatar with hover dropdown --}}
                <div class="header-avatar-wrap header-desktop-only">
                    <div class="header-avatar">
                        <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}">
                    </div>

                    <div class="header-dropdown">
                        {{-- User info --}}
                        <div class="header-dropdown-user">
                            <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}">
                            <div style="min-width:0;">
                                <div class="header-dropdown-name">{{ Auth::user()->name }}</div>
                                <div class="header-dropdown-email">{{ Auth::user()->email }}</div>
                                <span class="header-dropdown-role role-{{ Auth::user()->role }}">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>
                        </div>

                        {{-- Links --}}
                        <div class="header-dropdown-links">
                            <a href="{{ route('profile.edit') }}">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profile
                            </a>

                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.users') }}">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                User Management
                            </a>
                            @endif

                            <hr class="header-dropdown-divider">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </header>
        @endisset

        <main class="page-content">
            {{ $slot }}
        </main>
    </div>

</div>

<script>
    // Dark mode toggle
    function toggleDark() {
        const html = document.documentElement;
        const isDark = html.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }
    document.getElementById('darkToggle')?.addEventListener('click', toggleDark);
    document.getElementById('darkToggleMobile')?.addEventListener('click', toggleDark);

    // Mobile drawer
    const drawer  = document.getElementById('mobileDrawer');
    const overlay = document.getElementById('mobileOverlay');
    const openBtn = document.getElementById('mobileMenuBtn');
    const closeBtn = document.getElementById('mobileDrawerClose');

    function openDrawer()  { drawer.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow='hidden'; }
    function closeDrawer() { drawer.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow=''; }

    openBtn?.addEventListener('click', openDrawer);
    closeBtn?.addEventListener('click', closeDrawer);
    overlay?.addEventListener('click', closeDrawer);
    drawer?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));
</script>
</body>
</html>