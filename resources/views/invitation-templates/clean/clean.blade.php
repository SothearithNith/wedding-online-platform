@php
    use App\Support\CleanInvitationPresenter;
    $templateKey = $templateKey ?? 'clean';
    $viewData = CleanInvitationPresenter::prepare(
        is_array($data ?? null) ? $data : [],
        $templateKey,
        $eventType ?? 'wedding',
    );
    extract($viewData, EXTR_SKIP);

    $defaultMusic = is_file(public_path('assets/music/wedding.mp3')) ? asset('assets/music/wedding.mp3') : '';
    $musicSrc = is_string($music ?? null) && $music !== '' ? $music : $defaultMusic;

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
    <meta name="theme-color" content="#1b1209">
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
    <div class="lang" id="lang" data-l="km" role="group" aria-label="Language / ភាសា">
        <button type="button" class="on" data-l="km" lang="km" aria-pressed="true">ខ្មែរ</button>
        <button type="button" data-l="en" lang="en" aria-pressed="false">EN</button>
    </div>
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


    @if ($musicSrc)
        <audio id="bgm" src="{{ $musicSrc }}" loop preload="auto"></audio>
        <button class="mu" id="mu" type="button" aria-label="Music" aria-pressed="false">{!! $ic('music', 20) !!}</button>
    @endif

    @include('invitation-templates.clean.scripts')
    @include('invitation-templates.clean.motion')

</body>

</html>
