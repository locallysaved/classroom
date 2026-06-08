<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
<body style="margin:0;min-height:100vh;background:var(--bg);
             display:flex;align-items:center;justify-content:center;">

    {{ $slot }}

</body>
</html>