{{-- ============================ KHMER MOTION (JS) ============================
     Pure progressive enhancement: the invitation works without it.
     - parallax on every database background
     - falling champa petals + golden fireflies
     - flowing kbach strips between sections
     - live background swap (editor preview): window.postMessage({type:'kx-bg', section:'story', url:'...'})
============================================================================ --}}
<script>
    (function() {
        'use strict';
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var gw = document.getElementById('gw');
        var GOLD = '#e3bd5a';

        /* ---------- 1. parallax backgrounds ---------- */
        var layers = [].slice.call(document.querySelectorAll('.g-bgimg'));
        layers.forEach(function(el) {
            el.setAttribute('data-parallax', '');
        });

        function parallax() {
            var vh = window.innerHeight;
            layers.forEach(function(el) {
                var host = el.parentNode;
                if (!host) return;
                var r = host.getBoundingClientRect();
                if (r.bottom < -100 || r.top > vh + 100) return;
                var p = (r.top + r.height / 2 - vh / 2) / vh; // -1 .. 1
                el.style.setProperty('--py', (p * -40).toFixed(1) + 'px');
            });
        }
        if (!reduce) {
            var ticking = false;
            window.addEventListener('scroll', function() {
                if (ticking) return;
                ticking = true;
                requestAnimationFrame(function() {
                    parallax();
                    ticking = false;
                });
            }, {
                passive: true
            });
            window.addEventListener('resize', parallax);
            parallax();
        }

        /* ---------- 2. falling petals (starts when cover opens) ---------- */
        function startPetals() {
            if (reduce || document.querySelector('.kx-petals')) return;
            var wrap = document.createElement('div');
            wrap.className = 'kx-petals';
            wrap.setAttribute('aria-hidden', 'true');
            var n = window.innerWidth < 600 ? 14 : 24;
            for (var i = 0; i < n; i++) {
                var p = document.createElement('i');
                if (i % 5 === 0) p.className = 'pink';
                p.style.left = ((i * 37 + 7) % 100) + '%';
                p.style.setProperty('--w', (9 + (i % 4) * 3) + 'px');
                p.style.setProperty('--dur', (13 + (i % 7) * 2.2) + 's');
                p.style.setProperty('--delay', '-' + ((i * 1.7) % 16).toFixed(1) + 's');
                p.style.setProperty('--dx', ((i % 2 ? 1 : -1) * (30 + (i % 6) * 18)) + 'px');
                p.style.setProperty('--rot', ((i % 2 ? 1 : -1) * (200 + i * 23)) + 'deg');
                wrap.appendChild(p);
            }
            document.body.appendChild(wrap);
        }

        /* ---------- 3. fireflies inside photo sections ---------- */
        function addFireflies() {
            if (reduce) return;
            [].slice.call(document.querySelectorAll('.g-photo, .g-hero')).forEach(function(sec, s) {
                var box = document.createElement('div');
                box.className = 'kx-fireflies';
                box.setAttribute('aria-hidden', 'true');
                for (var i = 0; i < 9; i++) {
                    var b = document.createElement('b');
                    b.style.left = ((i * 41 + s * 13) % 94 + 3) + '%';
                    b.style.top = ((i * 29 + s * 17) % 80 + 8) + '%';
                    b.style.setProperty('--dur', (6 + (i % 5) * 1.6) + 's');
                    b.style.setProperty('--delay', ((i * 0.9) % 7).toFixed(1) + 's');
                    b.style.setProperty('--fx', ((i % 2 ? 1 : -1) * (14 + i * 3)) + 'px');
                    b.style.setProperty('--fy', (-20 - i * 4) + 'px');
                    box.appendChild(b);
                }
                sec.appendChild(box);
            });
        }

        /* ---------- 4. flowing kbach strips between sections ---------- */
        function kbachSvg(id) {
            return '<svg aria-hidden="true" viewBox="0 0 1200 34" preserveAspectRatio="none">' +
                '<defs><pattern id="' + id + '" width="40" height="34" patternUnits="userSpaceOnUse">' +
                '<path d="M20 31 C8 29 5 17 12 9 C12 15 16 17 20 15 C19 9 23 4 28 3 C27 10 32 14 31 21 C30 27 26 30 20 31 Z" fill="none" stroke="' +
                GOLD + '" stroke-width="1.2"/>' +
                '<path d="M20 25 C15 23 15 17 18 13 C18 17 21 18 23 17 C25 20 23 24 20 25 Z" fill="' + GOLD +
                '" opacity=".55"/>' +
                '</pattern></defs><rect width="1200" height="34" fill="url(#' + id + ')"/></svg>';
        }

        function addStrips() {
            if (!gw) return;
            var ids = ['countdown', 'schedule', 'rsvp', 'apology'];
            ids.forEach(function(id, i) {
                var sec = document.getElementById(id);
                if (!sec) return;
                var strip = document.createElement('div');

                strip.innerHTML = kbachSvg('kx-kb-' + i);
                sec.parentNode.insertBefore(strip, sec);
            });
        }

        /* ---------- 5. line-art "draws itself" ---------- */
        function watchDraw() {
            var els = [].slice.call(document.querySelectorAll('.kx-draw'));
            if (!('IntersectionObserver' in window)) {
                els.forEach(function(e) {
                    e.classList.add('in');
                });
                return;
            }
            var io = new IntersectionObserver(function(es) {
                es.forEach(function(e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('in');
                        io.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.3
            });
            els.forEach(function(e) {
                io.observe(e);
            });
        }

        /* ---------- 6. mark hero bg as loaded (Ken Burns) ---------- */
        var hero = document.getElementById('hero');
        if (hero) {
            setTimeout(function() {
                hero.classList.add('kx-bg-on');
            }, 400);
        }

        /* ---------- 7. live background swap (editor / admin preview) ---------- */
        window.addEventListener('message', function(ev) {
            var d = ev.data;
            if (!d || d.type !== 'kx-bg' || typeof d.url !== 'string') return;
            if (!/^(https?:)?\/\/|^\/|^data:image\//i.test(d.url)) return;
            var sel = d.section === 'all' ? '.g-bgimg' : '#' + String(d.section).replace(/[^\w-]/g, '') +
                ' .g-bgimg';
            [].slice.call(document.querySelectorAll(sel)).forEach(function(el) {
                el.style.backgroundImage = 'url("' + d.url.replace(/"/g, '%22') + '")';
                el.classList.remove('kx-swap');
                void el.offsetWidth;
                el.classList.add('on', 'kx-swap');
            });
        });

        /* ---------- boot ---------- */
        addStrips();
        addFireflies();
        watchDraw();

        var gc = document.getElementById('gc');
        var openBtn = document.getElementById('gc-open');

        function opened() {
            if (gw) gw.classList.add('kx-open');
            startPetals();
        }
        if (!gc) {
            opened();
        } else {
            if (openBtn) openBtn.addEventListener('click', function() {
                setTimeout(opened, 900);
            });
            // safety: cover already dismissed (e.g. no-JS fallback / reload)
            if (gc.classList.contains('opened')) opened();
        }
    })();
</script>
