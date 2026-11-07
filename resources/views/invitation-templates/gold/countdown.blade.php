{{-- ============================ COUNTDOWN ============================ --}}
<section class="g-sec g-paper" id="countdown" style="padding:80px 20px;text-align:center">
    <div class="g-bgimg" data-bg="{{ $bg('countdown') }}"></div>
    <div class="g-chakra"></div>
    <p class="rv" style="font-size:12px;letter-spacing:.3em;color:var(--accent)">{{ $K['date'] }}</p>
    <p class="rv g-foil font-display" data-delay="100" style="margin-top:12px;font-size:15px">
        <span class="js-date" data-iso="{{ $w['date'] }}">{{ $w['dateText'] }}</span>
    </p>

    <div class="rv" data-v="zoom" data-delay="200">
        <div class="cd-box g-foil-box" id="cd" data-date="{{ $w['date'] }}">
            <span class="dm" style="left:-7px;top:-7px"></span>
            <span class="dm" style="right:-7px;top:-7px"></span>
            <span class="dm" style="left:-7px;bottom:-7px"></span>
            <span class="dm" style="right:-7px;bottom:-7px"></span>
            <div class="cd-grid">
                @foreach ($K['countdown'] as $i => $lab)
                    <div>
                        <div style="overflow:hidden"><span class="num g-foil tnum"
                                data-u="{{ ['d', 'h', 'm', 's'][$i] }}">00</span></div>
                        <div style="margin-top:4px;font-size:11px;color:var(--accent)">{{ $lab }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="ac-wrap">
        <button type="button" class="ac-btn" id="ac-btn">
            <span id="ac-ic">{!! $ic('calplus', 16) !!}</span>
            {{ $K['addCal'] }}
            <span id="ac-lotus">{!! $lotus(8, 18, 'g-spin', 0) !!}</span>
        </button>
    </div>
</section>
