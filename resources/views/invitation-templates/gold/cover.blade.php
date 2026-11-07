@php
    $guestRaw = request()->query('guest');
    $hasCover = true;
@endphp

{{-- ============================ COVER (golden doors) ============================ --}}
@if ($hasCover)
    <div class="gc" id="gc" style="display:none">
        <div class="gc-doors" style="position:absolute;inset:0;z-index:0">
            @foreach (['l', 'r'] as $side)
                <div class="gc-door {{ $side }}">
                    <div class="pat"></div>
                    <div class="frame"></div>
                    <div class="cart {{ $side === 'r' ? 'mirror' : '' }}">
                        <svg viewBox="0 0 100 200" preserveAspectRatio="none" fill="none" aria-hidden="true">
                            <defs>
                                <linearGradient id="gcs{{ $side }}" x1="0" y1="0" x2="0"
                                    y2="1">
                                    <stop offset="0" stop-color="#d9dce1" />
                                    <stop offset="1" stop-color="#a4a7ad" />
                                </linearGradient>
                            </defs>
                            <path
                                d="M50 2C58 22 80 26 90 44C98 60 92 78 96 100C92 122 98 140 90 156C80 174 58 178 50 198C42 178 20 174 10 156C2 140 8 122 4 100C8 78 2 60 10 44C20 26 42 22 50 2Z"
                                fill="url(#gcs{{ $side }})" stroke="#d9b44a" stroke-width="2.5"
                                vector-effect="non-scaling-stroke" />
                            <path
                                d="M50 2C58 22 80 26 90 44C98 60 92 78 96 100C92 122 98 140 90 156C80 174 58 178 50 198C42 178 20 174 10 156C2 140 8 122 4 100C8 78 2 60 10 44C20 26 42 22 50 2Z"
                                transform="translate(5 10) scale(.9)" stroke="#d9b44a" stroke-width="1" opacity=".7"
                                vector-effect="non-scaling-stroke" />
                            <image href="{{ asset('assets/images/gold/apsara.png') }}" x="6" y="30" width="88"
                                height="168" preserveAspectRatio="xMidYMax meet" />
                        </svg>
                    </div>
                    <div class="bar"></div>
                    <div class="knob"></div>
                </div>
            @endforeach
        </div>

        <div class="gc-main">
            <div class="gc-img" data-bg="{{ $img($w['cover']) ?: $heroBg }}" data-eager></div>
            <div class="gc-shade"></div>
            <div class="gc-glow"></div>
            <div class="gc-box g-foil-box"></div>
            <div class="gc-corner"></div>

            <div style="position:absolute;inset:0;pointer-events:none">
                @for ($i = 0; $i < 12; $i++)
                    <span class="g-particle"
                        style="left:{{ 8 + (($i * 37) % 85) }}%;top:{{ 12 + (($i * 23) % 75) }}%;--d:{{ $i * 0.65 }}s;--x:{{ ($i % 2 ? 1 : -1) * (12 + $i * 2) }}px"></span>
                @endfor
            </div>
            <div class="g-lotusfl a" aria-hidden="true">❀</div>
            <div class="g-lotusfl b" aria-hidden="true">❀</div>

            <div class="gc-top">
                <div class="g-reveal" style="--d:100ms;color:#e8ca7b">
                    {!! $ic('sparkles', 20) !!}
                    <p class="g-foil font-khmer" style="font-size:15px;letter-spacing:.1em;margin-top:8px">
                        {{ $K['coverTitle'] }}</p>
                </div>
                <div class="g-foil-line" style="width:96px;margin:20px auto 0"></div>
                <p class="g-reveal font-khmer"
                    style="--d:300ms;margin-top:20px;font-size:13px;letter-spacing:.25em;color:#f6e9c8">
                    {{ $K['coverInvite'] }}</p>
                <h1 class="g-reveal g-foil font-khmer"
                    style="--d:500ms;margin-top:20px;font-size:clamp(1.35rem,6.5vw,2rem);font-weight:700;line-height:2">
                    {{ $groom['name'] }}
                    <span
                        style="margin:0 10px;display:inline-block;font-size:18px;font-weight:400">{{ $K['and'] }}</span>
                    {{ $bride['name'] }}
                </h1>
                <p class="g-reveal font-khmer"
                    style="--d:700ms;margin-top:12px;font-size:13px;color:rgba(245,231,197,.9)">
                    {{ $K['coverSub'] }}</p>
                <div class="g-foil-line" style="width:128px;margin:20px auto 0"></div>
            </div>

            <div class="gc-bot">
                <p class="g-reveal font-khmer" style="--d:800ms;font-size:15px;color:#f8e8bd">{{ $K['coverTo'] }}</p>
                <p class="g-reveal font-khmer"
                    style="--d:950ms;margin-top:8px;font-size:13px;color:rgba(255,255,255,.8)">
                    {{ $K['coverDear'] }}</p>
                <div class="gc-guest g-reveal" style="--d:1100ms">
                    <p class="font-khmer" style="font-size:14px;font-weight:600" id="gc-guest"
                        data-default="{{ $K['coverGuest'] }}">
                        {{ is_string($guestRaw) && $guestRaw !== '' ? \Illuminate\Support\Str::limit($guestRaw, 60, '') : $K['coverGuest'] }}
                    </p>
                </div>
                <button type="button" class="gc-open g-reveal font-khmer" id="gc-open" style="--d:1300ms">
                    {!! $ic('mail', 16) !!} {{ $K['coverOpen'] }} <span style="font-size:18px">❧</span>
                </button>
                <p class="g-reveal font-khmer"
                    style="--d:1500ms;margin-top:20px;font-size:10px;letter-spacing:.05em;color:rgba(255,255,255,.65)">
                    {{ $K['coverBye'] }}</p>
            </div>
        </div>
    </div>
@endif
