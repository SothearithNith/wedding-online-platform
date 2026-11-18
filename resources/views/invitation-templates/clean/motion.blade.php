{{-- Optional motion: petals fall after the cover opens (progressive enhancement) --}}
<script>
(function () {
    'use strict';
    var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce) return;
    /* petals */
    function petals() {
        var w = document.createElement('div'); w.className = 'pt'; w.setAttribute('aria-hidden', 'true');
        for (var i = 0; i < (innerWidth < 600 ? 10 : 16); i++) {
            var p = document.createElement('i');
            p.style.left = ((i * 37 + 7) % 100) + '%';
            p.style.setProperty('--dur', (14 + (i % 6) * 2.2) + 's');
            p.style.setProperty('--dl', '-' + ((i * 1.7) % 15).toFixed(1) + 's');
            p.style.setProperty('--dx', ((i % 2 ? 1 : -1) * (30 + (i % 5) * 18)) + 'px');
            w.appendChild(p);
        }
        document.body.appendChild(w);
    }


    var cv = document.getElementById('cv');
    if (!cv) { petals(); return; }
    document.getElementById('open').addEventListener('click', function () { setTimeout(petals, 1500); });
})();
</script>
