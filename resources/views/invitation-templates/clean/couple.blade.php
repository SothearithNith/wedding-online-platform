{{-- COUPLE --}}
<section class="cl" id="couple"><div class="cl-wrap">
    <h2 class="cl-h rv">{{ $K['wedding'] }}</h2>{!! $orn !!}
    <div class="pair">
        @foreach ([[$K['groom'], $groom], [$K['bride'], $bride]] as $i => [$label, $p])
            @if ($i === 1)<div class="amp rv">{{ $K['and'] }}</div>@endif
            <article class="person rv" style="--d:{{ $i * 150 }}ms">
                @if ($img($p['photo'] ?? ''))<img src="{{ $img($p['photo']) }}" alt="{{ $p['name'] }}" loading="lazy">@endif
                <span class="role">{{ $label }}</span>
                <h3>{{ $p['name'] }}</h3>
                @if (!empty($p['dad']))<p>{{ trans_json('Mr.') }} {{ implode(' ', $parents($p['dad'])) }}</p>@endif
                @if (!empty($p['mom']))<p>{{ trans_json('Ms.') }} {{ implode(' ', $parents($p['mom'])) }}</p>@endif
            </article>
        @endforeach
    </div>
    @if (!empty($w['quote']))<p class="mute rv" style="margin-top:40px">“{{ $w['quote'] }}”</p>@endif
</div></section>
