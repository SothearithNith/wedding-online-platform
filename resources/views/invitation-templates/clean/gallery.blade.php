{{-- GALLERY --}}
@if (count($gallery))
<section class="cl" id="gallery"><div class="cl-wrap" style="max-width:760px">
    <h2 class="cl-h rv" data-i18n="gallery">{{ $K['gallery'] }}</h2>{!! $orn !!}
    <div class="gal">
        @foreach ($gallery as $i => $src)
            <button type="button" class="rv" data-v="clip" style="--d:{{ ($i % 2) * 120 }}ms" data-i="{{ $i }}" data-i18n-aria="gallery" aria-label="{{ $K['gallery'] }} {{ $i + 1 }}"><img src="{{ $src }}" alt="" loading="lazy" decoding="async"></button>
        @endforeach
    </div>
</div></section>
<div class="lb" id="lb" role="dialog" aria-modal="true" aria-hidden="true">
    <img id="lb-img" src="" alt="">
    <span class="lb-count" id="lb-c" aria-live="polite"></span>
    <button class="x" id="lb-x" data-i18n-aria="close" aria-label="{{ $K['close'] }}">✕</button>
    <button class="p" id="lb-p" data-i18n-aria="prev" aria-label="{{ $K['prev'] }}">‹</button>
    <button class="n" id="lb-n" data-i18n-aria="next" aria-label="{{ $K['next'] }}">›</button>
</div>
@endif
