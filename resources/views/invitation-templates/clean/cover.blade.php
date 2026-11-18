<div class="cv" id="cv">
    <div class="cv-half cv-l">
        <div class="cv-pic" data-bg="{{ $img($w['cover']) ?: $heroBg }}"></div>
    </div>
    <div class="cv-half cv-r">
        <div class="cv-pic" data-bg="{{ $img($w['cover']) ?: $heroBg }}"></div>
    </div>
    <span class="cv-seam" aria-hidden="true"></span>
    <div class="cv-fx" aria-hidden="true">
        @for ($i = 0; $i < 14; $i++)
            <b
                style="left:{{ ($i * 37 + 6) % 96 }}%;--dur:{{ 7 + ($i % 5) * 1.6 }}s;--dl:{{ ($i * 0.7) % 6 }}s;--dx:{{ (($i % 5) - 2) * 18 }}px"></b>
        @endfor
    </div>
    <div class="cv-in">
        <div class="cv-mandala" aria-hidden="true">{!! $lotus(16, 360, 'cv-spin', 0.8) !!}</div>
        <svg class="cv-arch" viewBox="0 0 300 470" aria-hidden="true" preserveAspectRatio="none">
            <path pathLength="100" d="M8 462V160C8 84 74 52 150 6C226 52 292 84 292 160V462Z" />
            <path pathLength="100" d="M20 450V164C20 96 80 66 150 24C220 66 280 96 280 164V450Z" opacity=".5" />
        </svg>
        <div class="cv-body">
            <span class="cv-emblem " style="--d:200ms">{!! $bloom('cl-bloom') !!}</span>
            <small style="--d:400ms">{{ $K['coverInvite'] }}</small>
            <h1>
                <span class="nm title-font" style="--d:600ms">{{ $groom['name'] }}</span>
                <em style="--d:800ms">{{ $K['and'] }}</em>
                <span class="nm title-font" style="--d:1000ms">{{ $bride['name'] }}</span>
            </h1>
            <p class="cv-sub" style="--d:1200ms">{{ $K['coverSub'] }}</p>
            <div style="--d:1400ms"><span class="guest title-font">{{ $guestName }}</span></div>
            <button class="btn shine title-font" id="open" type="button"
                style="--d:1600ms">{!! $ic('mail', 16) !!}
                {{ $K['coverOpen'] }}</button>
        </div>
    </div>
</div>
