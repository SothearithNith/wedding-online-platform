<script>
    (function() {
        'use strict';

        var $ = function(s, r) {
            return (r || document).querySelector(s);
        };
        var $$ = function(s, r) {
            return Array.prototype.slice.call((r || document).querySelectorAll(s));
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
            var fire = function() {
                if (done) return;
                done = true;
                io.disconnect();
                cb();
            };
            var io = new IntersectionObserver(function(es) {
                if (es[0].isIntersecting) fire();
            }, opts || {
                threshold: 0.15
            });
            io.observe(el);
            if (safetyMs) setTimeout(fire, safetyMs);
        }

        /* ---------- scroll reveal ---------- */
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

        /* ---------- lazy section backgrounds ---------- */
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

        /* ---------- Khmer date fallback ---------- */
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

        /* ---------- couple frames ---------- */
        onSeen($('#cp'), function() {
            $('#cp').classList.add('kx-on');
        }, {
            threshold: 0.15
        }, 3000);

        /* ---------- countdown ---------- */
        (function() {
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
        })();

        /* ---------- add to calendar (.ics) ---------- */
        (function() {
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
                $('#ac-ic').innerHTML = ICON.check;
                var wrap = document.createElement('span');
                wrap.setAttribute('aria-hidden', 'true');
                var h = '<span class="burst-ring"></span>';
                for (var i = 0; i < 8; i++) h += '<span class="burst-spark" style="--a:' + (i * 45) +
                    'deg"></span>';
                wrap.innerHTML = h;
                btn.appendChild(wrap);
                setTimeout(function() {
                    btn.classList.remove('done');
                    $('#ac-ic').innerHTML = ICON.cal;
                    if (wrap.parentNode) wrap.parentNode.removeChild(wrap);
                }, 2500);
            });
        })();

        /* ---------- story line ---------- */
        onSeen($('#tl'), function() {
            $('#tl').classList.add('seen');
        }, {
            threshold: 0.05
        }, 3000);

        /* ---------- schedule: scroll-filled line + active event ---------- */
        (function() {
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
        })();

        /* ---------- gallery tiles + lightbox ---------- */
        $$('.kg-tile').forEach(function(t) {
            onSeen(t, function() {
                t.classList.add('kg-in');
            }, {
                threshold: 0.15
            }, 2500);
        });
        (function() {
            var lb = $('#kg-lb');
            if (!lb) return;
            var imgs = $$('.kg-frame').map(function(b) {
                return $('img', b).getAttribute('src');
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
                cur = (i + imgs.length) % imgs.length;
                var f = $('#kg-lb-frame');
                var im = $('#kg-lb-img');
                f.style.animation = 'none';
                void f.offsetWidth;
                f.style.animation = '';
                im.src = imgs[cur];
                thumbs.forEach(function(t, k) {
                    t.classList.toggle('on', k === cur);
                });
                $('#kg-count').textContent = kh(cur + 1) + ' / ' + kh(imgs.length);
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
            $('#kg-x').addEventListener('click', function(e) {
                e.stopPropagation();
                close();
            });
            $('#kg-p').addEventListener('click', function(e) {
                e.stopPropagation();
                show(cur - 1);
            });
            $('#kg-n').addEventListener('click', function(e) {
                e.stopPropagation();
                show(cur + 1);
            });
            $('#kg-lb-frame').addEventListener('click', function(e) {
                e.stopPropagation();
            });
            $('#kg-thumbs').addEventListener('click', function(e) {
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
        })();

        /* ---------- RSVP + wishes ---------- */
        (function() {
            var form = $('#rs-form');
            if (!form) return;
            var KEY = 'wedding-wishes',
                PREVIEW = 4;
            var attend = true,
                expanded = false,
                wishes = [];
            var nameEl = $('#rs-name'),
                err = $('#rs-err'),
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
                    $('#rs-attending').value = attend ? '1' : '0';
                    $$('.rs-toggle button').forEach(function(x) {
                        var on = x === b;
                        x.classList.toggle('on', on);
                        x.setAttribute('aria-pressed', on ? 'true' : 'false');
                    });
                    $('#rs-guests-wrap').style.display = attend ? 'block' : 'none';
                });
            });
            nameEl.addEventListener('input', function() {
                err.hidden = true;
                nameEl.classList.remove('err');
            });

            function render() {
                var list = $('#wish-list'),
                    more = $('#wish-more');
                list.innerHTML = '';
                $('#wish-none').hidden = wishes.length > 0;
                $('#wish-wrap').hidden = wishes.length === 0;
                var cnt = $('#wish-count');
                cnt.hidden = wishes.length === 0;
                cnt.textContent = wishes.length;
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
                    ok.textContent = (x.attend ? '✓' : '✗') + (x.attend && x.guests ? ' ' + x.guests :
                        '');
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
                $('#wish-fade').hidden = expanded || !hasMore;
                more.hidden = !hasMore;
                more.textContent = expanded ? T.less : T.more + ' (' + (wishes.length - PREVIEW) + ')';
                more.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            }
            $('#wish-more').addEventListener('click', function() {
                expanded = !expanded;
                render();
            });
            render();

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var name = nameEl.value.trim();
                fail.hidden = true;
                if (!name) {
                    err.hidden = false;
                    nameEl.classList.add('err');
                    nameEl.focus();
                    return;
                }

                var guests = attend ? (+$('#rs-guests').value || 1) : 0;
                var msg = $('#rs-msg').value.trim();

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
                    $('#rs-done').hidden = false;
                }

                // No RSVP endpoint (preview / builder): behave like the demo
                if (!action) {
                    finish();
                    return;
                }

                send.disabled = true;
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
                    send.disabled = false;
                    if (r.ok || (r.status >= 300 && r.status < 400)) finish();
                    else fail.hidden = false;
                }).catch(function() {
                    send.disabled = false;
                    fail.hidden = false;
                });
            });
        })();

        /* ---------- gift: copy account number ---------- */
        (function() {
            var btn = $('#copy-btn');
            if (!btn) return;
            btn.addEventListener('click', function() {
                var text = $('#acc-num').textContent.replace(/\s/g, '');

                function ok() {
                    btn.classList.add('done');
                    $('#copy-ic').innerHTML = ICON.check;
                    $('#copy-tx').textContent = T.copied;
                    var wrap = document.createElement('span');
                    wrap.setAttribute('aria-hidden', 'true');
                    var h = '<span class="burst-ring"></span>';
                    for (var i = 0; i < 8; i++) h += '<span class="burst-spark" style="--a:' + (i *
                        45) + 'deg"></span>';
                    wrap.innerHTML = h;
                    btn.appendChild(wrap);
                    setTimeout(function() {
                        btn.classList.remove('done');
                        $('#copy-ic').innerHTML = ICON.copy;
                        $('#copy-tx').textContent = T.copy;
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
                if (navigator.clipboard && navigator.clipboard.writeText) navigator.clipboard.writeText(
                    text).then(ok, legacy);
                else legacy();
            });
        })();

        /* ---------- apology reveal ---------- */
        onSeen($('#ap'), function() {
            $('#ap').classList.add('ap-shown');
        }, {
            threshold: 0.15
        }, 2500);

        /* ---------- thanks: bloom + share ---------- */
        onSeen($('#th'), function() {
            $('#th').classList.add('played');
        }, {
            threshold: 0.3
        }, 4000);
        (function() {
            var btn = $('#share-btn');
            if (!btn) return;
            btn.addEventListener('click', function() {
                var url = window.location.href.split('?')[0];

                function copied() {
                    $('#share-ic').innerHTML = ICON.check;
                    $('#share-tx').textContent = T.linkCopied;
                    setTimeout(function() {
                        $('#share-ic').innerHTML = ICON.share;
                        $('#share-tx').textContent = T.share;
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
            })
        })();

        /* ---------- cover (golden doors) + music ---------- */
        (function() {
            var gc = $('#gc');
            var audio = $('#bgm'),
                mbtn = $('#mbtn');

            function bindMusic() {
                if (!audio || !mbtn) return;
                mbtn.classList.remove('hide');
                mbtn.onclick = function() {
                    if (audio.paused) audio.play().catch(function() {});
                    else audio.pause();
                };
                audio.addEventListener('play', function() {
                    mbtn.classList.add('on');
                });
                audio.addEventListener('pause', function() {
                    mbtn.classList.remove('on');
                });
            }
            bindMusic();

            if (!gc) return;
            gc.style.display = '';
            document.body.style.overflow = 'hidden';

            // ?guest=Name
            try {
                var g = new URLSearchParams(window.location.search).get('guest');
                if (g) $('#gc-guest').textContent = g.slice(0, 60);
            } catch (e) {}

            $('#gc-open').addEventListener('click', function(e) {
                e.currentTarget.blur();
                gc.classList.add('opened');
                gc.setAttribute('inert', '');
                document.body.style.overflow = '';
                if (audio) audio.play().catch(function() {}); // a click is required for audio
                setTimeout(function() {
                    gc.classList.add('slide');
                }, 1000);
                setTimeout(function() {
                    if (gc.parentNode) gc.parentNode.removeChild(gc);
                }, 2900);
            });
        })();
    })();
</script>
