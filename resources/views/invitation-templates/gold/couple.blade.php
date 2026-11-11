<section class="g-sec g-photo" id="couple" style="padding:96px 16px 80px">
    <div class="g-bgimg" data-bg="{{ $bg('intro') }}"></div>
    <div class="g-ov" style="{{ $overlay(0.55) }}"></div>
    {{-- {!! $edge('top', '#fbfaf6') !!} --}}

    <div class="cp-wrap">
        <div class="cp-in" id="cp">
            <div class="cp-halo" aria-hidden="true">
                <div></div>
            </div>

            <div class="cp-grid">
                @foreach ([[$K['groom'], $groom, 0, 'l', 'kx-float'], [$K['bride'], $bride, 500, 'r', 'kx-float-b']] as $idx => [$lab, $p, $delay, $slide, $floatCls])
                    @php
                        $uid = 'cf' . $idx;
                        $shape =
                            'M150 8 C158 44 200 52 214 92 C224 122 268 130 270 172 L270 394 Q270 410 254 410 L46 410 Q30 410 30 394 L30 172 C32 130 76 122 86 92 C100 52 142 44 150 8Z';
                        $photo = $img($p['photo'] ?? '');
                    @endphp
                    <div>
                        <div class="kx-slide kx-slide-{{ $slide }}" style="--dl:{{ $delay }}ms">
                            <div class="kx-fwrap">
                                <div class="kx-fglow" aria-hidden="true"></div>
                                <div class="{{ $floatCls }}">
                                    <svg viewBox="0 -24 300 444" class="kx-fsvg" role="img"
                                        aria-label="{{ $p['name'] }}">
                                        <defs>
                                            <clipPath id="{{ $uid }}c">
                                                <path d="{{ $shape }}"
                                                    transform="translate(150 210) scale(0.9) translate(-150 -210)" />
                                            </clipPath>
                                            <linearGradient id="{{ $uid }}g" x1="0" y1="0"
                                                x2="1" y2="1">
                                                <stop offset="0" stop-color="#f6e3a0" />
                                                <stop offset="0.5" stop-color="#a87c2e" />
                                                <stop offset="1" stop-color="#f0d58a" />
                                            </linearGradient>
                                            <linearGradient id="{{ $uid }}s" x1="0" y1="0"
                                                x2="0" y2="1">
                                                <stop offset="0.7" stop-color="#000" stop-opacity="0" />
                                                <stop offset="1" stop-color="#000" stop-opacity="0.45" />
                                            </linearGradient>
                                            <linearGradient id="{{ $uid }}w" x1="0" y1="0"
                                                x2="1" y2="0">
                                                <stop offset="0" stop-color="#fff" stop-opacity="0" />
                                                <stop offset="0.5" stop-color="#fff" stop-opacity="0.55" />
                                                <stop offset="1" stop-color="#fff" stop-opacity="0" />
                                            </linearGradient>
                                        </defs>

                                        <path d="{{ $shape }}" fill="#2a1c0d" />

                                        <g clip-path="url(#{{ $uid }}c)">
                                            @if ($photo)
                                                <image class="kx-photo" href="{{ $photo }}" x="0" y="0"
                                                    width="300" height="420"
                                                    preserveAspectRatio="xMidYMin slice" />
                                            @else
                                                <text x="150" y="260" text-anchor="middle" font-size="90" fill="#fff"
                                                    opacity="0.7">❀</text>
                                            @endif
                                            <rect width="300" height="420" fill="url(#{{ $uid }}s)" />
                                            <rect class="kx-sweep" x="0" y="-20" width="90" height="460"
                                                fill="url(#{{ $uid }}w)" />
                                        </g>

                                        <path class="kx-outline" pathLength="1000" d="{{ $shape }}"
                                            fill="none" stroke="url(#{{ $uid }}g)" stroke-width="8"
                                            stroke-linejoin="round" />
                                        <path d="{{ $shape }}"
                                            transform="translate(150 210) scale(0.955) translate(-150 -210)"
                                            fill="none" stroke="#fff1b8" stroke-width="4.5" stroke-dasharray="0.1 9"
                                            stroke-linecap="round" />
                                        <path class="kx-outline" pathLength="1000" d="{{ $shape }}"
                                            transform="translate(150 210) scale(0.915) translate(-150 -210)"
                                            fill="none" stroke="#fff1b8" stroke-opacity="0.85" stroke-width="1.5" />

                                        @foreach ([[30, 172], [270, 172], [86, 92], [214, 92]] as [$sx, $sy])
                                            <path
                                                d="M{{ $sx }} {{ $sy - 9 }} L{{ $sx + 7 }} {{ $sy }} L{{ $sx }} {{ $sy + 9 }} L{{ $sx - 7 }} {{ $sy }}Z"
                                                fill="#fff1b8" stroke="#a87c2e" stroke-width="1" />
                                        @endforeach

                                        <g class="kx-flame">
                                            <path
                                                d="M150 -20 C155 -8 163 -4 162 6 C161 13 155 16 150 24 C145 16 139 13 138 6 C137 -4 145 -8 150 -20Z"
                                                fill="url(#{{ $uid }}g)" stroke="#7a5518"
                                                stroke-width="1" />
                                        </g>
                                    </svg>
                                </div>
                            </div>

                            <div class="kx-text">
                                <p class="font-khmer" style="font-size:10px;letter-spacing:.3em;color:#f6e3a0">
                                    {{ $lab }}</p>
                                <h3 class="kx-name font-khmer">{{ $p['name'] }}</h3>
                                <div class="kx-divider" aria-hidden="true">
                                    <span class="kx-divline"
                                        style="background:linear-gradient(90deg,transparent,#f0d58a)"></span>
                                    <span class="kx-dia"></span>
                                    <span class="kx-divline"
                                        style="background:linear-gradient(270deg,transparent,#f0d58a)"></span>
                                </div>
                                <p class="font-khmer"
                                    style="max-width:10rem;margin:6px auto 0;font-size:11px;line-height:1.4;color:rgba(255,255,255,.85)">
                                    {{ implode(' ', $parents($p['parents'])) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- heart badge --}}
            <div class="kx-heartpos">
                <div class="kx-heart-wrap">
                    <span class="kx-ringwave" aria-hidden="true"></span>
                    <span class="kx-ringwave b" aria-hidden="true"></span>
                    <svg viewBox="0 0 120 120" style="position:relative;width:100%;height:100%" aria-hidden="true">
                        <defs>
                            <linearGradient id="hbh" x1="0" y1="0" x2="1"
                                y2="1">
                                <stop offset="0" stop-color="#ff9db0" />
                                <stop offset="0.55" stop-color="#e0405f" />
                                <stop offset="1" stop-color="#9c1233" />
                            </linearGradient>
                            <linearGradient id="hbg" x1="0" y1="0" x2="1"
                                y2="1">
                                <stop offset="0" stop-color="#f6e3a0" />
                                <stop offset="0.5" stop-color="#a87c2e" />
                                <stop offset="1" stop-color="#f0d58a" />
                            </linearGradient>
                            <filter id="hbs" x="-30%" y="-30%" width="160%" height="160%">
                                <feDropShadow dx="0" dy="4" stdDeviation="5" flood-color="#000"
                                    flood-opacity="0.6" />
                            </filter>
                        </defs>
                        <g filter="url(#hbs)">
                            <circle cx="60" cy="60" r="54" fill="#1b1209" stroke="url(#hbg)"
                                stroke-width="4" />
                        </g>
                        <circle class="kx-orbit" cx="60" cy="60" r="46" fill="none"
                            stroke="#fff1b8" stroke-width="3" stroke-dasharray="0.1 9" stroke-linecap="round" />
                        <circle cx="60" cy="60" r="39" fill="none" stroke="#d9b66f"
                            stroke-opacity="0.6" stroke-width="1" />
                        <g class="kx-heart">
                            <g transform="translate(34 36) scale(1.62)">
                                <path
                                    d="M23.6 0c-3.4 0-5.5 1.9-7.6 4.2C13.9 1.9 11.8 0 8.4 0 3.8 0 0 3.8 0 8.4c0 9.4 9.5 11.9 16 21.2 6.1-9.3 16-12.1 16-21.2C32 3.8 28.2 0 23.6 0z"
                                    fill="url(#hbh)" stroke="url(#hbg)" stroke-width="1.1" />
                                <path d="M8 6 C5 7 4 10 5 12" fill="none" stroke="#fff" stroke-opacity="0.7"
                                    stroke-width="1.4" stroke-linecap="round" />
                            </g>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="kx-hearts" aria-hidden="true">
                @for ($i = 0; $i < 10; $i++)
                    <span class="kx-love"
                        style="left:{{ ($i * 47 + 8) % 92 }}%;animation-delay:{{ $i * 0.8 }}s">♥</span>
                @endfor
            </div>
        </div>
    </div>

    {{-- {!! $edge('bottom', '#ffffff') !!} --}}
</section>
