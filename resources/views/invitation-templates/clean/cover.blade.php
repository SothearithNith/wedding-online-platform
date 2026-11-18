{{-- COVER (slides up when opened) --}}

<div class="cv" id="cv" data-bg="{{ $img($w['cover']) ?: $heroBg }}">
    <div class="cv-in">
        <small style="--d:100ms">{{ $K['coverInvite'] }}</small>
        <h1 style="--d:300ms">{{ $groom['name'] }}<em>{{ $K['and'] }}</em>{{ $bride['name'] }}</h1>
        <div style="--d:500ms" class="mute" style="color:#f8e8bd">{{ $K['coverSub'] }}</div>
        <div style="--d:700ms"><span class="guest">{{ $guestName }}</span></div>
        <button class="btn" id="open" type="button" style="--d:900ms">{!! $ic('mail', 16) !!} {{ $K['coverOpen'] }}</button>
    </div>
</div>
