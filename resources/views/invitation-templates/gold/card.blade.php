@php
    $navItems = [
        ['hero', 'home', 'ទំព័រដើម'],
        ['story', 'heart', $K['story']],
        ['schedule', 'clock', $K['schedule']],
        ['gallery', 'image', $K['gallery']],
        ['rsvp', 'mail', $K['rsvp']],
        ['gift', 'gift', $K['gift']],
    ];
    $navIcons = [
        'home' => '<path d="M3 11l9-8 9 8"/><path d="M5 10v10h5v-6h4v6h5V10"/>',
        'heart' => '<path d="M12 21s-8-5.2-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.8-8 11-8 11z"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'image' =>
            '<rect x="3" y="4" width="18" height="16" rx="1"/><circle cx="9" cy="10" r="1.6"/><path d="M3 17l5-5 4 4 3-3 6 6"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3 7l9 7 9-7"/>',
        'gift' =>
            '<rect x="3" y="9" width="18" height="11" rx="1"/><path d="M12 9v11M3 13h18"/><path d="M12 9c-3 0-5-1-5-3s3-2 5 3c2-5 5-5 5-3s-2 3-5 3z"/>',
    ];
@endphp

<section id="hero" class="g-hero" data-bg="{{ $heroBg }}" data-eager>
    <div class="kc-fx" aria-hidden="true">
        <div class="royal-glow"></div>
        @for ($i = 0; $i < 12; $i++)
            <span class="royal-particle"
                style="left:{{ 8 + (($i * 17) % 85) }}%;top:{{ 8 + (($i * 23) % 85) }}%;width:{{ $i % 3 === 0 ? 4 : 2 }}px;height:{{ $i % 3 === 0 ? 4 : 2 }}px;animation-delay:{{ $i * 0.45 }}s"></span>
        @endfor
        <div class="kc-dust" style="position:absolute;inset:0">
            @for ($i = 0; $i < 14; $i++)
                <i
                    style="left:{{ ($i * 37 + 5) % 100 }}%;width:{{ 4 + ($i % 3) * 2 }}px;height:{{ 4 + ($i % 3) * 2 }}px;animation-duration:{{ 10 + ($i % 6) * 2 }}s;animation-delay:{{ ($i % 8) * 1.4 }}s;--dx:{{ (($i % 5) - 2) * 26 }}px"></i>
            @endfor
        </div>
    </div>

    <div class="kc-frame">
        <div class="kc-shape" aria-hidden="true"></div>
        <div class="kc-rays" aria-hidden="true"></div>
        <div class="kc-sun" aria-hidden="true"></div>
        <svg aria-hidden="true" viewBox="-100 -100 200 200" class="kc-mandala">
            <g fill="none" stroke="{{ $GOLD }}" stroke-width="0.8">
                @for ($i = 0; $i < 16; $i++)
                    <path d="M0 -14 C12 -40 12 -70 0 -92 C-12 -70 -12 -40 0 -14 Z"
                        transform="rotate({{ $i * 22.5 }})" />
                @endfor
                @for ($i = 0; $i < 8; $i++)
                    <path d="M0 -10 C7 -26 7 -44 0 -58 C-7 -44 -7 -26 0 -10 Z"
                        transform="rotate({{ $i * 45 + 22.5 }})" />
                @endfor
                <circle r="96" />
                <circle r="8" />
            </g>
        </svg>

        <div class="royal-enter" style="--d:50ms;position:relative;z-index:10;width:100%;margin:-8px 0 4px">
            {!! $kbach('kc-kb-top', true) !!}
        </div>

        <div class="royal-enter"
            style="--d:650ms;position:relative;z-index:10;margin-top:-20px;display:flex;width:100%;max-width:310px;flex-direction:column;align-items:center">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                <span class="hl-rule" style="width:40px"></span>
                <span class="royal-diamond" style="font-size:18px;color:#e3bd5a">✥</span>
                <span class="hl-rule" style="width:40px"></span>
            </div>
            <img src="{{ asset('assets/images/gold/gold-wedding.png') }}" alt="" width="500" height="180"
                class="royal-logo" style="display:block;width:100%;max-width:250px;height:auto;object-fit:contain"
                onerror="this.style.display='none'">
            <div class="royal-enter"
                style="--d:950ms;margin-top:12px;display:flex;flex-direction:column;align-items:center">
                <p class="kc-moul" style="font-size:9px;letter-spacing:.15em;color:#e3bd5a">{{ $K['celebrate'] }}</p>
                <p style="margin-top:4px;font-size:12px;line-height:1.6;color:rgba(251,238,193,.8)">{{ $K['invite'] }}
                </p>
            </div>
        </div>

        <div class="royal-enter"
            style="--d:1150ms;position:relative;z-index:10;margin-top:16px;display:grid;width:100%;grid-template-columns:1fr 1fr;gap:12px">
            @foreach ([[$groom['dad'], $groom['mom']], [$bride['dad'], $bride['mom']]] as [$dad, $mom])
                <div style="min-width:0;padding:0 4px">
                    <div class="hl-rule" style="width:32px;margin:4px auto 0"></div>

                    <div style="margin-top:8px;display:flex;flex-direction:column;align-items:center;gap:6px">
                        <p class="hl-parent">
                            {{ trans_json('Mr.') }}&nbsp;&nbsp;<span class="title-font">{{ $dad }}</span>
                        </p>
                        <p class="hl-parent">
                            {{ trans_json('Ms.') }}&nbsp;&nbsp;<span class="title-font">{{ $mom }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rv" data-v="unfold"
            style="position:relative;z-index:10;display:flex;align-items:center;justify-content:center;gap:12px;margin:12px 0">
            <span class="hl-rule" style="width:40px"></span>
            <span class="royal-shimmer" style="font-size:18px;color:#e3bd5a">❖</span>
            <span class="hl-rule" style="width:40px"></span>
        </div>

        <div style="position:relative;z-index:10;width:100%">
            <p class="rv" data-v="blur"
                style="max-width:290px;margin:0 auto;font-size:13px;line-height:1.7;color:#fbeec1">
                {{ $w['intro'] }}</p>
        </div>

        <div class="rv" data-v="zoom" data-delay="150" style="position:relative;z-index:10;margin-top:8px">
            <div class="royal-emblem-glow">
                <div style="position:relative;margin:0 auto;height:112px;width:96px;color:#fbeec1">
                    <svg viewBox="0 0 100 120" style="position:absolute;inset:0;width:100%;height:100%" fill="none"
                        stroke="currentColor" aria-hidden="true">
                        <path d="M50 4C60 24 90 36 90 70C90 100 70 116 50 116C30 116 10 100 10 70C10 36 40 24 50 4Z"
                            stroke-width="2.5" />
                        <path d="M50 14C58 30 80 40 80 70C80 94 66 106 50 106C34 106 20 94 20 70C20 40 42 30 50 14Z"
                            stroke-width="1" opacity=".7" />
                    </svg>
                    <svg viewBox="-60 -60 120 90"
                        style="position:absolute;left:0;right:0;bottom:20px;margin:0 auto;height:48px;width:64px"
                        fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        @foreach ([-62, -34, 0, 34, 62] as $rot)
                            <path d="M0 20 C-16 4 -14 -24 0 -46 C14 -24 16 4 0 20 Z"
                                transform="rotate({{ $rot }} 0 20)" />
                        @endforeach
                    </svg>
                </div>
            </div>
        </div>

        @if (!empty($w['quote']))
            <div style="position:relative;z-index:10;margin-top:8px">
                <p class="rv royal-quote" data-v="blur" data-delay="250"
                    style="max-width:280px;margin:0 auto;font-size:12px;line-height:1.7">“{{ $w['quote'] }}”</p>
            </div>
        @endif

        <div class="royal-enter"
            style="--d:1550ms;position:relative;z-index:10;margin-top:16px;display:grid;width:100%;grid-template-columns:1fr auto 1fr;align-items:center;gap:8px">
            <div style="min-width:0">
                <p class="hl-label">{{ $K['groom'] }}</p>
                <p class="kc-name kc-moul title-font" style="margin-top:6px">{{ $groom['name'] }}</p>
            </div>
            <svg aria-hidden="true" viewBox="-50 -50 100 100" width="44" height="44" class="kc-knot">
                @for ($i = 0; $i < 8; $i++)
                    <path d="M0 -6 C6 -18 6 -32 0 -44 C-6 -32 -6 -18 0 -6 Z" fill="none"
                        stroke="{{ $GOLD }}" stroke-width="1.8" transform="rotate({{ $i * 45 }})" />
                @endfor
                @for ($i = 0; $i < 4; $i++)
                    <path d="M0 -5 C4 -12 4 -20 0 -26 C-4 -20 -4 -12 0 -5 Z" fill="{{ $GOLD }}"
                        opacity=".7" transform="rotate({{ $i * 90 + 22.5 }})" />
                @endfor
                <circle r="4.5" fill="{{ $GOLD }}" />
            </svg>
            <div style="min-width:0">
                <p class="hl-label">{{ $K['bride'] }}</p>
                <p class="kc-name kc-moul title-font" style="margin-top:6px">{{ $bride['name'] }}</p>
            </div>
        </div>

        <div class="royal-enter" style="--d:1800ms;position:relative;z-index:10;margin-top:16px">
            <p class="kc-date js-date" data-iso="{{ $w['date'] }}">{{ $w['dateText'] }}</p>
            <div class="royal-ornament-glow" style="margin-top:8px">
                <div class="chakra-band" style="width:140px"></div>
            </div>
        </div>

        <div class="royal-enter" style="--d:2050ms;position:relative;z-index:10;margin-top:8px;width:100%">
            <p class="royal-bottom-ornament" style="font-size:14px;letter-spacing:.4em">❧ ✥ ❧</p>
            {!! $kbach('kc-kb-bottom') !!}
        </div>
    </div>

    <div class="kc-scroll" aria-hidden="true">
        <span>{{ $K['scroll'] }}</span>
        {!! $ic('down', 18) !!}
    </div>
</section>

<nav class="kx-nav" id="kx-nav" aria-label="Sections">
    @foreach ($navItems as [$target, $icon, $label])
        <a class="kx-nav-i title-font" href="#{{ $target }}" data-t="{{ $target }}">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">{!! $navIcons[$icon] !!}</svg>
            <span>{{ $label }}</span>
        </a>
    @endforeach
</nav>
<style>
    /* ===== Bottom navigation bar ===== */
    .kx-nav {
        position: fixed;
        left: 50%;
        bottom: calc(10px + env(safe-area-inset-bottom, 0px));
        transform: translate(-50%, 140%);
        opacity: 0;
        pointer-events: none;
        z-index: 60;
        display: flex;
        gap: 2px;
        width: min(94vw, 460px);
        padding: 6px;
        border-radius: 18px;
        background: rgba(23, 8, 34, .82);
        -webkit-backdrop-filter: blur(14px);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(227, 189, 90, .45);
        box-shadow: 0 8px 28px rgba(0, 0, 0, .45), inset 0 0 0 1px rgba(251, 238, 193, .06);
        transition: transform .6s cubic-bezier(.2, .8, .2, 1), opacity .6s;
    }

    .kx-nav.show {
        transform: translate(-50%, 0);
        opacity: 1;
        pointer-events: auto;
    }

    .kx-nav-i {
        flex: 1 1 0;
        min-width: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        padding: 7px 2px;
        border-radius: 12px;
        font-size: 10px;
        line-height: 1.3;
        color: rgba(251, 238, 193, .65);
        text-decoration: none;
        -webkit-tap-highlight-color: transparent;
        transition: color .25s, background .25s;
    }

    .kx-nav-i span {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .kx-nav-i.on {
        color: #e3bd5a;
        background: rgba(227, 189, 90, .14);
    }

    /* keep the last section clear of the bar */
    .gw {
        padding-bottom: calc(90px + env(safe-area-inset-bottom, 0px));
    }

    /* keep the music button above the bar */
    .g-music {
        bottom: calc(84px + env(safe-area-inset-bottom, 0px)) !important;
    }
</style>
<script>
    (function() {
        var nav = document.getElementById('kx-nav');
        if (!nav) return;
        // live directly under <body> so no parent (overflow / transform) can hide or clip the bar
        document.body.appendChild(nav);

        // show the bar as soon as the cover is gone (or immediately when there is no cover)
        function showNav() {
            nav.classList.add('show');
        }
        var gc = document.getElementById('gc');
        var gcBtn = document.getElementById('gc-open');
        if (!gc || gc.style.display === 'none' || gc.classList.contains('opened')) {
            showNav();
        } else {
            if (gcBtn) gcBtn.addEventListener('click', function() {
                setTimeout(showNav, 1400);
            });
            // safety net: never leave the visitor without navigation
            var tries = 0,
                t = setInterval(function() {
                    if (!document.getElementById('gc') || ++tries > 120) {
                        clearInterval(t);
                        showNav();
                    }
                }, 500);
        }

        // this script sits BEFORE the other sections in the page, so wait until they exist
        function init() {
            var items = [].slice.call(nav.querySelectorAll('.kx-nav-i'));

            // hide entries whose section is not on this invitation
            items = items.filter(function(a) {
                if (document.getElementById(a.getAttribute('data-t'))) return true;
                a.parentNode.removeChild(a);
                return false;
            });
            nav.style.setProperty('--n', items.length);

            function setActive(id) {
                items.forEach(function(a) {
                    a.classList.toggle('on', a.getAttribute('data-t') === id);
                });
            }
            setActive('hero');

            if ('IntersectionObserver' in window) {
                var io = new IntersectionObserver(function(es) {
                    es.forEach(function(e) {
                        if (e.isIntersecting) setActive(e.target.id);
                    });
                }, {
                    rootMargin: '-45% 0px -50% 0px'
                });
                items.forEach(function(a) {
                    io.observe(document.getElementById(a.getAttribute('data-t')));
                });
            }
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
        else init();
    })();
</script>
