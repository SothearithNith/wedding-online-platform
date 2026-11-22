<script>
    (function() {
        'use strict';
        var $ = function(s, r) {
            return (r || document).querySelector(s);
        };
        var $$ = function(s, r) {
            return [].slice.call((r || document).querySelectorAll(s));
        };
        var root = document.documentElement,
            body = document.body;
        var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
        var coarse = window.matchMedia && matchMedia('(pointer:coarse)').matches;
        var store = {
            get: function(k) {
                try {
                    return localStorage.getItem(k);
                } catch (e) {
                    return null;
                }
            },
            set: function(k, v) {
                try {
                    localStorage.setItem(k, v);
                } catch (e) {}
            }
        };

        /* ------------------------------------------------------------------
           LANGUAGE  ·  Khmer = whatever the server rendered ($K / data), kept as-is
                        English = the dictionary below + optional *_en fields (data-en)
           ------------------------------------------------------------------ */
        var T = {
            copied: @json($K['copied']),
            copy: @json($K['copy']),
            linkCopied: @json($K['linkCopied']),
            share: @json($K['share'])
        };
        var EN = {
            coverInvite: 'You are warmly invited to',
            and: '&',
            coverSub: 'the wedding celebration of',
            coverGuest: 'Dear Guest',
            coverOpen: 'Open Invitation',
            swipe: 'Swipe up to open',
            celebrate: 'We are getting married',
            scroll: 'Scroll',
            wedding: 'The Wedding',
            groom: 'The Groom',
            bride: 'The Bride',
            mr: 'Mr.',
            ms: 'Mrs.',
            countdowns: 'Counting Down',
            cd0: 'Days',
            cd1: 'Hours',
            cd2: 'Minutes',
            cd3: 'Seconds',
            story: 'Our Story',
            schedule: 'Schedule',
            map: 'View map',
            gallery: 'Gallery',
            close: 'Close',
            prev: 'Previous',
            next: 'Next',
            dressCode: 'Dress Code',
            rsvp: 'RSVP',
            rsvpSub: 'Kindly let us know if you can join us',
            yourName: 'Your name',
            required: 'Please enter your name',
            attend: 'Joyfully attending',
            decline: 'Regretfully declining',
            guests: 'Guests',
            wish: 'Leave a wish for the couple',
            send: 'Send',
            sent: 'Thank you! Your reply has been sent.',
            failed: 'Something went wrong. Please try again.',
            gift: 'Wedding Gift',
            giftSub: 'Your presence is our greatest gift. Should you wish to bless us further, you may do so here.',
            scan: 'Scan to send',
            copy: 'Copy',
            copied: 'Copied',
            share: 'Share invitation',
            linkCopied: 'Link copied',
            apology: 'Our Apologies',
            thanks: 'Thank You',
            home: 'Home'
        };
        var KM_D = '០១២៣៤៥៦៧៨៩';
        var lang = 'km';
        var tr = function(k) {
            return lang === 'en' && EN[k] != null ? EN[k] : T[k];
        };
        var num = function(x) {
            var s = String(x);
            return lang === 'km' ? s.replace(/\d/g, function(d) {
                return KM_D.charAt(d);
            }) : s;
        };

        function pd(s) {
            /* iOS-safe date parsing */
            if (!s) return NaN;
            s = String(s).trim();
            if (/^\d{4}-\d{2}-\d{2}$/.test(s)) s += 'T00:00:00';
            else s = s.replace(/^(\d{4}-\d{2}-\d{2})\s+/, '$1T');
            return new Date(s).getTime();
        }
        var cdEl = $('#cd'),
            evMs = cdEl ? pd(cdEl.dataset.date) : NaN;

        function enDate() {
            if (isNaN(evMs)) return '';
            try {
                return new Intl.DateTimeFormat('en-GB', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }).format(new Date(evMs));
            } catch (e) {
                return '';
            }
        }

        /* remember the server-rendered (Khmer) text once */
        $$('[data-i18n],[data-en],[data-i18n-date]').forEach(function(el) {
            el.dataset.km = el.textContent;
        });
        $$('[data-i18n-ph]').forEach(function(el) {
            el.dataset.kmPh = el.getAttribute('placeholder') || '';
        });
        $$('[data-i18n-aria]').forEach(function(el) {
            el.dataset.kmAria = el.getAttribute('aria-label') || '';
        });

        var cdPaint = function() {};

        function apply(l) {
            lang = l;
            var en = l === 'en';
            root.lang = en ? 'en' : 'km';
            $$('[data-i18n]').forEach(function(el) {
                var k = el.dataset.i18n,
                    v = el.dataset.km;
                if (en && EN[k] != null) v = EN[k] + (el.dataset.n ? ': ' + el.dataset.n : '');
                el.textContent = v;
            });
            $$('[data-en]').forEach(function(el) {
                el.textContent = en && el.dataset.en ? el.dataset.en : el.dataset.km;
            });
            var d = enDate();
            $$('[data-i18n-date]').forEach(function(el) {
                el.textContent = en && d ? d : el.dataset.km;
            });
            $$('[data-i18n-ph]').forEach(function(el) {
                var k = el.dataset.i18nPh;
                el.setAttribute('placeholder', en && EN[k] != null ? EN[k] : el.dataset.kmPh);
            });
            $$('[data-i18n-aria]').forEach(function(el) {
                var k = el.dataset.i18nAria,
                    sfx = (el.dataset.kmAria.match(/\s\d+$/) || [''])[0];
                el.setAttribute('aria-label', en && EN[k] != null ? EN[k] + sfx : el.dataset.kmAria);
            });
            var sw = $('#lang');
            if (sw) {
                sw.dataset.l = l;
                $$('button', sw).forEach(function(b) {
                    var on = b.dataset.l === l;
                    b.classList.toggle('on', on);
                    b.setAttribute('aria-pressed', on);
                });
            }
            cdPaint(true);
            if (typeof lbCount === 'function') lbCount();
        }

        function setLang(l, fade) {
            if (l === lang) return;
            store.set('inv_lang', l);
            if (!fade || reduce) {
                apply(l);
                return;
            }
            body.classList.add('sw');
            setTimeout(function() {
                apply(l);
                body.classList.remove('sw');
            }, 170);
        }
        $$('#lang button').forEach(function(b) {
            b.addEventListener('click', function() {
                setLang(b.dataset.l, true);
            });
        });
        var qs = (location.search.match(/[?&]lang=(en|km)/) || [])[1];
        var first = qs || store.get('inv_lang');
        if (first === 'en') apply('en');

        /* ------------------------------------------------------------------ */

        /* backgrounds (lazy-set so nothing blocks first paint) */
        $$('[data-bg]').forEach(function(el) {
            var u = el.getAttribute('data-bg');
            if (u) el.style.backgroundImage = 'url("' + u.replace(/"/g, '%22') + '")';
        });

        /* scroll reveal */
        var rv = $$('.rv');
        rv.forEach(function(el) {
            if (el.dataset.delay) el.style.setProperty('--d', el.dataset.delay + 'ms');
        });
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function(es) {
                es.forEach(function(e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('in');
                        io.unobserve(e.target);
                    }
                });
            }, {
                threshold: .12,
                rootMargin: '0px 0px -4% 0px'
            });
            rv.forEach(function(el) {
                io.observe(el);
            });
            setTimeout(function() {
                rv.forEach(function(el) {
                    el.classList.add('in');
                });
            }, 8000);

            /* battery saver: pause endless loops while their section is off-screen */
            var off = new IntersectionObserver(function(es) {
                es.forEach(function(e) {
                    e.target.classList.toggle('is-off', !e.isIntersecting);
                });
            }, {
                rootMargin: '80px'
            });
            $$('.hero, section.dark').forEach(function(el) {
                off.observe(el);
            });
        } else rv.forEach(function(el) {
            el.classList.add('in');
        });
        document.addEventListener('visibilitychange', function() {
            root.classList.toggle('paused', document.hidden);
        });

        /* scroll progress + parallax + timeline fill */
        var prog = $('#prog'),
            pars = $$('[data-par]'),
            fills = $$('[data-fill]'),
            tk = false;

        function onScroll() {
            var y = window.pageYOffset,
                max = root.scrollHeight - innerHeight;
            if (prog) prog.style.transform = 'scaleX(' + (max > 0 ? y / max : 0) + ')';
            if (!reduce && y < innerHeight * 1.5) pars.forEach(function(el) {
                el.style.setProperty('--py', (y * parseFloat(el.dataset.par)).toFixed(1) + 'px');
            });
            fills.forEach(function(tl) {
                var r = tl.getBoundingClientRect(),
                    p = (innerHeight * .65 - r.top) / r.height;
                tl.style.setProperty('--f', Math.max(0, Math.min(1, p)).toFixed(3));
            });
            tk = false;
        }
        addEventListener('scroll', function() {
            if (!tk) {
                tk = true;
                requestAnimationFrame(onScroll);
            }
        }, {
            passive: true
        });
        addEventListener('resize', onScroll);
        onScroll();

        /* 3D tilt on portraits (mouse only) */
        if (!reduce && !coarse && matchMedia('(hover:hover)').matches) {
            $$('.tilt').forEach(function(el) {
                el.addEventListener('pointermove', function(e) {
                    var r = el.getBoundingClientRect(),
                        x = (e.clientX - r.left) / r.width - .5,
                        y = (e.clientY - r.top) / r.height - .5;
                    el.style.transform = 'rotateY(' + (x * 14) + 'deg) rotateX(' + (-y * 14) +
                        'deg)';
                });
                el.addEventListener('pointerleave', function() {
                    el.style.transform = '';
                });
            });
        }

        /* button ripple */
        if (!reduce) document.addEventListener('pointerdown', function(e) {
            var b = e.target.closest ? e.target.closest('.btn') : null;
            if (!b || b.disabled) return;
            var r = b.getBoundingClientRect(),
                s = document.createElement('span');
            s.className = 'rp';
            s.style.left = (e.clientX - r.left) + 'px';
            s.style.top = (e.clientY - r.top) + 'px';
            b.appendChild(s);
            setTimeout(function() {
                if (s.parentNode) s.parentNode.removeChild(s);
            }, 750);
        }, {
            passive: true
        });

        /* cover — tap the button or swipe up */
        /* YouTube-backed music: same API as <audio> (play/pause/paused/addEventListener) */
        function ytShim(host) {
            var id = host.dataset.yt,
                L = {
                    play: [],
                    pause: []
                },
                player = null,
                ready = false,
                want = false,
                o = {
                    paused: true,
                    volume: 0.85
                };

            function fire(t) {
                L[t].forEach(function(f) {
                    f();
                });
            }
            o.addEventListener = function(t, f) {
                if (L[t]) L[t].push(f);
            };
            o.play = function() {
                want = true;
                if (!ready) return Promise.reject(new Error('YouTube player not ready'));
                player.setVolume(Math.round(o.volume * 100));
                player.playVideo();
                return new Promise(function(res, rej) {
                    var n = 0,
                        t = setInterval(function() {
                            if (!o.paused) {
                                clearInterval(t);
                                res();
                            } else if (++n > 12) {
                                clearInterval(t);
                                rej(new Error('YouTube blocked or still buffering'));
                            }
                        }, 150);
                });
            };
            o.pause = function() {
                want = false;
                if (ready) player.pauseVideo();
            };

            function build() {
                var d = document.createElement('div');
                host.appendChild(d);
                player = new YT.Player(d, {
                    width: '2',
                    height: '2',
                    videoId: id,
                    playerVars: {
                        playsinline: 1,
                        controls: 0,
                        disablekb: 1,
                        fs: 0,
                        rel: 0,
                        loop: 1,
                        playlist: id,
                        origin: location.origin
                    },
                    events: {
                        onReady: function() {
                            ready = true;
                            player.setVolume(Math.round(o.volume * 100));
                            if (want) player.playVideo();
                        },
                        onStateChange: function(e) {
                            if (e.data === 1) {
                                if (o.paused) {
                                    o.paused = false;
                                    fire('play');
                                }
                            } else if (e.data === 2) {
                                if (!o.paused) {
                                    o.paused = true;
                                    fire('pause');
                                }
                            } else if (e.data === 0) {
                                player.playVideo();
                            }
                        }
                    }
                });
            }
            if (window.YT && YT.Player) build();
            else {
                var prev = window.onYouTubeIframeAPIReady;
                window.onYouTubeIframeAPIReady = function() {
                    if (prev) try {
                        prev();
                    } catch (e) {}
                    build();
                };
                var sc = document.createElement('script');
                sc.src = 'https://www.youtube.com/iframe_api';
                sc.async = true;
                document.head.appendChild(sc);
            }
            return o;
        }

        var cv = $('#cv'),
            mu = $('#mu'),
            bgm = $('#bgm'),
            opened = false;
        if (bgm && bgm.dataset && bgm.dataset.yt) bgm = ytShim(bgm);

        /* music: browsers only allow sound after a real gesture (click / touchend / keydown).
           Scroll and swipe do NOT count, so we retry on every genuine gesture until it plays. */
        var musicWanted = true,
            gestures = ['click', 'touchend', 'pointerup', 'keydown'];

        function unlockMusic(ev) {
            if (ev && ev.target && ev.target.closest && ev.target.closest('#mu')) return;
            if (musicWanted && bgm && bgm.paused) startMusic();
        }

        function clearUnlock() {
            gestures.forEach(function(e) {
                document.removeEventListener(e, unlockMusic, true);
            });
        }

        function startMusic() {
            if (!bgm) return;
            var p = bgm.play();
            if (p && p.then) {
                p.then(clearUnlock).catch(function(err) {
                    if (window.console) console.warn('[bgm] play blocked:', err && err.name, err && err
                        .message);
                });
            } else clearUnlock();
        }
        if (bgm) {
            bgm.volume = 0.85;
            gestures.forEach(function(e) {
                document.addEventListener(e, unlockMusic, true);
            });
            startMusic(); /* works if the browser already allows it */
        }

        function openCover() {
            if (opened || !cv) return;
            opened = true;
            cv.classList.add('open');
            body.style.overflow = '';
            setTimeout(function() {
                var h = $('#hero');
                if (h) h.classList.add('go');
            }, 900);
            setTimeout(function() {
                if (cv.parentNode) cv.parentNode.removeChild(cv);
            }, 2600);
            startMusic();
            if (navigator.vibrate) try {
                navigator.vibrate(12);
            } catch (e) {}
        }
        if (cv) {
            body.style.overflow = 'hidden';
            var op = $('#open');
            if (op) op.addEventListener('click', openCover);
            var y0 = null;
            cv.addEventListener('touchstart', function(e) {
                y0 = e.touches[0].clientY;
            }, {
                passive: true
            });
            cv.addEventListener('touchend', function(e) {
                if (y0 !== null && y0 - e.changedTouches[0].clientY > 70) openCover();
                y0 = null;
            }, {
                passive: true
            });
        } else {
            var hh = $('#hero');
            if (hh) hh.classList.add('go');
        }

        if (mu && bgm) {
            mu.classList.add('show');
            var wasOn = false;
            bgm.addEventListener('play', function() {
                mu.classList.add('on');
                mu.setAttribute('aria-pressed', 'true');
            });
            bgm.addEventListener('pause', function() {
                mu.classList.remove('on');
                mu.setAttribute('aria-pressed', 'false');
            });
            mu.addEventListener('click', function(e) {
                e.stopPropagation();
                if (bgm.paused) {
                    musicWanted = true;
                    startMusic();
                } else {
                    musicWanted = false;
                    bgm.pause();
                }
            });
            /* be polite: silence when the phone locks / tab is hidden, resume after */
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    wasOn = !bgm.paused;
                    bgm.pause();
                } else if (wasOn && musicWanted) {
                    startMusic();
                }
            });
        }

        /* countdown (Khmer numerals in the Khmer view) */
        if (cdEl) {
            var nodes = {},
                last = {},
                vals = {};
            $$('[data-u]', cdEl).forEach(function(n) {
                nodes[n.dataset.u] = n;
            });
            var pad = function(n) {
                return (n < 10 ? '0' : '') + n;
            };
            cdPaint = function(all) {
                for (var k in vals) {
                    if (!nodes[k]) continue;
                    if (!all && last[k] === vals[k]) continue;
                    last[k] = vals[k];
                    nodes[k].textContent = num(pad(vals[k]));
                    if (!reduce && !all) {
                        nodes[k].classList.remove('tick');
                        void nodes[k].offsetWidth;
                        nodes[k].classList.add('tick');
                    }
                }
            };
            var tick = function() {
                var ms = isNaN(evMs) ? 0 : Math.max(0, evMs - Date.now());
                vals = {
                    d: Math.floor(ms / 864e5),
                    h: Math.floor(ms / 36e5) % 24,
                    m: Math.floor(ms / 6e4) % 60,
                    s: Math.floor(ms / 1e3) % 60
                };
                cdPaint(false);
            };
            tick();
            cdPaint(true);
            setInterval(tick, 1000);
        }

        /* gallery lightbox — tap, swipe, arrows */
        var lb = $('#lb'),
            lbCount = null;
        if (lb) {
            var imgs = $$('.gal img').map(function(i) {
                    return i.currentSrc || i.src;
                }),
                cur = 0,
                li = $('#lb-img'),
                lc = $('#lb-c');
            lbCount = function() {
                if (lc) lc.textContent = num(cur + 1) + ' / ' + num(imgs.length);
            };
            var show = function(i, dir) {
                cur = (i + imgs.length) % imgs.length;
                li.src = imgs[cur];
                li.classList.remove('s', 'r');
                void li.offsetWidth;
                li.classList.add('s');
                if (dir < 0) li.classList.add('r');
                lbCount();
                [1, -1].forEach(function(o) {
                    new Image().src = imgs[(cur + o + imgs.length) % imgs.length];
                });
            };
            var close = function() {
                lb.classList.remove('open');
                lb.setAttribute('aria-hidden', 'true');
                body.classList.remove('lb-open');
                body.style.overflow = '';
            };
            $$('.gal button').forEach(function(b) {
                b.addEventListener('click', function() {
                    show(+b.dataset.i, 1);
                    lb.classList.add('open');
                    lb.setAttribute('aria-hidden', 'false');
                    body.classList.add('lb-open');
                    body.style.overflow = 'hidden';
                });
            });
            $('#lb-x').onclick = close;
            $('#lb-p').onclick = function() {
                show(cur - 1, -1);
            };
            $('#lb-n').onclick = function() {
                show(cur + 1, 1);
            };
            lb.addEventListener('click', function(e) {
                if (e.target === lb) close();
            });
            var x0 = null;
            lb.addEventListener('touchstart', function(e) {
                x0 = e.touches[0].clientX;
            }, {
                passive: true
            });
            lb.addEventListener('touchend', function(e) {
                if (x0 === null) return;
                var dx = e.changedTouches[0].clientX - x0;
                x0 = null;
                if (Math.abs(dx) > 48) show(cur + (dx < 0 ? 1 : -1), dx < 0 ? 1 : -1);
            }, {
                passive: true
            });
            document.addEventListener('keydown', function(e) {
                if (!lb.classList.contains('open')) return;
                if (e.key === 'Escape') close();
                if (e.key === 'ArrowLeft') show(cur - 1, -1);
                if (e.key === 'ArrowRight') show(cur + 1, 1);
            });
        }

        /* RSVP */
        var form = $('#rs');
        if (form) {
            var att = $('#rs-att'),
                gw = $('#rs-g'),
                nm = $('#rs-name');
            $$('.seg button').forEach(function(b) {
                b.addEventListener('click', function() {
                    $$('.seg button').forEach(function(x) {
                        var on = x === b;
                        x.classList.toggle('on', on);
                        x.setAttribute('aria-pressed', on);
                    });
                    att.value = b.dataset.v;
                    gw.style.display = b.dataset.v === '1' ? '' : 'none';
                });
            });
            nm.addEventListener('input', function() {
                nm.classList.remove('err');
                $('#rs-err').hidden = true;
            });
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                $('#rs-fail').hidden = true;
                if (!nm.value.trim()) {
                    nm.classList.add('err');
                    $('#rs-err').hidden = false;
                    nm.focus();
                    return;
                }
                var btn = $('#rs-send');
                btn.disabled = true;
                btn.classList.add('loading');
                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(r) {
                        if (!r.ok) throw 0;
                        form.style.display = 'none';
                        var ok = $('#ok');
                        ok.classList.add('show');
                        ok.scrollIntoView({
                            behavior: reduce ? 'auto' : 'smooth',
                            block: 'center'
                        });
                    })
                    .catch(function() {
                        $('#rs-fail').hidden = false;
                        btn.disabled = false;
                        btn.classList.remove('loading');
                    });
            });
        }

        /* copy account + share */
        function flash(span, txt, key) {
            span.textContent = txt;
            setTimeout(function() {
                span.textContent = tr(key);
            }, 1800);
        }
        var cp = $('#copy');
        if (cp) cp.addEventListener('click', function() {
            var n = $('#acc').textContent.trim();
            (navigator.clipboard ? navigator.clipboard.writeText(n) : Promise.reject()).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = n;
                ta.style.cssText = 'position:fixed;opacity:0';
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                } catch (e) {}
                document.body.removeChild(ta);
            });
            if (navigator.vibrate) try {
                navigator.vibrate(10);
            } catch (e) {}
            flash($('#copy-t'), tr('copied'), 'copy');
        });
        var sh = $('#share');
        if (sh) sh.addEventListener('click', function() {
            var url = location.href.split('#')[0];
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: url
                }).catch(function() {});
                return;
            }
            if (navigator.clipboard) navigator.clipboard.writeText(url).then(function() {
                flash($('#share-t'), tr('linkCopied'), 'share');
            });
        });
    })(); /* Hero + cover dust particles. Add before </body>, after your main script. */
    (function() {
        if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        var coarse = window.matchMedia && matchMedia('(pointer:coarse)').matches;

        function fill(id, n) {
            var fx = document.getElementById(id);
            if (!fx || fx.children.length) return; // skip if already filled
            for (var i = 0; i < n; i++) {
                var b = document.createElement('b'),
                    r = Math.random;
                b.style.cssText =
                    'left:' + (r() * 100).toFixed(1) + '%;' +
                    '--dur:' + (8 + r() * 8).toFixed(1) + 's;' +
                    '--dl:' + (-r() * 12).toFixed(1) + 's;' +
                    '--dx:' + ((r() - .5) * 80).toFixed(0) + 'px;' +
                    'width:' + (3 + r() * 4).toFixed(1) + 'px;height:' + (3 + r() * 4).toFixed(1) + 'px';
                fx.appendChild(b);
            }
        }
        fill('hero-fx', coarse ? 14 : 24);
        var cvfx = document.querySelector('.cv-fx');
        if (cvfx) {
            cvfx.id = cvfx.id || 'cv-fx';
            fill(cvfx.id, coarse ? 10 : 16);
        }
    })();
</script>
