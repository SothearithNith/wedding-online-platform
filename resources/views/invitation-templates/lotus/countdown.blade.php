<section class="g-sec g-photo" id="countdown" style="padding:112px 20px;text-align:center">
    <div class="g-bgimg" data-bg="{{ $bg('countdown') }}"></div>
    <div class="g-ov" style="{{ $overlay(0.08) }}"></div>
    <div class="g-arch-glass">
        <div class="gloss"></div>
        {{-- {!! $title($K['countdowns']) !!} --}}

        <p class="rv ts" style="font-size:12px;letter-spacing:.3em;color:#f6e3a0">{{ $K['date'] }}</p>
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
                            <div class="ts" style="margin-top:4px;font-size:11px;color:rgba(255,255,255,.85)">
                                {{ $lab }}</div>
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
    </div>
    <style>
        /* countdown on glass */
        #countdown .cd-box {
            background: rgba(255, 255, 255, 0.08);
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
        }

        #countdown .cd-box::before {
            border-color: rgba(246, 227, 160, 0.45);
        }

        #countdown .cd-grid>div+div {
            border-left-color: rgba(246, 227, 160, 0.3);
        }

        #countdown .ac-btn {
            background: rgba(0, 0, 0, 0.25);
            border-color: rgba(246, 227, 160, 0.7);
            color: #f6e3a0;
        }

        #countdown .ac-btn:hover,
        #countdown .ac-btn.done {
            background: var(--foil);
            color: #3b2503;
        }
    </style>
</section>
