@php
    use App\Support\GoldInvitationPresenter;

    $templateKey = $templateKey ?? 'gold';

    $viewData = GoldInvitationPresenter::prepare(
        is_array($data ?? null) ? $data : [],
        $templateKey,
        $eventType ?? 'wedding'
    );

    extract($viewData, EXTR_SKIP);
@endphp
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $groom['name'] }} &amp; {{ $bride['name'] }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Moul&family=Noto+Serif+Khmer:wght@400;600;700&family=Noto+Sans+Khmer:wght@400;600;700&display=swap"
        rel="stylesheet">

    @php
        $cssPath = public_path('assets/css/gold.css');
        $cssVersion = is_file($cssPath) ? filemtime($cssPath) : 1;
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/gold.css') }}?v={{ $cssVersion }}">

    <noscript>
        <style>
            .gc { display: none !important; }
        </style>
    </noscript>
    <script>
        document.documentElement.classList.add('js');
    </script>
</head>
<body>
    @include('invitation-templates.gold.cover')

    <main class="gw" id="gw" data-template="{{ $templateKey }}">
        @include('invitation-templates.gold.card')
        @include('invitation-templates.gold.couple')
        @include('invitation-templates.gold.countdown')
        @include('invitation-templates.gold.story')
        @include('invitation-templates.gold.schedule')
        @include('invitation-templates.gold.gallery')
        @include('invitation-templates.gold.dress-code')
        @include('invitation-templates.gold.rsvp')
        @include('invitation-templates.gold.gift')
        @include('invitation-templates.gold.apology')
        @include('invitation-templates.gold.thanks')
    </main>

    @if ($music)
        <audio id="bgm" src="{{ $music }}" loop preload="none"></audio>
        <button class="g-music hide" id="mbtn" type="button" aria-label="Music">
            {!! $ic('music', 20) !!}
        </button>
    @endif

    @include('invitation-templates.gold.scripts')
    @include('invitation-templates.gold.motion')
</body>
</html>
