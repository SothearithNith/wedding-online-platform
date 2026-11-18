<script>
(function () {
    'use strict';
    var $ = function (s, r) { return (r || document).querySelector(s); };
    var $$ = function (s, r) { return [].slice.call((r || document).querySelectorAll(s)); };
    var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    var T = { copied: @json($K['copied']), copy: @json($K['copy']), linkCopied: @json($K['linkCopied']), share: @json($K['share']) };

    /* backgrounds (lazy-set so nothing blocks first paint) */
    $$('[data-bg]').forEach(function (el) {
        var u = el.getAttribute('data-bg');
        if (u) el.style.backgroundImage = 'url("' + u.replace(/"/g, '%22') + '")';
    });

    /* scroll reveal */
    var rv = $$('.rv');
    rv.forEach(function (el) { if (el.dataset.delay) el.style.setProperty('--d', el.dataset.delay + 'ms'); });
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (es) {
            es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
        }, { threshold: .12 });
        rv.forEach(function (el) { io.observe(el); });
        setTimeout(function () { rv.forEach(function (el) { el.classList.add('in'); }); }, 6000);
    } else rv.forEach(function (el) { el.classList.add('in'); });


    /* scroll progress + parallax + timeline fill */
    var prog = $('#prog'), pars = $$('[data-par]'), fills = $$('[data-fill]'), tk = false;
    function onScroll() {
        var h = document.documentElement, y = window.pageYOffset, max = h.scrollHeight - innerHeight;
        if (prog) prog.style.transform = 'scaleX(' + (max > 0 ? y / max : 0) + ')';
        if (!reduce) pars.forEach(function (el) { el.style.setProperty('--py', (y * parseFloat(el.dataset.par)).toFixed(1) + 'px'); });
        fills.forEach(function (tl) {
            var r = tl.getBoundingClientRect(), p = (innerHeight * .65 - r.top) / r.height;
            tl.style.setProperty('--f', Math.max(0, Math.min(1, p)).toFixed(3));
        });
        tk = false;
    }
    addEventListener('scroll', function () { if (!tk) { tk = true; requestAnimationFrame(onScroll); } }, { passive: true });
    addEventListener('resize', onScroll); onScroll();

    /* 3D tilt on portraits (pointer devices) */
    if (!reduce && matchMedia('(hover:hover)').matches) {
        $$('.tilt').forEach(function (el) {
            el.addEventListener('pointermove', function (e) {
                var r = el.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
                el.style.transform = 'rotateY(' + (x * 14) + 'deg) rotateX(' + (-y * 14) + 'deg)';
            });
            el.addEventListener('pointerleave', function () { el.style.transform = ''; });
        });
    }

    /* cover */
    var cv = $('#cv'), mu = $('#mu'), bgm = $('#bgm');
    document.body.style.overflow = 'hidden';
    if (!cv) { var hh = $('#hero'); if (hh) hh.classList.add('go'); }
    $('#open').addEventListener('click', function () {
        cv.classList.add('open');
        document.body.style.overflow = '';
        setTimeout(function () { var h = $('#hero'); if (h) h.classList.add('go'); }, 900);
        setTimeout(function () { if (cv.parentNode) cv.parentNode.removeChild(cv); }, 2600);
        if (bgm) { var p = bgm.play(); if (p && p.catch) p.catch(function () {}); }
    });
    if (mu && bgm) {
        mu.classList.add('show');
        bgm.addEventListener('play', function () { mu.classList.add('on'); });
        bgm.addEventListener('pause', function () { mu.classList.remove('on'); });
        mu.addEventListener('click', function () { bgm.paused ? bgm.play() : bgm.pause(); });
    }

    /* countdown */
    var cd = $('#cd');
    if (cd) {
        var t = new Date(cd.dataset.date).getTime(), nodes = {}, last = {};
        $$('[data-u]', cd).forEach(function (n) { nodes[n.dataset.u] = n; });
        var pad = function (n) { return (n < 10 ? '0' : '') + n; };
        var tick = function () {
            var ms = isNaN(t) ? 0 : Math.max(0, t - Date.now());
            var v = { d: Math.floor(ms / 864e5), h: Math.floor(ms / 36e5) % 24, m: Math.floor(ms / 6e4) % 60, s: Math.floor(ms / 1e3) % 60 };
            for (var k in v) {
                if (last[k] === v[k] || !nodes[k]) continue;
                last[k] = v[k]; nodes[k].textContent = pad(v[k]);
                if (!reduce) { nodes[k].classList.remove('tick'); void nodes[k].offsetWidth; nodes[k].classList.add('tick'); }
            }
        };
        tick(); setInterval(tick, 1000);
    }

    /* gallery lightbox */
    var lb = $('#lb');
    if (lb) {
        var imgs = $$('.gal img').map(function (i) { return i.src; }), cur = 0, li = $('#lb-img');
        var show = function (i) { cur = (i + imgs.length) % imgs.length; li.src = imgs[cur]; li.classList.remove('s'); void li.offsetWidth; li.classList.add('s'); };
        var close = function () { lb.classList.remove('open'); document.body.style.overflow = ''; };
        $$('.gal button').forEach(function (b) {
            b.addEventListener('click', function () { show(+b.dataset.i); lb.classList.add('open'); document.body.style.overflow = 'hidden'; });
        });
        $('#lb-x').onclick = close; $('#lb-p').onclick = function () { show(cur - 1); }; $('#lb-n').onclick = function () { show(cur + 1); };
        lb.addEventListener('click', function (e) { if (e.target === lb) close(); });
        document.addEventListener('keydown', function (e) {
            if (!lb.classList.contains('open')) return;
            if (e.key === 'Escape') close(); if (e.key === 'ArrowLeft') show(cur - 1); if (e.key === 'ArrowRight') show(cur + 1);
        });
    }

    /* RSVP */
    var form = $('#rs');
    if (form) {
        var att = $('#rs-att'), gw = $('#rs-g');
        $$('.seg button').forEach(function (b) {
            b.addEventListener('click', function () {
                $$('.seg button').forEach(function (x) { x.classList.toggle('on', x === b); });
                att.value = b.dataset.v; gw.style.display = b.dataset.v === '1' ? '' : 'none';
            });
        });
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var name = $('#rs-name'); $('#rs-fail').hidden = true;
            if (!name.value.trim()) { name.classList.add('err'); $('#rs-err').hidden = false; name.focus(); return; }
            name.classList.remove('err'); $('#rs-err').hidden = true;
            var btn = $('#rs-send'); btn.disabled = true;
            fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { if (!r.ok) throw 0; form.style.display = 'none'; $('#ok').classList.add('show'); })
                .catch(function () { $('#rs-fail').hidden = false; btn.disabled = false; });
        });
    }

    /* copy account + share */
    function flash(btn, span, txt, back) { span.textContent = txt; setTimeout(function () { span.textContent = back; }, 1800); }
    var cp = $('#copy');
    if (cp) cp.addEventListener('click', function () {
        var n = $('#acc').textContent.trim();
        (navigator.clipboard ? navigator.clipboard.writeText(n) : Promise.reject()).catch(function () {
            var ta = document.createElement('textarea'); ta.value = n; ta.style.cssText = 'position:fixed;opacity:0';
            document.body.appendChild(ta); ta.select(); try { document.execCommand('copy'); } catch (e) {} document.body.removeChild(ta);
        }); flash(cp, $('#copy-t'), T.copied, T.copy);
    });
    var sh = $('#share');
    if (sh) sh.addEventListener('click', function () {
        var url = location.href.split('#')[0];
        if (navigator.share) { navigator.share({ title: document.title, url: url }).catch(function () {}); return; }
        if (navigator.clipboard) navigator.clipboard.writeText(url).then(function () { flash(sh, $('#share-t'), T.linkCopied, T.share); });
    });
})();
</script>
