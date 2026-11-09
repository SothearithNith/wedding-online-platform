@php
    $guestRaw = request()->query('guest');
    $hasCover = true;
@endphp

@if ($hasCover)
    <div class="gc" id="gc" style="display:none">
<div class="gc-doors" style="position:absolute;inset:0;z-index:0">
    @foreach (['l', 'r'] as $side)
        <div class="gc-door {{ $side }}">
            <div class="pat"></div>
            {{-- rotating sun-lotus halo centred on the seam (joins into one circle when closed) --}}
            <div class="mand" aria-hidden="true"></div>
            <div class="frame"></div>
            <div class="studs" aria-hidden="true"></div>
            {{-- flowing kbach vine along the outer edge --}}
            <div class="vine" aria-hidden="true"></div>

            <div class="cart {{ $side === 'r' ? 'mirror' : '' }}">
                <svg viewBox="0 0 100 240" fill="none" aria-hidden="true">
                    <defs>
                        {{-- light gold --}}
                        <linearGradient id="gcg{{ $side }}" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#fff0b7" />
                            <stop offset=".45" stop-color="#d9b44a" />
                            <stop offset="1" stop-color="#8a6414" />
                        </linearGradient>
                        {{-- bronze for outlines --}}
                        <linearGradient id="gcb{{ $side }}" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#c19a2e" />
                            <stop offset="1" stop-color="#5a3d0c" />
                        </linearGradient>
                        {{-- pearl-champagne niche, so the apsara stands out from the gold door --}}
                        <linearGradient id="gcn{{ $side }}" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#fffaf0" />
                            <stop offset=".6" stop-color="#f3e2ae" />
                            <stop offset="1" stop-color="#d8b45a" />
                        </linearGradient>
                    </defs>

                    {{-- kanok flame finial --}}
                    <path class="flame" d="M50 1C55 6 55 12 50 18C45 12 45 6 50 1Z" fill="url(#gcb{{ $side }})" />
                    <circle cx="50" cy="21" r="1.7" fill="#5a3d0c" />

                    {{-- kbach curls that "write themselves" --}}
                    <g class="curls" stroke="#6b4a10" stroke-width="1.3" stroke-linecap="round">
                        <path pathLength="100" d="M20 28C29 27 33 36 27 41C23 44 19 40 22 37" />
                        <path pathLength="100" d="M20 28C14 29 12 35 15 39" />
                        <g transform="translate(100 0) scale(-1 1)">
                            <path pathLength="100" d="M20 28C29 27 33 36 27 41C23 44 19 40 22 37" />
                            <path pathLength="100" d="M20 28C14 29 12 35 15 39" />
                        </g>
                    </g>

                    {{-- pointed arch niche --}}
                    <path d="M50 18C52 36 66 46 84 66L84 232L16 232L16 66C34 46 48 36 50 18Z"
                        fill="url(#gcn{{ $side }})" stroke="url(#gcb{{ $side }})" stroke-width="3"
                        stroke-linejoin="round" vector-effect="non-scaling-stroke" />
                    {{-- inner arch: a line of gold beads that travels around the niche --}}
                    <path class="beads" d="M50 18C52 36 66 46 84 66L84 232L16 232L16 66C34 46 48 36 50 18Z"
                        transform="translate(5 14) scale(.9)" stroke="#8a6414" stroke-width="2.4"
                        stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />

                    {{-- lotus: petals bloom open one after another --}}
                    <g fill="url(#gcg{{ $side }})" stroke="#6b4a10" stroke-width=".8">
                        @foreach ([-62, 62, -32, 32, 0] as $k => $deg)
                            <g transform="rotate({{ $deg }} 50 234)">
                                <path class="dl-petal" style="--i:{{ $k }}"
                                    d="M50 234C60 220 60 202 50 184C40 202 40 220 50 234Z"
                                    vector-effect="non-scaling-stroke" />
                            </g>
                        @endforeach
                    </g>

                    <image class="apsara" href="{{ asset('assets/images/gold/apsara.png') }}" x="4" y="40"
                        width="92" height="194" preserveAspectRatio="xMidYMax meet" />
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
                <div class="gc-logo g-reveal" style="--d:100ms">
                    <span class="gc-logo-halo" aria-hidden="true"></span>
                    <span class="gc-spark" aria-hidden="true">{!! $ic('sparkles', 18) !!}</span>
                    <img src="{{ asset('assets/images/gold/gold-wedding.png') }}" alt="" width="500"
                        height="180" class="royal-logo gc-logo-img" onerror="this.style.display='none'">
                </div>

                <div class="gc-orn g-reveal" style="--d:300ms" aria-hidden="true">
                    <span></span><i></i><span></span>
                </div>

                <p class="gc-invite font-khmer g-reveal" style="--d:450ms">{{ $K['coverInvite'] }}</p>

                <h1 class="gc-names title-font g-reveal" style="--d:600ms">
                    <span class="gc-name g-foil">{{ $groom['name'] }}</span>
                    <span class="gc-amp" aria-hidden="true">{{ $K['and'] }}</span>
                    <span class="gc-name g-foil">{{ $bride['name'] }}</span>
                </h1>

                <p class="gc-sub font-khmer g-reveal" style="--d:800ms">{{ $K['coverSub'] }}</p>

                <div class="gc-orn wide g-reveal" style="--d:950ms" aria-hidden="true">
                    <span></span><i></i><span></span>
                </div>
            </div>

            <div class="gc-bot">
                <p class="g-reveal font-khmer" style="--d:800ms;font-size:15px;color:#f8e8bd">{{ $K['coverTo'] }}</p>
                <p class="g-reveal font-khmer"
                    style="--d:950ms;margin-top:8px;font-size:13px;color:rgba(255,255,255,.8)">
                    {{ $K['coverDear'] }}</p>
                <div class="gc-guest g-reveal" style="--d:1100ms; border-radius: 12px; overflow: hidden;">
                    <p class="font-khmer" style="font-size:14px;font-weight:600" id="gc-guest"
                        data-default="{{ $K['coverGuest'] }}">
                        {{ is_string($guestRaw) && $guestRaw !== '' ? \Illuminate\Support\Str::limit($guestRaw, 60, '') : $K['coverGuest'] }}
                    </p>
                </div>
                <button type="button" class="gc-open g-reveal font-khmer" id="gc-open" style="--d:1300ms">
                    {!! $ic('mail', 16) !!}
                    {{ $K['coverOpen'] }}
                    <span class="gc-open-ornament">❧</span>
                </button>
                <p class="g-reveal font-khmer"
                    style="--d:1500ms;margin-top:20px;font-size:10px;letter-spacing:.05em;color:rgba(255,255,255,.65)">
                    {{ $K['coverBye'] }}</p>
            </div>
        </div>
    </div>
@endif
