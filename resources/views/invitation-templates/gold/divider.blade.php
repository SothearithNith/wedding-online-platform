@php
    $variant = $variant ?? 'ornament'; 
    $uid = 'gd' . substr(md5(uniqid('', true)), 0, 6);
@endphp

<div class="g-div g-div--{{ $variant }} rv" data-v="zoom" aria-hidden="true">
    @if ($variant === 'band')
        <span class="g-div-band"></span>
    @else
        <svg class="g-div-svg" viewBox="0 0 360 64" role="presentation" focusable="false">
            <defs>
                <linearGradient id="{{ $uid }}-f" gradientUnits="userSpaceOnUse" x1="0" y1="0"
                    x2="360" y2="0">
                    <stop offset="0" stop-color="#8a6414" />
                    <stop offset=".3" stop-color="#d9b44a" />
                    <stop offset=".5" stop-color="#fff0b7" />
                    <stop offset=".7" stop-color="#d9b44a" />
                    <stop offset="1" stop-color="#8a6414" />
                </linearGradient>
                <linearGradient id="{{ $uid }}-l" gradientUnits="userSpaceOnUse" x1="300" y1="0"
                    x2="356" y2="0">
                    <stop offset="0" stop-color="#c9962b" />
                    <stop offset="1" stop-color="#c9962b" stop-opacity="0" />
                </linearGradient>
            </defs>
            @foreach (['r', 'l'] as $side)
                <g @if ($side === 'l') transform="translate(360 0) scale(-1 1)" @endif fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path class="kb-draw" pathLength="100"
                        d="M194 34C208 34 210 20 226 20C242 20 244 34 262 34C280 34 282 46 298 46C312 46 316 38 326 34"
                        stroke="url(#{{ $uid }}-f)" stroke-width="2" />
                    <path class="kb-draw" style="--i:1" pathLength="100"
                        d="M238 25C246 27 247 38 239 38C233 38 233 31 238 31"
                        stroke="url(#{{ $uid }}-f)" stroke-width="1.6" />
                    <path class="kb-draw" style="--i:2" pathLength="100"
                        d="M276 40C269 38 268 28 276 28C282 28 282 35 277 35"
                        stroke="url(#{{ $uid }}-f)" stroke-width="1.6" />
                    <g transform="translate(226 20)">
                        <path class="kb-leaf" style="--i:1"
                            d="M0 0C-6 -4 -7 -11 -2 -16C-1 -18 0 -19 0 -21C0 -19 1 -18 2 -16C7 -11 6 -4 0 0Z"
                            fill="url(#{{ $uid }}-f)" stroke="#6b4a10" stroke-width=".6" />
                    </g>
                    <g transform="translate(298 46) rotate(180)">
                        <path class="kb-leaf" style="--i:2"
                            d="M0 0C-6 -4 -7 -11 -2 -16C-1 -18 0 -19 0 -21C0 -19 1 -18 2 -16C7 -11 6 -4 0 0Z"
                            fill="url(#{{ $uid }}-f)" stroke="#6b4a10" stroke-width=".6" />
                    </g>
                    <path class="kb-draw" style="--i:3" pathLength="100" d="M326 34H356"
                        stroke="url(#{{ $uid }}-l)" stroke-width="1.2" />
                    <path class="kb-leaf" style="--i:3" d="M338 34l3.5 -3.5l3.5 3.5l-3.5 3.5z"
                        fill="#d9b44a" stroke="none" />
                </g>
            @endforeach
            <g class="kb-lotus" stroke="#6b4a10" stroke-width=".8" stroke-linejoin="round">
                @foreach ([[-62, .62, 0], [62, .62, 0], [-32, .85, 1], [32, .85, 1], [0, 1, 2]] as [$rot, $sc, $i])
                    <g transform="translate(180 50) rotate({{ $rot }}) scale({{ $sc }})">
                        <path class="kb-petal" style="--i:{{ $i }}"
                            d="M0 0C-9 -6 -10 -16 -3 -24C-1.5 -26 0 -29 0 -32C0 -29 1.5 -26 3 -24C10 -16 9 -6 0 0Z"
                            fill="url(#{{ $uid }}-f)" />
                    </g>
                @endforeach
                <path d="M180 48V30" stroke="#6b4a10" stroke-width=".7" fill="none" stroke-linecap="round" />
                <rect class="kb-petal" style="--i:3" x="176.5" y="49" width="7" height="7"
                    transform="rotate(45 180 52.5)" fill="#fff0b7" />
                <circle cx="168" cy="52" r="1.6" fill="#d9b44a" stroke="none" />
                <circle cx="192" cy="52" r="1.6" fill="#d9b44a" stroke="none" />
            </g>
        </svg>
    @endif
</div>
