<div class="cv" id="cv">
    <div class="cv-half cv-l">
        <div class="cv-pic" data-bg="{{ $img($w['cover']) ?: $heroBg }}"></div>
    </div>
    <div class="cv-half cv-r">
        <div class="cv-pic" data-bg="{{ $img($w['cover']) ?: $heroBg }}"></div>
    </div>
    <span class="cv-seam" aria-hidden="true"></span>
    <div class="cv-fx" aria-hidden="true">
        @for ($i = 0; $i < 10; $i++)
            <b
                style="left:{{ ($i * 37 + 6) % 96 }}%;--dur:{{ 7 + ($i % 5) * 1.6 }}s;--dl:{{ ($i * 0.7) % 6 }}s;--dx:{{ (($i % 5) - 2) * 18 }}px"></b>
        @endfor
    </div>
    <div class="cv-in">
        <div class="cv-mandala" aria-hidden="true">{!! $lotus(16, 360, 'cv-spin', 0.8) !!}</div>
        
        <div class="cv-body">
            <span class="cv-emblem" style="--d:200ms">{!! $bloom('cl-bloom') !!}</span>
            <small style="--d:400ms" data-i18n="coverInvite">{{ $K['coverInvite'] }}</small>
            <h1>
                <span class="nm title-font" style="--d:600ms">{{ $groom['name'] }}</span>
                <em style="--d:800ms" data-i18n="and">{{ $K['and'] }}</em>
                <span class="nm title-font" style="--d:1000ms">{{ $bride['name'] }}</span>
            </h1>
            <p class="cv-sub" style="--d:1200ms" data-i18n="coverSub">{{ $K['coverSub'] }}</p>
            <div style="--d:1400ms"><span class="guest title-font"
                    @if (!$hasGuest) data-i18n="coverGuest" @endif>{{ $guestName }}</span></div>
            <button class="btn shine title-font" id="open" type="button"
                style="--d:1600ms">{!! $ic('mail', 16) !!}
                <span data-i18n="coverOpen">{{ $K['coverOpen'] }}</span></button>
        </div>
    </div>
    <div class="cv-hint title-font" aria-hidden="true"><i></i><span
            data-i18n="swipe">{{ $K['swipe'] ?? 'អូសឡើងលើដើម្បីបើក' }}</span></div>
</div>
