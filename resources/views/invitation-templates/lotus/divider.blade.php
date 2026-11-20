@php
    $variant = $variant ?? 'lotus';
    $u = 'gd' . \Illuminate\Support\Str::random(5);
@endphp

@if ($variant === 'band')
    <div class="g-div g-div--band rv" aria-hidden="true">
        <i class="g-div-band"></i>
    </div>
@else
    <div class="g-div g-div--orn rv" aria-hidden="true">
        <svg class="g-div-svg gdo" viewBox="0 0 600 110" xmlns="http://www.w3.org/2000/svg">
            <defs>
                {{-- base gold foil --}}
                <linearGradient id="{{ $u }}-base" gradientUnits="userSpaceOnUse" x1="0" y1="0"
                    x2="600" y2="110">
                    <stop offset="0" stop-color="#a87c2e" />
                    <stop offset=".2" stop-color="#f6e3a0" />
                    <stop offset=".4" stop-color="#e3bd5a" />
                    <stop offset=".5" stop-color="#fff1b8" />
                    <stop offset=".6" stop-color="#e3bd5a" />
                    <stop offset=".8" stop-color="#f6e3a0" />
                    <stop offset="1" stop-color="#a87c2e" />
                </linearGradient>

                {{-- moving highlight --}}
                <linearGradient id="{{ $u }}-shine" gradientUnits="userSpaceOnUse" x1="-130"
                    y1="0" x2="0" y2="0">
                    <stop offset="0" stop-color="#fff" stop-opacity="0" />
                    <stop offset=".5" stop-color="#fffbe6" stop-opacity=".95" />
                    <stop offset="1" stop-color="#fff" stop-opacity="0" />
                    <animateTransform attributeName="gradientTransform" type="translate" values="0 0;730 0;730 0"
                        keyTimes="0;.55;1" dur="6s" begin="1.8s" repeatCount="indefinite" />
                </linearGradient>

                {{-- ============ LEFT HALF ============ --}}
                <g id="{{ $u }}-half">
                    {{-- line work (inherits stroke) --}}
                    <g fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path class="kb-draw" style="--i:0" pathLength="100" d="M12 55H128" />
                        {{-- big scrolls --}}
                        <path class="kb-draw" style="--i:1" pathLength="100"
                            d="M128 55C128 36 114 24 98 26C84 28 80 42 90 46C98 49 104 42 99 38" />
                        <path class="kb-draw" style="--i:1" pathLength="100"
                            d="M128 55C128 74 114 86 98 84C84 82 80 68 90 64C98 61 104 68 99 72" />
                        {{-- small outer scrolls --}}
                        <path class="kb-draw" style="--i:2" pathLength="100"
                            d="M94 55C88 44 72 40 60 44C52 47 54 56 62 54" />
                        <path class="kb-draw" style="--i:2" pathLength="100"
                            d="M94 55C88 66 72 70 60 66C52 63 54 54 62 56" />
                        {{-- lens arcs --}}
                        <path class="kb-draw" style="--i:3" pathLength="100"
                            d="M128 55C140 40 158 28 176 30C190 32 192 44 186 55" />
                        <path class="kb-draw" style="--i:3" pathLength="100"
                            d="M128 55C140 70 158 82 176 80C190 78 192 66 186 55" />
                        {{-- diamond lattice --}}
                        <path class="kb-draw" style="--i:4" pathLength="100" d="M186 55L219 30L252 55L219 80Z" />
                        <path class="kb-draw" style="--i:5" pathLength="100" d="M200 55L219 41L238 55L219 69Z" />
                        <path class="kb-draw" style="--i:5" pathLength="100"
                            d="M219 30C226 20 238 16 248 20C256 24 254 34 246 33" />
                        <path class="kb-draw" style="--i:5" pathLength="100"
                            d="M219 80C226 90 238 94 248 90C256 86 254 76 246 77" />
                        <path class="kb-draw" style="--i:6" pathLength="100" d="M252 55H266" />
                    </g>

                    {{-- solid leaves & beads (inherit fill) --}}
                    <g stroke="none">
                        <g class="kb-leaf" style="--i:0">
                            <path d="M2 55C16 48 34 48 52 55C34 62 16 62 2 55Z" />
                        </g>
                        <g class="kb-leaf" style="--i:1">
                            <path d="M26 52C30 42 40 38 48 38C46 46 38 52 26 52Z" />
                            <path d="M26 58C30 68 40 72 48 72C46 64 38 58 26 58Z" />
                        </g>
                        <g class="kb-leaf" style="--i:2">
                            <path d="M58 52C62 44 70 40 78 40C76 47 68 52 58 52Z" />
                            <path d="M58 58C62 66 70 70 78 70C76 63 68 58 58 58Z" />
                        </g>
                        <g class="kb-leaf" style="--i:3">
                            <path d="M148 40C154 30 164 26 172 28C170 36 162 42 148 40Z" />
                            <path d="M148 70C154 80 164 84 172 82C170 74 162 68 148 70Z" />
                        </g>
                        <g class="kb-leaf" style="--i:4">
                            <circle cx="99" cy="38" r="2.4" />
                            <circle cx="99" cy="72" r="2.4" />
                            <circle cx="62" cy="55" r="2.2" />
                            <circle cx="246" cy="33" r="2.2" />
                            <circle cx="246" cy="77" r="2.2" />
                            <circle cx="186" cy="55" r="2.6" />
                            <circle cx="252" cy="55" r="2.6" />
                            <circle cx="219" cy="55" r="4" />
                        </g>
                    </g>
                </g>

                {{-- ============ CENTRE FLOWER ============ --}}
                <g id="{{ $u }}-ctr">
                    <g stroke="none">
                        {{-- side petals --}}
                        <g class="kb-petal" style="--i:5">
                            <path d="M300 55C286 46 274 46 264 55C274 64 286 64 300 55Z" />
                            <path d="M300 55C314 46 326 46 336 55C326 64 314 64 300 55Z" />
                        </g>
                        {{-- top / bottom petals --}}
                        <g class="kb-petal" style="--i:6">
                            <path d="M300 55C291 44 291 32 300 22C309 32 309 44 300 55Z" />
                            <path d="M300 55C291 66 291 78 300 88C309 78 309 66 300 55Z" />
                        </g>
                        {{-- finials --}}
                        <g class="kb-petal" style="--i:7">
                            <path d="M300 22C296 14 296 8 300 3C304 8 304 14 300 22Z" />
                            <path d="M300 20C292 18 286 12 286 6C294 8 299 13 300 20Z" />
                            <path d="M300 20C308 18 314 12 314 6C306 8 301 13 300 20Z" />
                            <path d="M300 88C296 96 296 102 300 107C304 102 304 96 300 88Z" />
                            <path d="M300 90C292 92 286 98 286 104C294 102 299 97 300 90Z" />
                            <path d="M300 90C308 92 314 98 314 104C306 102 301 97 300 90Z" />
                        </g>
                    </g>
                    {{-- jewel --}}
                    <g class="gdo-pulse">
                        <g class="kb-leaf" style="--i:8">
                            <circle cx="300" cy="55" r="5.5" stroke="none" fill="#2b1046" />
                            <circle cx="300" cy="55" r="3.2" stroke="none" />
                            <circle cx="300" cy="55" r="8.5" fill="none" stroke-width="1" />
                        </g>
                    </g>
                </g>

                {{-- whole ornament --}}
                <g id="{{ $u }}-orn">
                    <use href="#{{ $u }}-half" />
                    <use href="#{{ $u }}-half" transform="translate(600 0) scale(-1 1)" />
                    <use href="#{{ $u }}-ctr" />
                </g>
            </defs>

            {{-- emboss shadow --}}
            <use href="#{{ $u }}-orn" transform="translate(.8 1.4)" fill="#3a1f05" stroke="#3a1f05"
                opacity=".55" />
            {{-- gold --}}
            <use href="#{{ $u }}-orn" fill="url(#{{ $u }}-base)"
                stroke="url(#{{ $u }}-base)" />
            {{-- light sweep --}}
            <use class="gdo-shine" href="#{{ $u }}-orn" fill="url(#{{ $u }}-shine)"
                stroke="url(#{{ $u }}-shine)" />

            {{-- sparkles --}}
            <g transform="translate(300 3)">
                <path class="gdo-spark" style="--d:0s" d="M0-6L1.3-1.3L6 0L1.3 1.3L0 6L-1.3 1.3L-6 0L-1.3-1.3Z"
                    fill="#fff1b8" />
            </g>
            <g transform="translate(300 107)">
                <path class="gdo-spark" style="--d:1.4s" d="M0-6L1.3-1.3L6 0L1.3 1.3L0 6L-1.3 1.3L-6 0L-1.3-1.3Z"
                    fill="#fff1b8" />
            </g>
            <g transform="translate(176 30)">
                <path class="gdo-spark" style="--d:.7s" d="M0-5L1.1-1.1L5 0L1.1 1.1L0 5L-1.1 1.1L-5 0L-1.1-1.1Z"
                    fill="#fff1b8" />
            </g>
            <g transform="translate(424 80)">
                <path class="gdo-spark" style="--d:2.1s" d="M0-5L1.1-1.1L5 0L1.1 1.1L0 5L-1.1 1.1L-5 0L-1.1-1.1Z"
                    fill="#fff1b8" />
            </g>
            <g transform="translate(40 40)">
                <path class="gdo-spark" style="--d:1.9s" d="M0-4L.9-.9L4 0L.9 .9L0 4L-.9 .9L-4 0L-.9-.9Z"
                    fill="#fff1b8" />
            </g>
            <g transform="translate(560 70)">
                <path class="gdo-spark" style="--d:.3s" d="M0-4L.9-.9L4 0L.9 .9L0 4L-.9 .9L-4 0L-.9-.9Z"
                    fill="#fff1b8" />
            </g>
        </svg>
    </div>
@endif
