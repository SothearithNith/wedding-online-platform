<section class="g-sec g-photo" id="couple">
    <div class="g-bgimg" data-bg="{{ $bg('intro') }}"></div>
    <div class="g-ov" style="{{ $overlay(0.62) }}"></div>

    {{-- gold frame, full screen --}}
    <img src="{{ asset('assets/images/gold/frame_gold.png') }}" alt="" aria-hidden="true" class="cp-frame">

    {{-- shared SVG definitions (written once) --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <defs>
            <path id="cpShape"
                d="M150 8 C158 44 200 52 214 92 C224 122 268 130 270 172 L270 394 Q270 410 254 410 L46 410 Q30 410 30 394 L30 172 C32 130 76 122 86 92 C100 52 142 44 150 8Z" />
            <clipPath id="cpClip">
                <use href="#cpShape" transform="translate(150 210) scale(0.92) translate(-150 -210)" />
            </clipPath>
            <linearGradient id="cpGold" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#f6e3a0" />
                <stop offset=".5" stop-color="#a87c2e" />
                <stop offset="1" stop-color="#f0d58a" />
            </linearGradient>
            <linearGradient id="cpHeart" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#ff9db0" />
                <stop offset=".55" stop-color="#e0405f" />
                <stop offset="1" stop-color="#9c1233" />
            </linearGradient>
            <linearGradient id="cpShade" x1="0" y1="0" x2="0" y2="1">
                <stop offset=".7" stop-color="#000" stop-opacity="0" />
                <stop offset="1" stop-color="#000" stop-opacity=".4" />
            </linearGradient>

            {{-- Khmer lotus (5 petals), reused under each photo --}}
            <symbol id="cpLotus" viewBox="-60 -52 120 76">
                <g fill="rgba(246,227,160,.12)" stroke="url(#cpGold)" stroke-width="2" stroke-linejoin="round">
                    @foreach ([-62, -34, 0, 34, 62] as $rot)
                        <path d="M0 20 C-16 4 -14 -24 0 -46 C14 -24 16 4 0 20Z"
                            transform="rotate({{ $rot }} 0 20)" />
                    @endforeach
                </g>
            </symbol>
        </defs>
    </svg>

    {{-- drifting gold petals --}}
    <div class="cp-fx" aria-hidden="true">
        @for ($i = 0; $i < 9; $i++)
            <i
                style="left:{{ ($i * 41 + 9) % 90 }}%;animation-duration:{{ 9 + ($i % 5) * 2 }}s;animation-delay:{{ $i * 1.1 }}s;--dx:{{ (($i % 5) - 2) * 22 }}px"></i>
        @endfor
    </div>

    <div class="cp-wrap">
        <div class="cp-stage">

            {{-- dotted gold curve from A to B --}}
            <svg class="cp-curve" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                <path d="M30 24 C52 24, 48 62, 70 62" fill="none" stroke="url(#cpGold)" stroke-width="2.2"
                    stroke-dasharray="0.1 7" stroke-linecap="round" vector-effect="non-scaling-stroke" />
            </svg>

            @foreach ([[$K['groom'], $groom, 0, 'a'], [$K['bride'], $bride, 450, 'b']] as [$label, $p, $delay, $pos])
                @php $photo = $img($p['photo'] ?? ''); @endphp

                <article class="cp-person cp-{{ $pos }}" style="--d:{{ $delay }}ms">
                    <div class="cp-card">
                        <div class="cp-glow" aria-hidden="true"></div>

                        <svg viewBox="0 -26 300 446" class="cp-photo" role="img" aria-label="{{ $p['name'] }}">
                            <use href="#cpShape" fill="#2a1c0d" />
                            <g clip-path="url(#cpClip)">
                                @if ($photo)
                                    <image href="{{ $photo }}" y="0" width="300" height="420"
                                        preserveAspectRatio="xMidYMin slice" />
                                @else
                                    <text x="150" y="260" text-anchor="middle" font-size="90" fill="#fff"
                                        opacity=".6">❀</text>
                                @endif
                                <rect width="300" height="420" fill="url(#cpShade)" />
                            </g>

                            {{-- double gold outline + pearl dots --}}
                            <use href="#cpShape" fill="none" stroke="url(#cpGold)" stroke-width="8"
                                stroke-linejoin="round" />
                            <use href="#cpShape" transform="translate(150 210) scale(.955) translate(-150 -210)"
                                fill="none" stroke="#fff1b8" stroke-width="4" stroke-dasharray="0.1 9"
                                stroke-linecap="round" />
                            <use href="#cpShape" transform="translate(150 210) scale(.915) translate(-150 -210)"
                                fill="none" stroke="#fff1b8" stroke-opacity=".8" stroke-width="1.4" />

                            {{-- temple-style finial --}}
                            <path
                                d="M150 -22 C155 -10 163 -6 162 4 C161 11 155 14 150 22 C145 14 139 11 138 4 C137 -6 145 -10 150 -22Z"
                                fill="url(#cpGold)" stroke="#7a5518" stroke-width="1" />
                        </svg>

                        {{-- lotus pedestal --}}
                        <svg class="cp-lotus" aria-hidden="true">
                            <use href="#cpLotus" />
                        </svg>
                    </div>

                    <div class="cp-text">
                        <p class="cp-label font-khmer">{{ $label }}</p>
                        <h3 class="cp-name kc-moul font-khmer title-font">{{ $p['name'] }}</h3>

                        <div class="cp-divider" aria-hidden="true">
                            <span></span><b>✥</b><span></span>
                        </div>

                        <p class="cp-parent font-khmer">
                            {{ trans_json('Mr.') }}&nbsp;<span
                                class="title-font">{{ implode(' ', $parents($p['dad'])) }}</span>
                        </p>
                        <p class="cp-parent font-khmer">
                            {{ trans_json('Ms.') }}&nbsp;<span
                                class="title-font">{{ implode(' ', $parents($p['mom'])) }}</span>
                        </p>
                    </div>
                </article>
            @endforeach

            {{-- heart badge with sunburst --}}
            <div class="cp-heart" aria-hidden="true">
                <span class="cp-rays"></span>
                <svg viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="54" fill="#1b1209" stroke="url(#cpGold)"
                        stroke-width="5" />
                    <circle cx="60" cy="60" r="44" fill="none" stroke="#fff1b8"
                        stroke-opacity=".6" stroke-width="2" stroke-dasharray="0.1 8" stroke-linecap="round" />
                    <g class="cp-beat">
                        <g transform="translate(34 36) scale(1.62)">
                            <path
                                d="M23.6 0c-3.4 0-5.5 1.9-7.6 4.2C13.9 1.9 11.8 0 8.4 0 3.8 0 0 3.8 0 8.4c0 9.4 9.5 11.9 16 21.2 6.1-9.3 16-12.1 16-21.2C32 3.8 28.2 0 23.6 0z"
                                fill="url(#cpHeart)" stroke="url(#cpGold)" stroke-width="1.1" />
                        </g>
                    </g>
                </svg>
            </div>

        </div>
    </div>
</section>
