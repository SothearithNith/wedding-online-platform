{{-- HERO / CARD --}}
<header class="hero" id="hero">
    <div class="hero-bg" data-bg="{{ $heroBg }}"></div>
    <div class="hero-in">
        <p class="k rv">{{ $K['celebrate'] }}</p>
        <h1 class="rv" style="--d:150ms">{{ $groom['name'] }}<b>{{ $K['and'] }}</b>{{ $bride['name'] }}</h1>
        <p class="rv" style="--d:300ms;max-width:320px;margin:0 auto;font-size:14px">{{ $w['intro'] }}</p>
        <p class="date rv" style="--d:450ms">{{ $w['dateText'] }}</p>
    </div>
    <div class="scroll" aria-hidden="true">{!! $ic('down', 20) !!}</div>
</header>
