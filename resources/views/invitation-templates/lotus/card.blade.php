@php
    $heroGroomParents = implode(' ', $parents($groom['parents'] ?? []));
    $heroBrideParents = implode(' ', $parents($bride['parents'] ?? []));
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

<section class="g-hero" id="hero">
    <div class="g-bgimg on" data-bg="{{ $img($w['cover']) ?: $heroBg }}" data-eager style="z-index:0"></div>
    <div class="g-ov"
        style="background:linear-gradient(180deg,rgba(23,8,34,.72),rgba(23,8,34,.38) 45%,rgba(23,8,34,.86));z-index:0">
    </div>

    <div class="kc-fx" aria-hidden="true">
        <div class="kc-rays"></div>
        <div class="kc-sun"></div>
        <div class="kc-dust">
            @for ($i = 0; $i < 14; $i++)
                <i
                    style="left:{{ ($i * 29 + 5) % 96 }}%;width:{{ 3 + ($i % 3) }}px;height:{{ 3 + ($i % 3) }}px;animation-duration:{{ 9 + ($i % 6) * 2 }}s;animation-delay:{{ ($i % 8) * 1.1 }}s;--dx:{{ (($i % 5) - 2) * 22 }}px"></i>
            @endfor
        </div>
    </div>

    <div class="kc-frame">
        <div class="kc-shape" aria-hidden="true"></div>
        <svg class="kc-angkor" viewBox="0 0 160 70" fill="none" stroke="#e3bd5a" stroke-width="1.4"
            stroke-linejoin="round" aria-hidden="true">
            <path d="M80 2c3 6 3 10 0 14c-3-4-3-8 0-14z" fill="#e3bd5a" />
            <path d="M68 40c0-10 5-16 12-24c7 8 12 14 12 24" />
            <path d="M72 40c0-8 3-12 8-18c5 6 8 10 8 18" opacity=".6" />
            <path d="M40 46c0-8 4-13 10-19c6 6 10 11 10 19M100 46c0-8 4-13 10-19c6 6 10 11 10 19" />
            <path d="M14 56c0-6 3-10 8-15c5 5 8 9 8 15M130 56c0-6 3-10 8-15c5 5 8 9 8 15" />
            <path d="M4 68h152M10 62h140M30 56h100M56 46h48M62 40h36" />
        </svg>
        <div class="kc-angkor-base royal-enter" style="--d:700ms"></div>
        <p class="hl-label royal-enter" style="--d:900ms;margin-top:22px">{{ $K['coverInvite'] }}</p>
        <h1 class="kc-moul kc-name royal-enter title-font"
            style="--d:1150ms;margin-top:10px;font-size:1.5rem;line-height:1.9">
            {{ $groom['name'] }}</h1>

        <div class="kc-amp royal-enter" style="--d:1400ms" aria-hidden="true">
            <span></span><b class="kc-knot">❀</b><span></span>
            <em>{{ $K['and'] }}</em>
        </div>

        <h1 class="kc-moul kc-name royal-enter title-font" style="--d:1650ms;font-size:1.5rem;line-height:1.9">
            {{ $bride['name'] }}</h1>
        <div class="kc-fam royal-enter" style="--d:1900ms">
            @foreach ([[$groom['dad'], $groom['mom']], [$bride['dad'], $bride['mom']]] as [$dad, $mom])
                <div class="kc-fam-col">
                    <div class="kc-fam-person">
                        <span class="kc-fam-role">{{ trans_json('Mr.') }}</span>
                        <span class="kc-fam-name title-font">{{ $dad }}</span>
                    </div>
                    <i class="kc-fam-dia" aria-hidden="true"></i>
                    <div class="kc-fam-person">
                        <span class="kc-fam-role">{{ trans_json('Ms.') }}</span>
                        <span class="kc-fam-name title-font">{{ $mom }}</span>
                    </div>
                </div>

                @if ($loop->first)
                    <div class="kc-fam-vline" aria-hidden="true"></div>
                @endif
            @endforeach
        </div>
        <div class="kc-when royal-enter" style="--d:2150ms">
            <span class="kc-date font-khmer js-date title-font"
                data-iso="{{ $w['date'] }}">{{ $w['dateText'] }}</span>

            <div class="kc-when-div" aria-hidden="true">
                <span></span><i></i><span></span>
            </div>

            <p class="kc-loc">
                <svg class="kc-loc-pin" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 12 2.5a7 7 0 0 1 7 7C19 14.800 12 21 12 21Z" />
                    <circle cx="12" cy="9.5" r="2.4" />
                </svg>
                <span class="kc-loc-text title-font">{{ $w['location'] }}</span>
            </p>
        </div>
        <div class="chakra-band royal-enter" style="--d:2400ms;width:9rem;margin-top:22px" aria-hidden="true"></div>
    </div>

    <div class="kc-scroll" aria-hidden="true">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 9l6 6 6-6" />
        </svg>
    </div>
</section>
 
<nav class="kx-nav" id="kx-nav" aria-label="Sections">
    @foreach ($navItems as [$target, $icon, $label])
        <a class="kx-nav-i" href="#{{ $target }}" data-t="{{ $target }}">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">{!! $navIcons[$icon] !!}</svg>
            <span>{{ $label }}</span>
        </a>
    @endforeach
</nav>

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
