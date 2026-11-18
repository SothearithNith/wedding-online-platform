{{-- Optional motion: gold dust in hero/dark sections + petals after the cover opens --}}
<script>
(function () {
    'use strict';
    if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    function dust(box, n) {
        if (!box) return;
        for (var i = 0; i < n; i++) {
            var b = document.createElement('b');
            b.style.left = ((i * 41 + 7) % 96) + '%';
            b.style.setProperty('--dur', (8 + (i % 6) * 1.7) + 's');
            b.style.setProperty('--dl', '-' + ((i * 1.3) % 9).toFixed(1) + 's');
            b.style.setProperty('--dx', (((i % 5) - 2) * 20) + 'px');
            box.appendChild(b);
        }
    }
    dust(document.getElementById('hero-fx'), 22);
    [].slice.call(document.querySelectorAll('.dk-fx')).forEach(function (b) { dust(b, 18); });

    function petals() {
        var w = document.createElement('div'); w.className = 'pt'; w.setAttribute('aria-hidden', 'true');
        for (var i = 0; i < (innerWidth < 600 ? 10 : 16); i++) {
            var p = document.createElement('i');
            if (i % 4 === 0) p.className = 'pk';
            p.style.left = ((i * 37 + 7) % 100) + '%';
            p.style.setProperty('--dur', (14 + (i % 6) * 2.2) + 's');
            p.style.setProperty('--dl', '-' + ((i * 1.7) % 15).toFixed(1) + 's');
            p.style.setProperty('--dx', ((i % 2 ? 1 : -1) * (30 + (i % 5) * 18)) + 'px');
            w.appendChild(p);
        }
        document.body.appendChild(w);
    }
    var open = document.getElementById('open');
    if (!open) { petals(); return; }
    open.addEventListener('click', function () { setTimeout(petals, 2200); });
})();
</script>
