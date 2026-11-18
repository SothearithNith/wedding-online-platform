{{-- COUPLE: arch portraits with glow, tilt, beating heart --}}
<section class="cl" id="couple"><div class="cl-wrap wide">
    <h2 class="cl-h rv" data-v="blur" data-i18n="wedding">{{ $K['wedding'] }}</h2>{!! $orn !!}
    <div class="pair">
        @foreach ([['groom', $groom, 'left'], ['bride', $bride, 'right']] as $i => [$lk, $p, $dir])
            @if ($i === 1)
                <div class="heart rv" data-v="zoom" data-delay="500" aria-hidden="true">
                    <span class="hr"></span><span class="hr b"></span>
                    <svg viewBox="0 0 32 30"><path d="M23.6 0c-3.4 0-5.5 1.9-7.6 4.2C13.9 1.9 11.8 0 8.4 0 3.8 0 0 3.8 0 8.4c0 9.4 9.5 11.9 16 21.2 6.1-9.3 16-12.1 16-21.2C32 3.8 28.2 0 23.6 0z"/></svg>
                </div>
            @endif
            <article class="person rv" data-v="{{ $dir }}" style="--d:{{ $i * 200 }}ms">
                <div class="ph tilt">
                    <span class="glow"></span>
                    @if ($img($p['photo'] ?? ''))<img src="{{ $img($p['photo']) }}" alt="{{ $p['name'] }}" loading="lazy" decoding="async">@endif
                    <span class="dm a"></span><span class="dm b"></span>
                </div>
                <span class="role" data-i18n="{{ $lk }}">{{ $K[$lk] }}</span>
                <h3>{{ $p['name'] }}</h3>
                @if (!empty($p['dad']))<p><span data-i18n="mr">{{ trans_json('Mr.') }}</span> {{ implode(' ', $parents($p['dad'])) }}</p>@endif
                @if (!empty($p['mom']))<p><span data-i18n="ms">{{ trans_json('Ms.') }}</span> {{ implode(' ', $parents($p['mom'])) }}</p>@endif
            </article>
        @endforeach
    </div>
    @if (!empty($w['quote']))<p class="quote rv" data-delay="200" data-en="{{ $w['quote_en'] ?? '' }}">{{ $w['quote'] }}</p>@endif
</div></section>
