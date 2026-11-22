@php
    $navItems = [
        ['hero', 'home', 'ទំព័រដើម', 'home'],
        ['story', 'heart', $K['story'] ?? 'Our Story', 'story'],
        ['schedule', 'clock', $K['schedule'] ?? 'Schedule', 'schedule'],
        ['gallery', 'image', $K['gallery'] ?? 'Gallery', 'gallery'],
        ['rsvp', 'mail', $K['rsvp'] ?? 'RSVP', 'rsvp'],
        ['gift', 'gift', $K['gift'] ?? 'Gift', 'gift'],
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

<nav class="kx-nav" id="kx-nav" aria-label="Sections">
    @foreach ($navItems as [$target, $icon, $label, $i18n])
        <a class="kx-nav-i title-font" href="#{{ $target }}" data-t="{{ $target }}">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $navIcons[$icon] !!}</svg>
            <span data-i18n="{{ $i18n }}">{{ $label }}</span>
        </a>
    @endforeach
</nav>

<style>
    .kx-nav {
        position: fixed;
        left: 50%;
        bottom: calc(10px + env(safe-area-inset-bottom, 0px));
        z-index: 60;
        display: flex;
        gap: 2px;
        width: min(calc(100% - 20px), 460px);
        padding: 6px;
        border-radius: 22px;
        background: rgba(42, 10, 16, .82);
        -webkit-backdrop-filter: blur(14px) saturate(1.2);
        backdrop-filter: blur(14px) saturate(1.2);
        border: 1px solid rgba(217, 180, 74, .35);
        box-shadow: 0 10px 30px rgba(0, 0, 0, .35);
        transform: translate(-50%, 140%);
        opacity: 0;
        pointer-events: none;
        transition: transform .6s cubic-bezier(.2, .8, .2, 1), opacity .6s;
    }

    .kx-nav.show {
        transform: translate(-50%, 0);
        opacity: 1;
        pointer-events: auto;
    }

    /* hide while the gallery lightbox is open */
    body.lb-open .kx-nav {
        transform: translate(-50%, 140%);
        opacity: 0;
        pointer-events: none;
    }

    .kx-nav-i {
        flex: 1 1 0;
        min-width: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        padding: 7px 2px 6px;
        border-radius: 16px;
        color: rgba(255, 255, 255, .62);
        text-decoration: none;
        font-size: .64rem;
        line-height: 1.2;
        -webkit-tap-highlight-color: transparent;
        transition: color .3s, background .3s;
    }

    .kx-nav-i span {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .kx-nav-i.on {
        color: #e3bd5a;
        background: rgba(217, 180, 74, .14);
    }

    .kx-nav-i.on svg {
        transform: translateY(-1px) scale(1.08);
    }

    .kx-nav-i svg {
        transition: transform .3s;
    }

    /* keep the last section clear of the bar, and lift the music button above it */
    body {
        padding-bottom: calc(76px + env(safe-area-inset-bottom, 0px));
    }

    .mu {
        bottom: calc(86px + env(safe-area-inset-bottom, 0px)) !important;
    }

    @media (prefers-reduced-motion: reduce) {

        .kx-nav,
        .kx-nav-i,
        .kx-nav-i svg {
            transition: none;
        }
    }
</style>

<script>
    (function() {
        var nav = document.getElementById('kx-nav');
        if (!nav) return;
        document.body.appendChild(nav); // directly under <body>: no parent overflow/transform can clip it
        var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* show once the cover is gone (or at once when there is no cover) */
        var shown = false;

        function showNav() {
            if (shown) return;
            shown = true;
            nav.classList.add('show');
        }
        var tries = 0,
            t = setInterval(function() {
                var cv = document.getElementById('cv');
                if (!cv) {
                    clearInterval(t);
                    showNav();
                    return;
                }
                if (cv.classList.contains('open')) {
                    clearInterval(t);
                    setTimeout(showNav, 1400);
                    return;
                }
                if (++tries > 600) { // safety net: never leave the guest without navigation
                    clearInterval(t);
                    showNav();
                }
            }, 200);

        function init() {
            var items = [].slice.call(nav.querySelectorAll('.kx-nav-i'));

            /* drop entries whose section isn't on this invitation */
            items = items.filter(function(a) {
                if (document.getElementById(a.getAttribute('data-t'))) return true;
                a.parentNode.removeChild(a);
                return false;
            });
            if (!items.length) {
                nav.parentNode.removeChild(nav);
                return;
            }

            function setActive(id) {
                items.forEach(function(a) {
                    var on = a.getAttribute('data-t') === id;
                    a.classList.toggle('on', on);
                    if (on) a.setAttribute('aria-current', 'true');
                    else a.removeAttribute('aria-current');
                });
            }
            setActive('hero');

            items.forEach(function(a) {
                a.addEventListener('click', function(e) {
                    var el = document.getElementById(a.getAttribute('data-t'));
                    if (!el) return;
                    e.preventDefault();
                    el.scrollIntoView({
                        behavior: reduce ? 'auto' : 'smooth',
                        block: 'start'
                    });
                    setActive(a.getAttribute('data-t'));
                });
            });

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
