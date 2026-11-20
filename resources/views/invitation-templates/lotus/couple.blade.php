<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="pf-gold" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#fdf7e8" />
            <stop offset=".35" stop-color="#e5c162" />
            <stop offset=".7" stop-color="#a97d2f" />
            <stop offset="1" stop-color="#fceebc" />
        </linearGradient>

        <path id="pf-flame"
            d="M0 0C-10 -12 -4 -22 -8 -32C-4 -30 0 -34 -2 -44C4 -38 6 -44 6 -58C14 -44 12 -28 6 -18C10 -20 12 -14 8 -8C6 -4 3 -2 0 0Z" />

        <g id="pf-side" fill="url(#pf-gold)" stroke="#4b1a6b" stroke-width=".7" stroke-linejoin="round">
            <use href="#pf-flame" transform="translate(70 150) rotate(-14) scale(1.2)" />
            <use href="#pf-flame" transform="translate(67 198) rotate(-6) scale(.9)" />
            <use href="#pf-flame" transform="translate(88 252) rotate(-76) scale(1.2)" />
            <use href="#pf-flame" transform="translate(104 276) rotate(-100) scale(.95)" />
            <use href="#pf-flame" transform="translate(128 298) rotate(-150) scale(.8)" />
        </g>

        <symbol id="pf-art" viewBox="0 0 340 340">
            <use href="#pf-side" />
            <use href="#pf-side" transform="translate(340 0) scale(-1 1)" />

            <g fill="url(#pf-gold)" stroke="#4b1a6b" stroke-width=".8" stroke-linejoin="round">
                <path d="M170 4C181 17 189 23 185 38L155 38C151 23 159 17 170 4Z" />
                <path d="M155 38C147 29 138 29 131 35C140 36 147 40 152 46Z" />
                <path d="M185 38C193 29 202 29 209 35C200 36 193 40 188 46Z" />
            </g>
            <path d="M170 14C175 22 177 27 175 34L165 34C163 27 165 22 170 14Z" fill="#fdf7e8" opacity=".7" />

            <ellipse cx="170" cy="172" rx="110" ry="138" fill="none" stroke="#e5c162"
                stroke-width="1" opacity=".5" />
            <ellipse cx="170" cy="172" rx="104" ry="132" fill="none" stroke="url(#pf-gold)"
                stroke-width="8" />
            <ellipse cx="170" cy="172" rx="98.5" ry="126.5" fill="none" stroke="#fdf7e8"
                stroke-width="1.4" opacity=".85" />
            <ellipse cx="170" cy="172" rx="108" ry="136" fill="none" stroke="#fdf7e8"
                stroke-width="2" stroke-dasharray=".1 7" stroke-linecap="round" opacity=".55" />
        </symbol>
    </defs>
</svg>

<section class="g-sec g-photo cpl" id="couple">
    <div class="g-bgimg" data-bg="{{ $bg('intro') }}"></div>
    <div class="g-ov" style="{{ $overlay(0.6) }}"></div>

    {{-- gold vine border (drawn by .cpl::after) --}}

    <div class="cpl-in" id="cp">

        <div class="cpl-orn rv" data-v="unfold" aria-hidden="true">
            <span></span><i></i><i class="big"></i><i></i><span></span>
        </div>

        @foreach ([[$K['groom'], $groom, 'left', 0], [$K['bride'], $bride, 'right', 200]] as [$lab, $p, $side, $delay])
            @php $photo = $img($p['photo'] ?? ''); @endphp

            <article class="cpl-card cpl-{{ $side }}">

                {{-- portrait in gold kbach oval frame --}}
                <div class="cpl-photo rv" data-v="{{ $side }}" style="--d:{{ $delay }}ms">
                    <div class="cpl-art">
                        <div class="cpl-halo" aria-hidden="true"></div>

                        <div class="cpl-oval">
                            @if ($photo)
                                <img src="{{ $photo }}" alt="{{ $p['name'] }}" loading="lazy"
                                    decoding="async">
                            @else
                                <span class="cpl-fallback">❀</span>
                            @endif
                        </div>

                        <svg class="cpl-frame-svg" viewBox="0 0 340 340" aria-hidden="true">
                            <use href="#pf-art" width="340" height="340" />
                        </svg>
                    </div>
                </div>

                {{-- nameplate --}}
                <div class="cpl-info rv" data-v="{{ $side === 'left' ? 'right' : 'left' }}"
                    style="--d:{{ $delay + 250 }}ms">
                    <span class="cpl-role font-khmer"><i></i>{{ $lab }}<i></i></span>
                    <h3 class="cpl-name title-font">{{ $p['name'] }}</h3>

                    <div class="cpl-div" aria-hidden="true">
                        <span></span><i></i><span></span>
                    </div>

                    {{-- parents of THIS person only --}}
                    <div class="cpl-parents font-khmer">
                        @if (!empty($p['dad']))
                            <div class="cpl-par">
                                <span class="cpl-par-role">{{ trans_json('Mr.') }}</span>
                                <span class="cpl-par-name title-font">{{ $p['dad'] }}</span>
                            </div>
                        @endif

                        @if (!empty($p['dad']) && !empty($p['mom']))
                            <i class="cpl-par-dia" aria-hidden="true"></i>
                        @endif

                        @if (!empty($p['mom']))
                            <div class="cpl-par">
                                <span class="cpl-par-role">{{ trans_json('Ms.') }}</span>
                                <span class="cpl-par-name title-font">{{ $p['mom'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </article>

            {{-- heart medallion between the two --}}
            @if ($loop->first)
                <div class="cpl-knot rv" data-v="zoom" style="--d:350ms" aria-hidden="true">
                    <span class="kx-ringwave"></span>
                    <span class="kx-ringwave b"></span>
                    <svg viewBox="0 0 64 64" width="100%" height="100%">
                        <defs>
                            <linearGradient id="cplh" x1="0" y1="0" x2="1"
                                y2="1">
                                <stop offset="0" stop-color="#ff9db0" />
                                <stop offset=".55" stop-color="#e0405f" />
                                <stop offset="1" stop-color="#9c1233" />
                            </linearGradient>
                        </defs>
                        <g class="kx-heart">
                            <path transform="translate(14 16) scale(1.1)"
                                d="M23.6 0c-3.4 0-5.5 1.9-7.6 4.2C13.9 1.9 11.8 0 8.4 0 3.8 0 0 3.8 0 8.4c0 9.4 9.5 11.9 16 21.2 6.1-9.3 16-12.1 16-21.2C32 3.8 28.2 0 23.6 0z"
                                fill="url(#cplh)" stroke="#fceebc" stroke-width="1" />
                        </g>
                    </svg>
                </div>
            @endif
        @endforeach

        <div class="cpl-orn cpl-orn-end rv" data-v="unfold" aria-hidden="true">
            <span></span><i></i><i class="big"></i><i></i><span></span>
        </div>
    </div>
</section>
