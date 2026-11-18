{{-- COUNTDOWN: night section, glowing gold digits --}}
<section class="cl dark" id="countdown">
    <div class="dk-mandala" aria-hidden="true">{!! $lotus(16, 520, 'cv-spin', .6) !!}</div>
    <div class="dk-fx" aria-hidden="true"></div>
    <div class="cl-wrap">
        <h2 class="cl-h rv" data-v="blur" data-i18n="countdowns">{{ $K['countdowns'] }}</h2>{!! $orn !!}
        <div class="cd rv" data-v="zoom" id="cd" data-date="{{ $w['date'] }}">
            @foreach ($K['countdown'] as $i => $lab)
                <div><b data-u="{{ ['d','h','m','s'][$i] }}">00</b><span data-i18n="cd{{ $i }}">{{ $lab }}</span></div>
            @endforeach
        </div>
        <p class="rv date-t" data-delay="200" data-i18n-date="long">{{ $w['dateText'] }}</p>
    </div>
</section>
