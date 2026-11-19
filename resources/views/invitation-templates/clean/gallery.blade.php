{{-- GALLERY (clean) — staggered 3-per-row prints, shimmer while loading, graceful broken-image fallback,
     and a self-contained lightbox (framed photo, counter, arrows, swipe, keys, thumbnail strip).
     Keeps your ids/hooks: .gal, data-i, data-v="clip", --d, #lb #lb-img #lb-c #lb-x #lb-p #lb-n. --}}
@if (count($gallery))
    @php $gTotal = count($gallery); @endphp
    <section class="cl" id="gallery">
        <div class="cl-wrap" style="max-width:760px">
            <h2 class="cl-h rv title-font" data-i18n="gallery">{{ $K['gallery'] }}</h2>{!! $orn !!}

            <div class="gal gal-m{{ $gTotal === 1 ? ' gal-one' : '' }}">
                @foreach ($gallery as $i => $src)
                    <button type="button" class="rv gal-item" data-v="clip" style="--d:{{ ($i % 3) * 110 }}ms"
                        data-i="{{ $i }}" data-i18n-aria="gallery"
                        aria-label="{{ $K['gallery'] }} {{ $i + 1 }}">
                        <span class="gal-frame">
                            <img src="{{ $src }}" alt="" decoding="async"
                                loading="{{ $i < 6 ? 'eager' : 'lazy' }}"
                                onload="this.classList.add('ld')"
                                onerror="this.closest('.gal-item').classList.add('is-broken')">
                            <span class="gal-fallback" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path d="m21 16-5-5-8 8"/></svg>
                            </span>
                            <span class="gal-zoom" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                            </span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <div class="lb" id="lb" role="dialog" aria-modal="true" aria-hidden="true">
        <img id="lb-img" src="" alt="">
        <span class="lb-count" id="lb-c" aria-live="polite"></span>
        <button type="button" class="x" id="lb-x" data-i18n-aria="close" aria-label="{{ $K['close'] }}">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
        <button type="button" class="p" id="lb-p" data-i18n-aria="prev" aria-label="{{ $K['prev'] }}">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 5-7 7 7 7"/></svg>
        </button>
        <button type="button" class="n" id="lb-n" data-i18n-aria="next" aria-label="{{ $K['next'] }}">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 5 7 7-7 7"/></svg>
        </button>
        @if ($gTotal > 1)
            <div class="lb-th" id="lb-th">
                @foreach ($gallery as $i => $src)
                    <button type="button" data-t="{{ $i }}" tabindex="-1" aria-hidden="true"><img src="{{ $src }}" alt="" loading="lazy"></button>
                @endforeach
            </div>
        @endif
    </div>

    <style>
    /* ================= GRID ================= */
    #gallery .gal-m{--g:clamp(.9rem,3.2vw,1.3rem);display:flex!important;flex-wrap:wrap;justify-content:center;
        gap:var(--g);margin-top:1.8rem;padding-bottom:18px}
    #gallery .gal-item{all:unset;box-sizing:border-box;display:block;position:relative;cursor:pointer;
        width:calc((100% - 2 * var(--g)) / 3);aspect-ratio:3/4}
    #gallery .gal-item:nth-child(3n+2){transform:translateY(18px)!important}
    #gallery .gal-m.gal-one .gal-item{width:min(60%,240px);transform:none!important}
    #gallery .gal-item::before,#gallery .gal-item::after{content:none!important}
    #gallery .gal-item:focus-visible{outline:2px solid var(--accent,#d9b44a);outline-offset:4px;border-radius:4px}

    /* ================= FRAME: arch window + double gold line ================= */
    #gallery .gal-item{--r:50% 50% 10px 10px / 37.5% 37.5% 10px 10px}
    #gallery .gal-frame{position:absolute;inset:0;display:block;overflow:hidden;border-radius:var(--r);
        background:#f6edd9;
        outline:1px solid color-mix(in srgb,var(--accent,#d9b44a) 80%,transparent);outline-offset:5px;
        box-shadow:0 16px 28px -16px rgba(130,92,20,.55);
        transition:transform .45s cubic-bezier(.2,.8,.2,1),box-shadow .45s,outline-offset .45s}
    /* shimmer skeleton while the photo loads */
    #gallery .gal-frame::before{content:"";position:absolute;inset:0;
        background:linear-gradient(100deg,#f3ead7 30%,#fbf6ea 50%,#f3ead7 70%);background-size:220% 100%;
        animation:galSh 1.4s linear infinite}
    @keyframes galSh{to{background-position:-220% 0}}
    #gallery .gal-frame img{position:absolute!important;inset:0!important;display:block;
        width:100%!important;height:100%!important;max-width:none!important;border:0!important;border-radius:0!important;
        aspect-ratio:auto!important;object-fit:cover;object-position:center 25%;filter:none;outline:0;
        transition:transform .8s cubic-bezier(.2,.8,.2,1),opacity .5s}
    .js #gallery .gal-frame img:not(.ld){opacity:0}
    #gallery .gal-item:hover .gal-frame,#gallery .gal-item:focus-visible .gal-frame{
        transform:translateY(-4px);outline-offset:9px;box-shadow:0 22px 34px -16px rgba(130,92,20,.6)}
    #gallery .gal-item:hover .gal-frame img{transform:scale(1.06)}

    #gallery .gal-zoom{position:absolute;right:50%;bottom:12px;margin-right:-14px;width:28px;height:28px;display:grid;place-items:center;
        border-radius:50%;color:#fff;background:rgba(0,0,0,.35);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);
        opacity:0;transform:translateY(6px);transition:opacity .3s,transform .3s}
    #gallery .gal-item:hover .gal-zoom,#gallery .gal-item:focus-visible .gal-zoom{opacity:1;transform:none}
    @media (hover:none){#gallery .gal-zoom{display:none}}

    /* broken image: calm placeholder instead of an empty hole */
    #gallery .gal-fallback{position:absolute;inset:0;display:none;place-items:center;
        color:color-mix(in srgb,var(--accent,#d9b44a) 80%,#7a5b12);background:#faf3e3}
    #gallery .gal-item.is-broken .gal-fallback{display:grid}
    #gallery .gal-item.is-broken .gal-frame::before,#gallery .gal-item.is-broken img,#gallery .gal-item.is-broken .gal-zoom{display:none}

    @media (max-width:420px){
        #gallery .gal-m:not(.gal-one) .gal-item{width:calc((100% - var(--g)) / 2)}
        #gallery .gal-item:nth-child(3n+2){transform:none!important}
        #gallery .gal-item:nth-child(even){transform:translateY(14px)!important}
    }

    /* ================= LIGHTBOX ================= */
    #lb{position:fixed!important;inset:0!important;z-index:9999!important;display:none!important;
        align-items:center;justify-content:center;opacity:1!important;visibility:visible!important;
        pointer-events:auto!important;transform:none!important;
        background:rgba(18,12,6,.93);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px)}
    #lb.gx-open{display:flex!important;animation:gxIn .3s ease}
    @keyframes gxIn{from{opacity:0}to{opacity:1}}
    #lb #lb-img{position:relative!important;inset:auto!important;transform:none!important;display:block;
        width:auto!important;height:auto!important;max-width:min(90vw,860px)!important;
        max-height:calc(100vh - 200px)!important;object-fit:contain;padding:0!important;background:transparent;border-radius:8px;
        border:1px solid color-mix(in srgb,var(--accent,#d9b44a) 85%,transparent);
        outline:1px solid color-mix(in srgb,var(--accent,#d9b44a) 45%,transparent);outline-offset:7px;
        box-shadow:0 30px 70px -20px rgba(0,0,0,.8);transition:opacity .25s}
    #lb #lb-img.sw{opacity:0}
    #lb .lb-count{position:absolute!important;left:50%;top:clamp(16px,3vh,28px);bottom:auto!important;right:auto!important;
        transform:translateX(-50%)!important;font-size:.78rem;letter-spacing:.2em;color:#fff;opacity:.85}
    #lb .x,#lb .p,#lb .n{position:absolute!important;display:grid;place-items:center;width:44px;height:44px;padding:0;
        cursor:pointer;color:#fff;border-radius:50%;border:1px solid rgba(255,255,255,.3);
        background:rgba(255,255,255,.12);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
        transition:background .25s,transform .25s}
    #lb .x{top:clamp(12px,2.4vh,22px)!important;right:clamp(12px,2.4vw,22px)!important;left:auto!important;bottom:auto!important}
    #lb .p,#lb .n{top:50%!important;bottom:auto!important;margin-top:-22px}
    #lb .p{left:clamp(8px,2vw,22px)!important;right:auto!important}
    #lb .n{right:clamp(8px,2vw,22px)!important;left:auto!important}
    #lb .x:hover,#lb .p:hover,#lb .n:hover{background:var(--accent,#d9b44a);transform:scale(1.06)}
    #lb .x:focus-visible,#lb .p:focus-visible,#lb .n:focus-visible{outline:2px solid #fff;outline-offset:3px}
    #lb.gx-single .p,#lb.gx-single .n{display:none}

    #lb .lb-th{position:absolute;left:50%;bottom:clamp(12px,2.4vh,22px);transform:translateX(-50%);
        display:flex;gap:8px;max-width:min(92vw,520px);padding:8px;overflow-x:auto;scrollbar-width:none;
        border-radius:14px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18)}
    #lb .lb-th::-webkit-scrollbar{display:none}
    #lb .lb-th button{all:unset;flex:none;width:42px;height:42px;border-radius:7px;overflow:hidden;cursor:pointer;
        opacity:.5;transition:opacity .25s,transform .25s,box-shadow .25s}
    #lb .lb-th button img{width:100%;height:100%;object-fit:cover;display:block}
    #lb .lb-th button:hover{opacity:.85}
    #lb .lb-th button.on{opacity:1;transform:translateY(-3px);box-shadow:0 0 0 2px var(--accent,#d9b44a)}
    @media (max-width:520px){#lb .p,#lb .n{display:none}}  /* swipe + thumbnails on phones */
    @media (prefers-reduced-motion:reduce){#gallery *,#lb,#lb *{transition:none!important;animation:none!important}}
    </style>

    <script>
    (function(){
      var lb=document.getElementById('lb'); if(!lb) return;
      var img=document.getElementById('lb-img'), cnt=document.getElementById('lb-c'), th=document.getElementById('lb-th');
      var items=[].slice.call(document.querySelectorAll('#gallery .gal-item'));
      var srcs=items.map(function(b){return b.querySelector('img').src});
      var n=srcs.length, cur=0, last=null, x0=null;
      if(n<2) lb.classList.add('gx-single');

      // cached images can finish before onload is attached
      items.forEach(function(b){var m=b.querySelector('img'); if(m.complete&&m.naturalWidth) m.classList.add('ld');});

      function pre(i){var p=new Image(); p.src=srcs[(i+n)%n];}
      function mark(){
        if(!th) return;
        [].forEach.call(th.children,function(b,i){
          var on=i===cur; b.classList.toggle('on',on);
          if(on) th.scrollTo({left:b.offsetLeft-th.clientWidth/2+b.clientWidth/2,behavior:'smooth'});
        });
      }
      function show(i){
        cur=(i+n)%n;
        img.classList.add('sw');
        setTimeout(function(){img.src=srcs[cur]; img.classList.remove('sw');},lb.classList.contains('gx-open')?120:0);
        cnt.textContent=n>1?(cur+1)+' / '+n:'';
        lb.classList.add('gx-open'); lb.setAttribute('aria-hidden','false');
        document.documentElement.style.overflow='hidden';
        if(n>1){pre(cur+1);pre(cur-1);} mark();
      }
      function hide(){
        lb.classList.remove('gx-open'); lb.setAttribute('aria-hidden','true');
        document.documentElement.style.overflow='';
        if(last) last.focus({preventScroll:true});
      }
      function open(){return lb.classList.contains('gx-open');}

      // capture phase + stopPropagation: any older lightbox script can't double-handle
      window.addEventListener('click',function(e){
        var t=e.target; if(!t.closest) return;
        var it=t.closest('#gallery .gal-item');
        if(it){ if(it.classList.contains('is-broken')) return; e.stopPropagation(); last=it; show(+it.dataset.i||0); return; }
        if(!open()) return;
        var tb=t.closest('#lb-th button');
        if(tb){e.stopPropagation(); show(+tb.dataset.t); return;}
        if(t.closest('#lb-x')||t===lb){e.stopPropagation(); hide();}
        else if(t.closest('#lb-n')){e.stopPropagation(); show(cur+1);}
        else if(t.closest('#lb-p')){e.stopPropagation(); show(cur-1);}
      },true);

      window.addEventListener('keydown',function(e){
        if(!open()) return; var k=e.key;
        if(k==='Escape'){e.stopPropagation();hide();}
        else if(k==='ArrowRight'){e.stopPropagation();show(cur+1);}
        else if(k==='ArrowLeft'){e.stopPropagation();show(cur-1);}
      },true);

      lb.addEventListener('touchstart',function(e){x0=e.touches[0].clientX},{passive:true});
      lb.addEventListener('touchend',function(e){
        if(x0===null||n<2) return;
        var dx=e.changedTouches[0].clientX-x0; x0=null;
        if(Math.abs(dx)>50) show(cur+(dx<0?1:-1));
      });
    })();
    </script>
@endif
