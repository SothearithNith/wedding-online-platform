<header class="hero" id="hero">
    <div class="hero-bg" data-bg="{{ $heroBg }}" data-par="0.18"></div>
    <div class="hero-rays" aria-hidden="true"></div>
    <div class="hero-fx" id="hero-fx" aria-hidden="true"></div>
    <div class="hero-frame">
        <svg class="hero-arch" viewBox="0 0 300 470" preserveAspectRatio="none" aria-hidden="true">
            <path pathLength="100" d="M8 462V160C8 84 74 52 150 6C226 52 292 84 292 160V462Z" />
            <path pathLength="100" d="M20 450V164C20 96 80 66 150 24C220 66 280 96 280 164V450Z" />
        </svg>
        <div class="hero-in">
            <span class="hi hero-emblem" style="--d:100ms">{!! $bloom('cl-bloom') !!}</span>
            <p class="hi k title-font" style="--d:300ms" data-i18n="celebrate">{{ $K['celebrate'] }}</p>
            <h1>
                <span class="mask"><span class="hi nm title-font" style="--d:500ms">{{ $groom['name'] }}</span></span>
                <b class="hi" style="--d:700ms" data-i18n="and">{{ $K['and'] }}</b>
                <span class="mask"><span class="hi nm title-font" style="--d:900ms">{{ $bride['name'] }}</span></span>
            </h1>
            <p class="hi intro" style="--d:1100ms" data-en="{{ $w['intro_en'] ?? '' }}">{{ $w['intro'] }}</p>
            <div class="hi date title-font" style="--d:1300ms"><span class="hl"></span><span
                    data-i18n-date="long">{{ $w['dateText'] }}</span><span class="hl"></span></div>
            <p class="hi location" style="--d:1500ms">{{ $w['location'] }}</p>
            </p>
        </div>
    </div>
    <div class="scroll title-font" aria-hidden="true"><span data-i18n="scroll">{{ $K['scroll'] }}</span><i></i></div>
</header>
