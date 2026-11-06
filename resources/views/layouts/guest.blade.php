<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Admin Login' }} | Wedding Platform</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (app()->getLocale() === 'km')
        {{-- Khmer Font --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap">

        <style>
            body,
            .title,
            .title-two,
            .footer__widget-title {
                font-family: "Noto Sans Khmer", sans-serif;
                font-stretch: condensed;
            }
        </style>
    @else
        {{-- English Font --}}
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap">

        <style>
            body,
            .title,
            .title-two,
            .footer__widget-title {
                font-family: "Space Grotesk", sans-serif;
            }
        </style>
    @endif
    @livewireStyles
</head>

<body class="min-h-screen bg-slate-50 antialiased">

    {{ $slot }}

    @livewireScripts
</body>

</html>
