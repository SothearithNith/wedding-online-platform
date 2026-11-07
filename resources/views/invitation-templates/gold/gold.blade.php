@php
    $d = $data ?? [];
    $bgAll = $d['backgrounds']['all'] ?? null;

    // Section background: its own image, else the shared one, else just the color
    $bg = function (string $k) use ($d, $bgAll) {
        $u = $d['backgrounds'][$k] ?? $bgAll;
        return $u
            ? "background-image:linear-gradient(rgba(251,246,234,.82),rgba(251,246,234,.82)),url('" . e($u) . "')"
            : '';
    };

    $safeLink = fn($u) => is_string($u) && preg_match('#^https?://#i', $u) ? $u : null;
    $groom = $d['groom'] ?? [];
    $bride = $d['bride'] ?? [];
@endphp
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $groom['name'] ?? '' }} &amp; {{ $bride['name'] ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Khmer:wght@400;600;700&family=Moul&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --wine: #7a1f2b;
            --gold: #e6b84a;
            --gold-d: #b8902c;
            --cream: #fbf6ea;
            --ink: #3b2a1a;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #e9e2d0;
            font-family: 'Noto Serif Khmer', serif;
            color: var(--ink);
            line-height: 1.8;
        }

        .wrap {
            max-width: 430px;
            margin: 0 auto;
            background: var(--cream);
            box-shadow: 0 0 40px rgba(0, 0, 0, .15);
        }

        section {
            padding: 56px 24px;
            text-align: center;
            background-size: cover;
            background-position: center;
            border-bottom: 1px solid rgba(184, 144, 44, .25);
        }

        h2 {
            font-family: 'Moul', serif;
            font-weight: 400;
            color: var(--wine);
            font-size: 20px;
            margin-bottom: 18px;
        }

        .gold {
            color: var(--gold-d);
        }

        .rule {
            width: 70px;
            height: 2px;
            background: var(--gold);
            margin: 16px auto;
        }

        .cover {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
            background: #3b2a1a center/cover;
            position: relative;
        }

        .cover::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(60, 15, 20, .35), rgba(60, 15, 20, .7));
        }

        .cover>* {
            position: relative;
        }

        .cover .names {
            font-family: 'Moul', serif;
            font-size: 30px;
            line-height: 1.6;
        }

        .cover .amp {
            color: var(--gold);
            display: block;
            font-size: 22px;
        }

        .couple {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .person img,
        .person .ph {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            border: 3px solid var(--gold);
            background: #eadfc0;
            display: block;
        }

        .person b {
            display: block;
            color: var(--wine);
        }

        .small {
            font-size: 13px;
            opacity: .8;
        }

        .count {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 12px;
        }

        .count div {
            width: 68px;
            padding: 10px 0;
            border: 1px solid var(--gold);
            border-radius: 10px;
            background: rgba(255, 255, 255, .6);
        }

        .count b {
            display: block;
            font-size: 22px;
            color: var(--wine);
        }

        .tl {
            text-align: left;
            border-left: 2px solid var(--gold);
            margin-left: 12px;
            padding-left: 20px;
        }

        .tl .it {
            position: relative;
            margin-bottom: 22px;
        }

        .tl .it::before {
            content: '';
            position: absolute;
            left: -27px;
            top: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--gold);
        }

        .tl .yr {
            color: var(--gold-d);
            font-weight: 700;
        }

        .ev {
            background: rgba(255, 255, 255, .7);
            border: 1px solid rgba(184, 144, 44, .4);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 14px;
        }

        .ev .t {
            color: var(--wine);
            font-weight: 700;
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            margin-top: 8px;
            padding: 8px 18px;
            border-radius: 999px;
            background: var(--wine);
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            border: 0;
            font-family: inherit;
            cursor: pointer;
        }

        .btn[disabled] {
            opacity: .5;
            cursor: not-allowed;
        }

        .gal {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .gal div {
            aspect-ratio: 1;
            border-radius: 10px;
            background: #eadfc0 center/cover;
        }

        .sw {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 14px;
        }

        .sw i {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .25);
        }

        .gift {
            background: #fff;
            border: 1px dashed var(--gold-d);
            border-radius: 14px;
            padding: 18px;
        }

        .gift img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            margin: 10px auto 0;
            display: block;
            background: #f3ecd8;
        }

        form input,
        form textarea,
        form select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 10px;
            border: 1px solid #d9c98f;
            border-radius: 10px;
            font-family: inherit;
            background: #fff;
        }

        .music {
            position: fixed;
            right: 14px;
            bottom: 14px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 0;
            background: var(--wine);
            color: var(--gold);
            font-size: 18px;
            cursor: pointer;
            z-index: 20;
        }
    </style>
</head>

<body>
    <div class="wrap">

        {{-- Cover --}}
        <section class="cover"
            @if (!empty($d['cover'])) style="background-image:url('{{ e($d['cover']) }}')" @endif>
            <p class="small">{{ $d['intro'] ?? '' }}</p>
            <div class="rule"></div>
            <h1 class="names">{{ $groom['name'] ?? '' }}<span class="amp">&amp;</span>{{ $bride['name'] ?? '' }}
            </h1>
            <div class="rule"></div>
            <p>{{ $d['dateText'] ?? '' }}</p>
        </section>

        {{-- Quote + couple --}}
        <section style="{{ $bg('intro') }}">
            @if (!empty($d['quote']))
                <p class="gold" style="font-size:18px;margin-bottom:24px">“{{ $d['quote'] }}”</p>
            @endif
            <div class="couple">
                @foreach ([$groom, $bride] as $p)
                    <div class="person">
                        @if (!empty($p['photo']))
                            <img src="{{ $p['photo'] }}" alt="{{ $p['name'] ?? '' }}"
                            onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'ph'}))">@else
                            <div class="ph"></div>
                        @endif
                        <b>{{ $p['name'] ?? '' }}</b>
                        <span class="small">{{ $p['parents'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Countdown --}}
        @if (!empty($d['date']))
            <section style="{{ $bg('count') }}">
                <h2>រាប់ថយក្រោយ</h2>
                <div class="count" id="cd" data-date="{{ $d['date'] }}">
                    <div><b data-u="d">0</b><span class="small">ថ្ងៃ</span></div>
                    <div><b data-u="h">0</b><span class="small">ម៉ោង</span></div>
                    <div><b data-u="m">0</b><span class="small">នាទី</span></div>
                    <div><b data-u="s">0</b><span class="small">វិនាទី</span></div>
                </div>
            </section>
        @endif

        {{-- Story --}}
        @if (!empty($d['story']))
            <section style="{{ $bg('story') }}">
                <h2>រឿងស្នេហារបស់យើង</h2>
                <div class="tl">
                    @foreach ($d['story'] as $s)
                        <div class="it">
                            <div class="yr">{{ $s['year'] ?? '' }}</div>
                            <b>{{ $s['title'] ?? '' }}</b>
                            <p class="small">{{ $s['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Schedule --}}
        @if (!empty($d['events']))
            <section style="{{ $bg('schedule') }}">
                <h2>កម្មវិធី</h2>
                @foreach ($d['events'] as $e)
                    <div class="ev">
                        <div class="gold">{{ $e['time'] ?? '' }}</div>
                        <div class="t">{{ $e['title'] ?? '' }}</div>
                        <div class="small">{{ $e['place'] ?? '' }}</div>
                        @if ($link = $safeLink($e['mapUrl'] ?? null))
                            <a class="btn" href="{{ $link }}" target="_blank" rel="noopener">ផែនទី</a>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        {{-- Gallery --}}
        @if (!empty($d['gallery']))
            <section style="{{ $bg('gallery') }}">
                <h2>កម្រងរូបភាព</h2>
                <div class="gal">
                    @foreach ($d['gallery'] as $g)
                        <div style="background-image:url('{{ e($g) }}')"></div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Dress code --}}
        @if (!empty($d['dressCode']))
            <section>
                <h2>{{ $d['dressCode']['title'] ?? 'ឈុតសម្លៀកបំពាក់' }}</h2>
                <p class="small">{{ $d['dressCode']['note'] ?? '' }}</p>
                <div class="sw">
                    @foreach ($d['dressCode']['colors'] ?? [] as $c)
                        @if (preg_match('/^#[0-9a-fA-F]{3,8}$/', $c))
                            <i style="background:{{ $c }}"></i>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Gift --}}
        @if (!empty($d['gift']))
            <section>
                <h2>អំណោយ</h2>
                <div class="gift">
                    <b>{{ $d['gift']['bank'] ?? '' }}</b>
                    <p>{{ $d['gift']['accountName'] ?? '' }}</p>
                    <p class="gold" style="font-size:20px;font-weight:700">{{ $d['gift']['accountNumber'] ?? '' }}
                    </p>
                    @if (!empty($d['gift']['qr']))
                        <img src="{{ $d['gift']['qr'] }}" alt="QR" onerror="this.remove()">
                    @endif
                </div>
            </section>
        @endif

        {{-- RSVP --}}
        <section style="{{ $bg('rsvp') }}">
            <h2>ការឆ្លើយតប</h2>
            @if (!empty($d['apology']))
                <p class="small" style="margin-bottom:16px">{{ $d['apology'] }}</p>
            @endif
            <form method="POST" action="{{ $rsvpUrl ?? '#' }}"
                @if (empty($rsvpUrl)) onsubmit="return false" @endif>
                @csrf
                <input name="guest_name" placeholder="ឈ្មោះរបស់អ្នក" required>
                <select name="attending">
                    <option value="1">ខ្ញុំនឹងចូលរួម</option>
                    <option value="0">ខ្ញុំមិនអាចចូលរួមបាន</option>
                </select>
                <input name="guests_count" type="number" min="1" value="1">
                <textarea name="message" rows="3" placeholder="សារជូនពរ"></textarea>
                <button class="btn" @if (empty($rsvpUrl)) disabled @endif>ផ្ញើ</button>
            </form>
        </section>

        {{-- Thanks --}}
        <section style="{{ $bg('thanks') }}">
            <h2>អរគុណ</h2>
            <p>{{ $d['thanks'] ?? '' }}</p>
            <div class="rule"></div>
            <p class="gold">{{ $groom['name'] ?? '' }} &amp; {{ $bride['name'] ?? '' }}</p>
        </section>
    </div>

    @if (!empty($d['music']))
        <audio id="bgm" src="{{ $d['music'] }}" loop preload="none"></audio>
        <button class="music" id="mbtn" aria-label="Music">♪</button>
    @endif

    <script>
        // Countdown
        (function() {
            var el = document.getElementById('cd');
            if (!el) return;
            var t = new Date(el.dataset.date).getTime();

            function tick() {
                var s = Math.max(0, Math.floor((t - Date.now()) / 1000));
                var v = {
                    d: Math.floor(s / 86400),
                    h: Math.floor(s % 86400 / 3600),
                    m: Math.floor(s % 3600 / 60),
                    s: s % 60
                };
                for (var k in v) el.querySelector('[data-u=' + k + ']').textContent = v[k];
            }
            tick();
            setInterval(tick, 1000);
        })();
        // Music (browsers need a click to start audio)
        (function() {
            var a = document.getElementById('bgm'),
                b = document.getElementById('mbtn');
            if (!a) return;
            b.onclick = function() {
                a.paused ? a.play().catch(function() {}) : a.pause();
                b.style.opacity = a.paused ? .6 : 1;
            };
        })();
    </script>
</body>

</html>
