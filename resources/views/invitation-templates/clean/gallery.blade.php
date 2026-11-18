{{-- GALLERY --}}
@if (count($gallery))
<section class="cl" id="gallery"><div class="cl-wrap" style="max-width:760px">
    <h2 class="cl-h rv">{{ $K['gallery'] }}</h2>{!! $orn !!}
    <div class="gal">
        @foreach ($gallery as $i => $src)
            <button type="button" class="rv" data-v="clip" style="--d:{{ ($i % 3) * 120 }}ms" data-i="{{ $i }}" aria-label="{{ $K['gallery'] }} {{ $i + 1 }}"><img src="{{ $src }}" alt="" loading="lazy"></button>
        @endforeach
    </div>
</div></section>
<div class="lb" id="lb" role="dialog" aria-modal="true">
    <img id="lb-img" src="" alt="">
    <button class="x" id="lb-x" aria-label="{{ $K['close'] }}">✕</button>
    <button class="p" id="lb-p" aria-label="{{ $K['prev'] }}">‹</button>
    <button class="n" id="lb-n" aria-label="{{ $K['next'] }}">›</button>
</div>
@endif
