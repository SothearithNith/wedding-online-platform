@php
    use App\Support\CleanInvitationPresenter;
    $templateKey = $templateKey ?? 'clean';
    $viewData = CleanInvitationPresenter::prepare(
        is_array($data ?? null) ? $data : [],
        $templateKey,
        $eventType ?? 'wedding',
    );
    extract($viewData, EXTR_SKIP);
    $resolveUrl = function ($v) use (&$resolveUrl) {
        if (is_array($v)) {
            foreach (['path', 'origin_url', 'url', 'src', 'file'] as $k) {
                $r = $resolveUrl($v[$k] ?? null);
                if ($r !== '') {
                    return $r;
                }
            }
            return '';
        }
        if (!is_string($v)) {
            return '';
        }
        $v = trim(str_replace('\\', '/', $v));
        if ($v === '') {
            return '';
        }
        if (preg_match('#^(https?://|//|data:)#i', $v)) {
            return $v;
        }
        if (preg_match('#^/?assets/#', $v)) {
            return asset(ltrim($v, '/'));
        }
        $rel = ltrim(preg_replace('#^/?(public/|storage/)#', '', $v), '/');
        return asset('storage/' . $rel);
    };

    // gallery: resolve every photo, drop empty/broken entries, re-index 0..n (the gallery uses the index)
    $gallery = collect($gallery ?? [])
        ->map($resolveUrl)
        ->filter()
        ->values()
        ->all();

    $defaultMusic = is_file(public_path('assets/music/wedding.mp3')) ? asset('assets/music/wedding.mp3') : '';
    $musicSrc = '';
    foreach ([$music ?? null, $data['music'] ?? null, $data['music_path'] ?? null, $data['music_origin_url'] ?? null, $data['music_url'] ?? null] as $candidate) {
        $musicSrc = $resolveUrl($candidate);
        if ($musicSrc !== '') {
            break;
        }
    }
    if ($musicSrc === '') {
        $musicSrc = $defaultMusic;
    }

    // YouTube link? -> use a hidden YouTube player instead of <audio>
    $ytId = null;
    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?(?:[^#]*&)?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~i', (string) $musicSrc, $ytm)) {
        $ytId = $ytm[1];
    }

    $rsvpId = $invitationId ?? ($invitation->id ?? null);
    $rsvpAction = $rsvpUrl ?? ($rsvpId ? route('invitations.rsvp', $rsvpId) : '');
    $guestRaw = request()->query('guest');
    $hasGuest = is_string($guestRaw) && $guestRaw !== '';
    $guestName = $hasGuest ? \Illuminate\Support\Str::limit($guestRaw, 60, '') : $K['coverGuest'];
    $kbachSvg =
        "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='30' height='26' viewBox='0 0 40 34'%3E%3Cpath d='M20 31C8 29 5 17 12 9c0 6 4 8 8 6-1-6 3-11 8-12-1 7 4 11 3 18-1 6-5 9-11 10z' fill='none' stroke='%23d9b44a' stroke-width='1.4'/%3E%3C/svg%3E";
    $orn =
        '<div class="cl-orn rv" data-v="zoom" aria-hidden="true"><span></span>' .
        $lotus(8, 34, 'cl-spin', 1.3) .
        '<span></span></div>';
@endphp
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#2a0a10">
    <meta name="color-scheme" content="light">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $groom['name'] }} &amp; {{ $bride['name'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Moul&family=Noto+Serif+Khmer:wght@400;600;700&family=Noto+Sans+Khmer:wght@400;600&family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500;1,600&family=Inter:wght@400;600&display=swap"
        rel="stylesheet">
    @php $cssPath = public_path('assets/css/clean.css'); @endphp
    <link rel="stylesheet"
        href="{{ asset('assets/css/clean.css') }}?v={{ is_file($cssPath) ? filemtime($cssPath) : 1 }}">
    <style>
        :root {
            --kbach: url("{!! $kbachSvg !!}")
        }
    </style>
    <script>
        document.documentElement.classList.add('js')
    </script>
</head>

<body>
    <div class="prog" id="prog" aria-hidden="true"></div>
    @include('invitation-templates.clean.cover')
    @include('invitation-templates.clean.card')
    <main>
        @include('invitation-templates.clean.divider', ['variant' => 'band'])
        @include('invitation-templates.clean.couple')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.countdown')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.story')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.schedule')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.gallery')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.dress-code')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.rsvp')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.gift')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.apology')
        @include('invitation-templates.clean.divider')
        @include('invitation-templates.clean.thanks')
    </main>

    @include('invitation-templates.clean.nav')


    @if ($musicSrc)
        @if ($ytId)
            <div id="bgm" data-yt="{{ $ytId }}" aria-hidden="true"
                style="position:fixed;left:-9999px;top:0;width:2px;height:2px;overflow:hidden;opacity:0;pointer-events:none"></div>
        @else
            <audio id="bgm" src="{{ $musicSrc }}" loop preload="auto"></audio>
        @endif
        <button class="mu" id="mu" type="button" aria-label="Music"
            aria-pressed="false">{!! $ic('music', 20) !!}</button>
    @endif

    @include('invitation-templates.clean.scripts')
    @include('invitation-templates.clean.motion')

</body>

</html>
