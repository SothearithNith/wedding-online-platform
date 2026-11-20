<script>
    (function() {
        'use strict';

        /* ---------- helpers (all null-safe) ---------- */
        var $ = function(s, r) {
            return (r || document).querySelector(s);
        };
        var $$ = function(s, r) {
            return Array.prototype.slice.call((r || document).querySelectorAll(s));
        };
        // add a listener only if the element exists
        var on = function(target, ev, fn, opts) {
            var n = typeof target === 'string' ? $(target) : target;
            if (n) n.addEventListener(ev, fn, opts);
            return n;
        };
        var setText = function(sel, t) {
            var n = $(sel);
            if (n) n.textContent = t;
        };
        var setHtml = function(sel, h) {
            var n = $(sel);
            if (n) n.innerHTML = h;
        };
        var setHidden = function(sel, v) {
            var n = $(sel);
            if (n) n.hidden = v;
        };
        // run a feature in isolation: if it fails, the other features still work
        var safe = function(name, fn) {
            try {
                fn();
            } catch (e) {
                if (window.console) console.error('[invitation] ' + name + ' failed:', e);
            }
        };

        var hasIO = 'IntersectionObserver' in window;
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var T = {
            copy: @json($K['copy']),
            copied: @json($K['copied']),
            share: @json($K['share']),
            linkCopied: @json($K['linkCopied']),
            more: @json($K['more']),
            less: @json($K['less']),
            guestDefault: @json($K['coverGuest'])
        };
        var ICON = {
            check: @json($ic('check', 16)),
            copy: @json($ic('copy', 16)),
            share: @json($ic('share', 16)),
            cal: @json($ic('calplus', 16))
        };

        /* ---------- observe once helper (with a safety timeout so nothing stays hidden) ---------- */
        function onSeen(el, cb, opts, safetyMs) {
            if (!el) return;
            if (!hasIO) {
                cb();
                return;
            }
            var done = false;
            var io;
            var fire = function() {
                if (done) return;
                done = true;
                if (io) io.disconnect();
                cb();
            };
            io = new IntersectionObserver(function(es) {
                if (es[0].isIntersecting) fire();
            }, opts || {
                threshold: 0.15
            });
            io.observe(el);
            if (safetyMs) setTimeout(fire, safetyMs);
        }

        /* ---------- scroll reveal ---------- */
        safe('reveal', function() {
            $$('.rv').forEach(function(el) {
                if (el.dataset.delay) el.style.setProperty('--d', el.dataset.delay + 'ms');
                onSeen(el, function() {
                    el.classList.add('in');
                }, {
                    threshold: 0.12
                }, 4000);
            });
            $$('.rv-title').forEach(function(el) {
                onSeen(el, function() {
                    el.classList.add('in');
                }, {
                    threshold: 0.2
                }, 2500);
            });
        });

        /* ---------- lazy section backgrounds ---------- */
        safe('backgrounds', function() {
            function setBg(el) {
                var u = el.getAttribute('data-bg');
                if (!u) return;
                el.style.backgroundImage = 'url("' + u.replace(/"/g, '%22') + '")';
                el.classList.add('on');
                if (el.classList.contains('g-bgimg')) {
                    var paper = el.parentNode && el.parentNode.classList.contains('g-paper');
                    el.style.setProperty('--bo', paper ? '.1' : (el.style.getPropertyValue('--bo') || '1'));
                }
            }
            $$('[data-bg]').forEach(function(el) {
                if (!el.getAttribute('data-bg')) return;
                if (el.hasAttribute('data-eager') || !hasIO) {
                    setBg(el);
                    return;
                }
                var host = el.classList.contains('g-bgimg') ? el.parentNode : el;
                var io = new IntersectionObserver(function(es) {
                    if (es[0].isIntersecting) {
                        setBg(el);
                        io.disconnect();
                    }
                }, {
                    rootMargin: '700px 0px',
                    threshold: 0
                });
                io.observe(host);
            });
        });

        /* ---------- Khmer date fallback ---------- */
        safe('date', function() {
            $$('.js-date').forEach(function(el) {
                if (el.textContent.trim()) return;
                var d = new Date(el.getAttribute('data-iso'));
                if (isNaN(d)) return;
                try {
                    el.textContent = d.toLocaleDateString('km-KH', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                } catch (e) {}
            });
        });

        /* ---------- couple frames ---------- */
        safe('couple', function() {
            var cp = $('#cp');
            onSeen(cp, function() { 
            }, {
                threshold: 0.15
            }, 3000);
        });

        /* ---------- countdown ---------- */
        safe('countdown', function() {
            var el = $('#cd');
            if (!el) return;
            var t = new Date(el.dataset.date).getTime();
            var nodes = {};
            $$('[data-u]', el).forEach(function(n) {
                nodes[n.dataset.u] = n;
            });
            var last = {};

            function pad(n) {
                return (n < 10 ? '0' : '') + n;
            }

            function tick() {
                var ms = isNaN(t) ? 0 : Math.max(0, t - Date.now());
                var v = {
                    d: Math.floor(ms / 864e5),
                    h: Math.floor(ms / 36e5) % 24,
                    m: Math.floor(ms / 6e4) % 60,
                    s: Math.floor(ms / 1e3) % 60
                };
                for (var k in v) {
                    if (last[k] === v[k]) continue;
                    last[k] = v[k];
                    if (!nodes[k]) continue;
                    nodes[k].textContent = pad(v[k]);
                    if (!reduce) {
                        nodes[k].classList.remove('tick');
                        void nodes[k].offsetWidth;
                        nodes[k].classList.add('tick');
                    }
                }
            }
            tick();
            setInterval(tick, 1000);
        });

        /* ---------- add to calendar (.ics) ---------- */
        safe('calendar', function() {
            var btn = $('#ac-btn');
            if (!btn) return;
            var data = {
                start: @json($w['date']),
                title: @json($icsTitle),
                events: @json($icsEvents)
            };

            function fmt(d) {
                return d.toISOString().replace(/[-:]/g, '').replace(/\.\d{3}/, '');
            }

            function esc(s) {
                return String(s || '').replace(/([,;\\])/g, '\\$1').replace(/\n/g, '\\n');
            }

            function ics() {
                var s = new Date(data.start);
                if (isNaN(s)) throw new Error('bad date');
                var e = new Date(s.getTime() + 3 * 3600 * 1000);
                var place = data.events.length ? (data.events[data.events.length > 1 ? 1 : 0].place || '') : '';
                var desc = data.events.map(function(x) {
                    return (x.time ? x.time + ' ' : '') + x.title;
                }).join(' / ');
                return ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//Invitation//Gold//EN',
                    'CALSCALE:GREGORIAN', 'BEGIN:VEVENT',
                    'UID:' + fmt(s) + '-' + Math.random().toString(36).slice(2) + '@invitation',
                    'DTSTAMP:' + fmt(new Date()), 'DTSTART:' + fmt(s), 'DTEND:' + fmt(e),
                    'SUMMARY:' + esc(data.title), 'LOCATION:' + esc(place), 'DESCRIPTION:' + esc(desc),
                    'END:VEVENT', 'END:VCALENDAR'
                ].join('\r\n');
            }
            btn.addEventListener('click', function() {
                try {
                    var blob = new Blob([ics()], {
                        type: 'text/calendar;charset=utf-8'
                    });
                    var a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = 'wedding.ics';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    setTimeout(function() {
                        URL.revokeObjectURL(a.href);
                    }, 2000);
                } catch (e) {
                    return;
                }
                btn.classList.add('done');
                setHtml('#ac-ic', ICON.check);
                var wrap = document.createElement('span');
                wrap.setAttribute('aria-hidden', 'true');
                var h = '<span class="burst-ring"></span>';
                for (var i = 0; i < 8; i++) h += '<span class="burst-spark" style="--a:' + (i * 45) +
                    'deg"></span>';
                wrap.innerHTML = h;
                btn.appendChild(wrap);
                setTimeout(function() {
                    btn.classList.remove('done');
                    setHtml('#ac-ic', ICON.cal);
                    if (wrap.parentNode) wrap.parentNode.removeChild(wrap);
                }, 2500);
            });
        });

        /* ---------- story line ---------- */
        safe('story', function() {
            var tl = $('#tl');
            onSeen(tl, function() {
                tl.classList.add('seen');
            }, {
                threshold: 0.05
            }, 3000);
        });

        /* ---------- schedule: scroll-filled line + active event ---------- */
        safe('schedule', function() {
            var list = $('#ev-list'),
                fill = $('#ev-fill');
            if (!list || !fill) return;
            var items = $$('#ev-list > li[data-i]');
            var endEl = $('#ev-end');
            var raf = 0,
                reached = -1;

            function clamp(n) {
                return Math.min(1, Math.max(0, n));
            }

            function update() {
                raf = 0;
                var r = list.getBoundingClientRect();
                var p = reduce ? 1 : clamp((window.innerHeight * 0.65 - r.top) / r.height);
                fill.style.transform = 'scaleY(' + p + ')';
            }

            function onScroll() {
                if (!raf) raf = requestAnimationFrame(update);
            }
            update();
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            window.addEventListener('resize', onScroll);

            if (!hasIO) {
                items.forEach(function(li) {
                    li.classList.add('reached');
                });
                if (endEl) endEl.classList.add('on');
                return;
            }
            var io = new IntersectionObserver(function(es) {
                es.forEach(function(en) {
                    if (!en.isIntersecting) return;
                    var i = items.indexOf(en.target);
                    items.forEach(function(li) {
                        li.classList.remove('active');
                    });
                    en.target.classList.add('active');
                    reached = Math.max(reached, i);
                    items.forEach(function(li, k) {
                        li.classList.toggle('reached', k <= reached);
                    });
                    if (endEl) endEl.classList.toggle('on', reached === items.length - 1);
                });
            }, {
                rootMargin: '-42% 0px -42% 0px',
                threshold: 0
            });
            items.forEach(function(li) {
                io.observe(li);
            });
        });

        /* ---------- gallery tiles ---------- */
        safe('gallery-tiles', function() {
            $$('.kg-tile').forEach(function(t) {
                onSeen(t, function() {
                    t.classList.add('kg-in');
                }, {
                    threshold: 0.15
                }, 2500);
            });
        });

        /* ---------- gallery lightbox ---------- */
        safe('lightbox', function() {
            var lb = $('#kg-lb');
            if (!lb) return;
            var imgs = $$('.kg-frame').map(function(b) {
                var im = $('img', b);
                return im ? im.getAttribute('src') : '';
            });
            var thumbs = $$('.kg-thumb', lb);
            var cur = null,
                sx = null;
            var KH = '០១២៣៤៥៦៧៨៩';

            function kh(n) {
                return String(n).replace(/\d/g, function(d) {
                    return KH[+d];
                });
            }

            function show(i) {
                if (!imgs.length) return;
                cur = (i + imgs.length) % imgs.length;
                var f = $('#kg-lb-frame');
                var im = $('#kg-lb-img');
                if (f) {
                    f.style.animation = 'none';
                    void f.offsetWidth;
                    f.style.animation = '';
                }
                if (im) im.src = imgs[cur];
                thumbs.forEach(function(t, k) {
                    t.classList.toggle('on', k === cur);
                });
                setText('#kg-count', kh(cur + 1) + ' / ' + kh(imgs.length));
            }

            function open(i) {
                lb.classList.add('open');
                document.body.style.overflow = 'hidden';
                show(i);
            }

            function close() {
                lb.classList.remove('open');
                document.body.style.overflow = '';
                cur = null;
            }

            $$('.kg-frame').forEach(function(b) {
                b.addEventListener('click', function() {
                    open(+b.dataset.i);
                });
            });
            thumbs.forEach(function(t) {
                t.addEventListener('click', function(e) {
                    e.stopPropagation();
                    show(+t.dataset.i);
                });
            });
            on('#kg-x', 'click', function(e) {
                e.stopPropagation();
                close();
            });
            on('#kg-p', 'click', function(e) {
                e.stopPropagation();
                show(cur - 1);
            });
            on('#kg-n', 'click', function(e) {
                e.stopPropagation();
                show(cur + 1);
            });
            on('#kg-lb-frame', 'click', function(e) {
                e.stopPropagation();
            });
            on('#kg-thumbs', 'click', function(e) {
                e.stopPropagation();
            });
            lb.addEventListener('click', close);
            lb.addEventListener('touchstart', function(e) {
                sx = e.touches[0].clientX;
            }, {
                passive: true
            });
            lb.addEventListener('touchend', function(e) {
                if (sx === null) return;
                var dx = e.changedTouches[0].clientX - sx;
                if (Math.abs(dx) > 50) show(cur + (dx < 0 ? 1 : -1));
                sx = null;
            });
            document.addEventListener('keydown', function(e) {
                if (cur === null) return;
                if (e.key === 'Escape') close();
                if (e.key === 'ArrowRight') show(cur + 1);
                if (e.key === 'ArrowLeft') show(cur - 1);
            });
        });

        /* ---------- RSVP + wishes ---------- */
        safe('rsvp', function() {
            var form = $('#rs-form');
            var nameEl = $('#rs-name');
            if (!form || !nameEl) return;
            var KEY = 'wedding-wishes',
                PREVIEW = 4;
            var attend = true,
                expanded = false,
                wishes = [];
            var err = $('#rs-err'),
                fail = $('#rs-fail'),
                send = $('#rs-send');
            var action = form.getAttribute('action');

            try {
                var parsed = JSON.parse(localStorage.getItem(KEY) || '[]');
                if (Array.isArray(parsed)) wishes = parsed;
            } catch (e) {}

            $$('.rs-toggle button').forEach(function(b) {
                b.addEventListener('click', function() {
                    attend = b.dataset.v === '1';
                    var hid = $('#rs-attending');
                    if (hid) hid.value = attend ? '1' : '0';
                    $$('.rs-toggle button').forEach(function(x) {
                        var isOn = x === b;
                        x.classList.toggle('on', isOn);
                        x.setAttribute('aria-pressed', isOn ? 'true' : 'false');
                    });
                    var gw = $('#rs-guests-wrap');
                    if (gw) gw.style.display = attend ? 'block' : 'none';
                });
            });
            nameEl.addEventListener('input', function() {
                if (err) err.hidden = true;
                nameEl.classList.remove('err');
            });

            function render() {
                var list = $('#wish-list'),
                    more = $('#wish-more');
                if (!list) return;
                list.innerHTML = '';
                setHidden('#wish-none', wishes.length > 0);
                setHidden('#wish-wrap', wishes.length === 0);
                var cnt = $('#wish-count');
                if (cnt) {
                    cnt.hidden = wishes.length === 0;
                    cnt.textContent = wishes.length;
                }
                var shown = expanded ? wishes : wishes.slice(0, PREVIEW);
                shown.forEach(function(x) {
                    var li = document.createElement('li');
                    var av = document.createElement('span');
                    av.className = 'wish-av';
                    av.textContent = (x.name || '?').trim().charAt(0).toUpperCase();
                    var body = document.createElement('div');
                    body.className = 'wish-body';
                    var row = document.createElement('div');
                    row.className = 'wish-row';
                    var nm = document.createElement('p');
                    nm.className = 'wish-name';
                    nm.textContent = x.name;
                    var ok = document.createElement('span');
                    ok.className = 'wish-ok' + (x.attend ? '' : ' no');
                    ok.textContent = (x.attend ? '✓' : '✗') + (x.attend && x.guests ? ' ' + x.guests : '');
                    row.appendChild(nm);
                    row.appendChild(ok);
                    body.appendChild(row);
                    if (x.msg) {
                        var m = document.createElement('p');
                        m.className = 'wish-msg';
                        m.textContent = x.msg;
                        body.appendChild(m);
                    }
                    li.appendChild(av);
                    li.appendChild(body);
                    list.appendChild(li);
                });
                list.classList.toggle('open', expanded);
                var hasMore = wishes.length > PREVIEW;
                setHidden('#wish-fade', expanded || !hasMore);
                if (more) {
                    more.hidden = !hasMore;
                    more.textContent = expanded ? T.less : T.more + ' (' + (wishes.length - PREVIEW) + ')';
                    more.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                }
            }
            on('#wish-more', 'click', function() {
                expanded = !expanded;
                render();
            });
            render();

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var name = nameEl.value.trim();
                if (fail) fail.hidden = true;
                if (!name) {
                    if (err) err.hidden = false;
                    nameEl.classList.add('err');
                    nameEl.focus();
                    return;
                }

                var gEl = $('#rs-guests'),
                    mEl = $('#rs-msg');
                var guests = attend ? ((gEl && +gEl.value) || 1) : 0;
                var msg = mEl ? mEl.value.trim() : '';

                function finish() {
                    wishes = [{
                        id: Date.now().toString(36) + Math.random().toString(36).slice(2),
                        name: name,
                        attend: attend,
                        msg: msg,
                        guests: attend ? guests : undefined
                    }].concat(wishes).slice(0, 50);
                    try {
                        localStorage.setItem(KEY, JSON.stringify(wishes));
                    } catch (x) {}
                    render();
                    form.hidden = true;
                    setHidden('#rs-done', false);
                }

                // No RSVP endpoint (preview / builder): behave like the demo
                if (!action) {
                    finish();
                    return;
                }

                if (send) send.disabled = true;
                var fd = new FormData(form);
                fd.set('guest_name', name);
                if (!attend) fd.set('guests_count', '0');
                fetch(action, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                }).then(function(r) {
                    if (send) send.disabled = false;
                    if (r.ok || (r.status >= 300 && r.status < 400)) finish();
                    else if (fail) fail.hidden = false;
                }).catch(function() {
                    if (send) send.disabled = false;
                    if (fail) fail.hidden = false;
                });
            });
        });

        /* ---------- gift: copy account number ---------- */
        safe('gift', function() {
            var btn = $('#copy-btn');
            if (!btn) return;
            btn.addEventListener('click', function() {
                var accEl = $('#acc-num');
                if (!accEl) return;
                var text = accEl.textContent.replace(/\s/g, '');

                function ok() {
                    btn.classList.add('done');
                    setHtml('#copy-ic', ICON.check);
                    setText('#copy-tx', T.copied);
                    var wrap = document.createElement('span');
                    wrap.setAttribute('aria-hidden', 'true');
                    var h = '<span class="burst-ring"></span>';
                    for (var i = 0; i < 8; i++) h += '<span class="burst-spark" style="--a:' + (i * 45) +
                        'deg"></span>';
                    wrap.innerHTML = h;
                    btn.appendChild(wrap);
                    setTimeout(function() {
                        btn.classList.remove('done');
                        setHtml('#copy-ic', ICON.copy);
                        setText('#copy-tx', T.copy);
                        if (wrap.parentNode) wrap.parentNode.removeChild(wrap);
                    }, 2200);
                }

                function legacy() {
                    try {
                        var ta = document.createElement('textarea');
                        ta.value = text;
                        ta.style.cssText = 'position:fixed;opacity:0';
                        document.body.appendChild(ta);
                        ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                        ok();
                    } catch (e) {}
                }
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(ok, legacy);
                } else {
                    legacy();
                }
            });
        });

        /* ---------- apology reveal ---------- */
        safe('apology', function() {
            var ap = $('#ap');
            onSeen(ap, function() {
                ap.classList.add('ap-shown');
            }, {
                threshold: 0.15
            }, 2500);
        });

        /* ---------- thanks: bloom + share ---------- */
        safe('thanks', function() {
            var th = $('#th');
            onSeen(th, function() {
                th.classList.add('played');
            }, {
                threshold: 0.3
            }, 4000);

            var btn = $('#share-btn');
            if (!btn) return;
            btn.addEventListener('click', function() {
                var url = window.location.href.split('?')[0];

                function copied() {
                    setHtml('#share-ic', ICON.check);
                    setText('#share-tx', T.linkCopied);
                    setTimeout(function() {
                        setHtml('#share-ic', ICON.share);
                        setText('#share-tx', T.share);
                    }, 2000);
                }
                if (navigator.share) {
                    navigator.share({
                        title: document.title,
                        url: url
                    }).catch(function() {});
                } else if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(copied, function() {});
                }
            });
        });

        /* ---------- music: <audio> file, or YouTube with automatic mp3 fallback ---------- */
        var noopMusic = {
            play: function() {},
            toggle: function() {}
        };

        var music = (function() {
            try {
                var audio = $('#bgm'),
                    mbtn = $('#mbtn'),
                    frame = $('#yt-player');
                var ytId = mbtn ? mbtn.getAttribute('data-yt') : '';
                var fallbackSrc = mbtn ? mbtn.getAttribute('data-fallback') : '';
                if (!mbtn || (!audio && !(ytId && frame))) return noopMusic;

                mbtn.classList.remove('hide');
                var playing = false;

                var setOn = function(isOn) {
                    playing = isOn;
                    mbtn.classList.toggle('on', isOn);
                };

                var audioApi = function(el) {
                    el.addEventListener('play', function() {
                        setOn(true);
                    });
                    el.addEventListener('pause', function() {
                        setOn(false);
                    });
                    return {
                        play: function() {
                            var p = el.play();
                            if (p && p.catch) p.catch(function() {});
                        },
                        toggle: function() {
                            if (el.paused) this.play();
                            else el.pause();
                        }
                    };
                };

                var api;

                if (ytId && frame) {
                    var yt = null,
                        ready = false,
                        want = false,
                        failed = false,
                        fb = null,
                        loadTimer = null,
                        startTimer = null;

                    var useFallback = function() {
                        if (failed) return;
                        failed = true;
                        clearTimeout(loadTimer);
                        clearTimeout(startTimer);
                        try {
                            if (yt && yt.destroy) yt.destroy();
                        } catch (e) {}
                        if (!fallbackSrc) return;
                        var a = new Audio(fallbackSrc);
                        a.loop = true;
                        a.preload = 'auto';
                        fb = audioApi(a);
                        if (want) fb.play();
                    };

                    var startYT = function() {
                        try {
                            yt.unMute();
                            yt.setVolume(100);
                            yt.playVideo();
                        } catch (e) {
                            useFallback();
                            return;
                        }
                        // if it has not really started shortly after, switch to the default song
                        clearTimeout(startTimer);
                        startTimer = setTimeout(function() {
                            if (!playing && !failed) useFallback();
                        }, 3500);
                    };

                    window.onYouTubeIframeAPIReady = function() {
                        try {
                            yt = new YT.Player('yt-player', {
                                events: {
                                    onReady: function() {
                                        ready = true;
                                        clearTimeout(loadTimer);
                                        if (want) startYT();
                                    },
                                    onStateChange: function(e) {
                                        if (e.data === 1) {
                                            clearTimeout(startTimer);
                                            setOn(true);
                                        } else if (e.data === 2) {
                                            setOn(false);
                                        }
                                    },
                                    onError: function() {
                                        useFallback();
                                    }
                                }
                            });
                        } catch (e) {
                            useFallback();
                        }
                    };

                    var s = document.createElement('script');
                    s.src = 'https://www.youtube.com/iframe_api';
                    s.async = true;
                    s.onerror = useFallback;
                    document.head.appendChild(s);

                    // blocked iframe / blocked API: never becomes ready
                    loadTimer = setTimeout(function() {
                        if (!ready) useFallback();
                    }, 4000);

                    api = {
                        play: function() {
                            want = true;
                            if (fb) fb.play();
                            else if (ready) startYT();
                        },
                        toggle: function() {
                            if (fb) {
                                fb.toggle();
                                return;
                            }
                            if (playing) {
                                want = false;
                                if (ready) yt.pauseVideo();
                            } else {
                                api.play();
                            }
                        }
                    };
                } else {
                    api = audioApi(audio);
                    // if the field's file/link cannot load, use the default song
                    audio.addEventListener('error', function() {
                        if (fallbackSrc && audio.getAttribute('src') !== fallbackSrc) {
                            audio.setAttribute('src', fallbackSrc);
                            audio.load();
                        }
                    });
                }

                mbtn.addEventListener('click', function() {
                    api.toggle();
                });
                return api;
            } catch (e) {
                if (window.console) console.error('[invitation] music failed:', e);
                return noopMusic;
            }
        })();

        /* ---------- cover (golden doors) ---------- */
        safe('cover', function() {
            var gc = $('#gc');
            if (!gc) return;
            gc.style.display = '';
            document.body.style.overflow = 'hidden';

            // ?guest=Name
            try {
                var g = new URLSearchParams(window.location.search).get('guest');
                if (g) setText('#gc-guest', g.slice(0, 60));
            } catch (e) {}

            var openBtn = $('#gc-open');
            if (!openBtn) {
                // no button: never trap the visitor behind the cover
                document.body.style.overflow = '';
                gc.style.display = 'none';
                return;
            }

            openBtn.addEventListener('click', function(e) {
                if (e.currentTarget && e.currentTarget.blur) e.currentTarget.blur();
                gc.classList.add('opened');
                gc.setAttribute('inert', '');
                document.body.style.overflow = '';

                // timers first, so the doors finish opening even if music fails
                setTimeout(function() {
                    gc.classList.add('slide');
                }, 1000);
                setTimeout(function() {
                    if (gc.parentNode) gc.parentNode.removeChild(gc);
                }, 2900);

                // the click is the user gesture browsers require
                try {
                    music.play();
                } catch (err) {
                    if (window.console) console.warn('music failed', err);
                }
            });
        });
    })();
</script>
