@php
    use App\Support\LotusInvitationPresenter;
    $templateKey = $templateKey ?? 'lotus';
    $viewData = LotusInvitationPresenter::prepare(
        is_array($data ?? null) ? $data : [],
        $templateKey,
        $eventType ?? 'wedding',
    );
    
    extract($viewData, EXTR_SKIP);
    $defaultMusic = is_file(public_path('assets/music/wedding.mp3')) ? asset('assets/music/wedding.mp3') : '';
    $pickMusic = function ($v) use (&$pickMusic) {
        if (is_array($v)) {
            foreach (['path', 'origin_url', 'url', 'src', 'file'] as $k) {
                $r = $pickMusic($v[$k] ?? null);
                if ($r !== '') {
                    return $r;
                }
            }
            return '';
        }
        return is_string($v) ? trim($v) : '';
    };
    $musicRaw = '';
    foreach (
        [
            $music ?? null,
            $data['music'] ?? null,
            $w['music'] ?? null,
            $data['music_path'] ?? null,
            $data['music_origin_url'] ?? null,
            $data['music_url'] ?? null,
        ]
        as $candidate
    ) {
        $musicRaw = $pickMusic($candidate);
        if ($musicRaw !== '') {
            break;
        }
    }

    $musicRaw = str_replace('\\', '/', $musicRaw);
    $musicYoutubeId = null;
    $musicSrc = '';

    if ($musicRaw !== '') {
        if (
            preg_match(
                '~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:[^#]*&)?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~i',
                $musicRaw,
                $m,
            )
        ) {
            $musicYoutubeId = $m[1];
        } elseif (preg_match('#^(https?://|//)#i', $musicRaw)) {
            $musicSrc = $musicRaw;
        } elseif (preg_match('#^/?assets/#', $musicRaw)) {
            $musicSrc = asset(ltrim($musicRaw, '/'));
        } else {
            $rel = ltrim(preg_replace('#^/?(public/|storage/)#', '', $musicRaw), '/');
            $musicSrc = asset('storage/' . $rel);
        }
    }
    if ($musicSrc === '' && !$musicYoutubeId) {
        $musicSrc = $defaultMusic;
    }
@endphp
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1f0a33">
    <title>{{ $groom['name'] }} &amp; {{ $bride['name'] }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Moul&family=Noto+Serif+Khmer:wght@400;600;700&family=Noto+Sans+Khmer:wght@400;600;700&display=swap"
        rel="stylesheet">

    @php
        $cssPath = public_path('assets/css/lotus.css');
        $cssVersion = is_file($cssPath) ? filemtime($cssPath) : 1;
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/lotu.css') }}?v={{ $cssVersion }}">

    <noscript>
        <style>
            .gc {
                display: none !important;
            }
        </style>
    </noscript>
    <script>
        document.documentElement.classList.add('js');
    </script>
</head>

<body>
    @include('invitation-templates.lotus.cover')
    <main class="gw" id="gw" data-template="{{ $templateKey }}">
        @include('invitation-templates.lotus.card')
        @include('invitation-templates.lotus.divider')
        @include('invitation-templates.lotus.couple')
        @include('invitation-templates.lotus.divider', ['variant' => 'band'])
        @include('invitation-templates.lotus.countdown')
        @include('invitation-templates.lotus.divider')
        @include('invitation-templates.lotus.story')
        @include('invitation-templates.lotus.divider', ['variant' => 'band'])
        @include('invitation-templates.lotus.schedule')
        @include('invitation-templates.lotus.divider')
        @include('invitation-templates.lotus.gallery')
        @include('invitation-templates.lotus.divider', ['variant' => 'band'])
        @include('invitation-templates.lotus.dress-code')
        @include('invitation-templates.lotus.divider')
        @include('invitation-templates.lotus.rsvp')
        @include('invitation-templates.lotus.divider', ['variant' => 'band'])
        @include('invitation-templates.lotus.gift')
        @include('invitation-templates.lotus.divider')
        @include('invitation-templates.lotus.apology')
        @include('invitation-templates.lotus.divider', ['variant' => 'band'])
        @include('invitation-templates.lotus.thanks')
    </main>

    @if ($musicSrc || $musicYoutubeId)
        @if ($musicYoutubeId)
            <div aria-hidden="true"
                style="position:fixed;left:0;bottom:0;width:200px;height:200px;opacity:.01;pointer-events:none;z-index:-1;overflow:hidden">
                <iframe id="yt-player" width="200" height="200" title="Music" frameborder="0"
                    allow="autoplay; encrypted-media"
                    src="https://www.youtube-nocookie.com/embed/{{ $musicYoutubeId }}?enablejsapi=1&playsinline=1&controls=0&loop=1&playlist={{ $musicYoutubeId }}&rel=0&origin={{ urlencode(request()->getSchemeAndHttpHost()) }}"></iframe>
            </div>
        @else
            <audio id="bgm" src="{{ $musicSrc }}" loop preload="auto"></audio>
        @endif

        <button class="g-music hide" id="mbtn" type="button" aria-label="Music" data-yt="{{ $musicYoutubeId }}"
            data-fallback="{{ $defaultMusic }}">
            {!! $ic('music', 20) !!}
        </button>
    @endif

    @include('invitation-templates.lotus.scripts')
    @include('invitation-templates.lotus.motion')
</body>

</html>
