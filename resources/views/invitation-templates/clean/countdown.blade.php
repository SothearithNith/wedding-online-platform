{{-- COUNTDOWN --}}
<section class="cl alt" id="countdown"><div class="cl-wrap">
    <h2 class="cl-h rv">{{ $K['countdowns'] }}</h2>{!! $orn !!}
    <div class="cd rv" id="cd" data-date="{{ $w['date'] }}">
        @foreach ($K['countdown'] as $i => $lab)
            <div><b data-u="{{ ['d','h','m','s'][$i] }}">00</b><span>{{ $lab }}</span></div>
        @endforeach
    </div>
    <p class="mute rv" style="margin-top:22px">{{ $w['dateText'] }}</p>
</div></section>
