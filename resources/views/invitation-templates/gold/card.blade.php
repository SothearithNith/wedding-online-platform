 
<section id="hero" class="g-hero" data-bg="{{ $heroBg }}" data-eager>
    <div class="kc-fx" aria-hidden="true">
        <div class="royal-glow"></div>
        @for ($i = 0; $i < 12; $i++)
            <span class="royal-particle"
                style="left:{{ 8 + (($i * 17) % 85) }}%;top:{{ 8 + (($i * 23) % 85) }}%;width:{{ $i % 3 === 0 ? 4 : 2 }}px;height:{{ $i % 3 === 0 ? 4 : 2 }}px;animation-delay:{{ $i * 0.45 }}s"></span>
        @endfor
        <div class="kc-dust" style="position:absolute;inset:0">
            @for ($i = 0; $i < 14; $i++)
                <i
                    style="left:{{ ($i * 37 + 5) % 100 }}%;width:{{ 4 + ($i % 3) * 2 }}px;height:{{ 4 + ($i % 3) * 2 }}px;animation-duration:{{ 10 + ($i % 6) * 2 }}s;animation-delay:{{ ($i % 8) * 1.4 }}s;--dx:{{ (($i % 5) - 2) * 26 }}px"></i>
            @endfor
        </div>
    </div>

    <div class="kc-frame">
        <div class="kc-shape" aria-hidden="true"></div>
        <div class="kc-rays" aria-hidden="true"></div>
        <div class="kc-sun" aria-hidden="true"></div>

        {{-- rotating mandala --}}
        <svg aria-hidden="true" viewBox="-100 -100 200 200" class="kc-mandala">
            <g fill="none" stroke="{{ $GOLD }}" stroke-width="0.8">
                @for ($i = 0; $i < 16; $i++)
                    <path d="M0 -14 C12 -40 12 -70 0 -92 C-12 -70 -12 -40 0 -14 Z"
                        transform="rotate({{ $i * 22.5 }})" />
                @endfor
                @for ($i = 0; $i < 8; $i++)
                    <path d="M0 -10 C7 -26 7 -44 0 -58 C-7 -44 -7 -26 0 -10 Z"
                        transform="rotate({{ $i * 45 + 22.5 }})" />
                @endfor
                <circle r="96" />
                <circle r="8" />
            </g>
        </svg>

        <div class="royal-enter" style="--d:50ms;position:relative;z-index:10;width:100%;margin:-8px 0 4px">
            {!! $kbach('kc-kb-top', true) !!}
        </div>
        <div class="royal-enter" style="--d:150ms;position:relative;z-index:10">
            <img src="{{ asset('assets/images/gold/angkor.png') }}" alt="" width="320" height="134"
                class="kc-angkor" onerror="this.style.display='none'">
            <div class="kc-angkor-base" aria-hidden="true"></div>
        </div>
        <div class="royal-enter" style="--d:300ms;position:relative;z-index:10;margin-top:4px">
            <div class="royal-ornament-glow">
                <div class="chakra-band" style="width:150px"></div>
            </div>
        </div>

        <div class="royal-enter"
            style="--d:450ms;position:relative;z-index:10;margin-top:8px;display:flex;align-items:center;justify-content:center;gap:12px">
            <span class="royal-shimmer" style="font-size:14px">❧</span>
            <span class="kc-moul" style="font-size:11px;letter-spacing:.15em;color:#e3bd5a">{{ $K['wedding'] }}</span>
            <span class="royal-shimmer" style="font-size:14px">❧</span>
        </div>

        <div class="royal-enter"
            style="--d:650ms;position:relative;z-index:10;margin-top:8px;display:flex;width:100%;max-width:310px;flex-direction:column;align-items:center">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                <span class="hl-rule" style="width:40px"></span>
                <span class="royal-diamond" style="font-size:18px;color:#e3bd5a">✥</span>
                <span class="hl-rule" style="width:40px"></span>
            </div>
            <img src="{{ asset('assets/images/gold/gold-wedding.png') }}" alt="" width="500" height="180"
                class="royal-logo" style="display:block;width:100%;max-width:250px;height:auto;object-fit:contain"
                onerror="this.style.display='none'">
            <div class="royal-enter"
                style="--d:950ms;margin-top:12px;display:flex;flex-direction:column;align-items:center">
                <p class="kc-moul" style="font-size:9px;letter-spacing:.15em;color:#e3bd5a">{{ $K['celebrate'] }}</p>
                <p style="margin-top:4px;font-size:12px;line-height:1.6;color:rgba(251,238,193,.8)">{{ $K['invite'] }}
                </p>
            </div>
        </div>

        {{-- parents --}}
        <div class="royal-enter"
            style="--d:1150ms;position:relative;z-index:10;margin-top:16px;display:grid;width:100%;grid-template-columns:1fr 1fr;gap:12px">
            @foreach ([[$K['parentsGroom'], $groom['parents']], [$K['parentsBride'], $bride['parents']]] as [$lab, $par])
                <div style="min-width:0;padding:0 4px">
                    <p class="hl-label">{{ $lab }}</p>
                    <div class="hl-rule" style="width:32px;margin:4px auto 0"></div>
                    <div style="margin-top:8px;display:flex;flex-direction:column;align-items:center;gap:6px">
                        @foreach ($parents($par) as $n)
                            <p class="hl-parent">{{ $n }}</p>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rv" data-v="unfold"
            style="position:relative;z-index:10;display:flex;align-items:center;justify-content:center;gap:12px;margin:12px 0">
            <span class="hl-rule" style="width:40px"></span>
            <span class="royal-shimmer" style="font-size:18px;color:#e3bd5a">❖</span>
            <span class="hl-rule" style="width:40px"></span>
        </div>

        <div style="position:relative;z-index:10;width:100%">
            <p class="rv" data-v="blur"
                style="max-width:290px;margin:0 auto;font-size:13px;line-height:1.7;color:#fbeec1">
                {{ $w['intro'] }}</p>
        </div>

        {{-- emblem --}}
        <div class="rv" data-v="zoom" data-delay="150" style="position:relative;z-index:10;margin-top:8px">
            <div class="royal-emblem-glow">
                <div style="position:relative;margin:0 auto;height:112px;width:96px;color:#fbeec1">
                    <svg viewBox="0 0 100 120" style="position:absolute;inset:0;width:100%;height:100%" fill="none"
                        stroke="currentColor" aria-hidden="true">
                        <path d="M50 4C60 24 90 36 90 70C90 100 70 116 50 116C30 116 10 100 10 70C10 36 40 24 50 4Z"
                            stroke-width="2.5" />
                        <path d="M50 14C58 30 80 40 80 70C80 94 66 106 50 106C34 106 20 94 20 70C20 40 42 30 50 14Z"
                            stroke-width="1" opacity=".7" />
                    </svg>
                    <svg viewBox="-60 -60 120 90"
                        style="position:absolute;left:0;right:0;bottom:20px;margin:0 auto;height:48px;width:64px"
                        fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        @foreach ([-62, -34, 0, 34, 62] as $rot)
                            <path d="M0 20 C-16 4 -14 -24 0 -46 C14 -24 16 4 0 20 Z"
                                transform="rotate({{ $rot }} 0 20)" />
                        @endforeach
                    </svg>
                </div>
            </div>
        </div>

        @if (!empty($w['quote']))
            <div style="position:relative;z-index:10;margin-top:8px">
                <p class="rv royal-quote" data-v="blur" data-delay="250"
                    style="max-width:280px;margin:0 auto;font-size:12px;line-height:1.7">“{{ $w['quote'] }}”</p>
            </div>
        @endif

        {{-- names --}}
        <div class="royal-enter"
            style="--d:1550ms;position:relative;z-index:10;margin-top:16px;display:grid;width:100%;grid-template-columns:1fr auto 1fr;align-items:center;gap:8px">
            <div style="min-width:0">
                <p class="hl-label">{{ $K['groom'] }}</p>
                <p class="kc-name kc-moul" style="margin-top:6px">{{ $groom['name'] }}</p>
            </div>
            <svg aria-hidden="true" viewBox="-50 -50 100 100" width="44" height="44" class="kc-knot">
                @for ($i = 0; $i < 8; $i++)
                    <path d="M0 -6 C6 -18 6 -32 0 -44 C-6 -32 -6 -18 0 -6 Z" fill="none"
                        stroke="{{ $GOLD }}" stroke-width="1.8" transform="rotate({{ $i * 45 }})" />
                @endfor
                @for ($i = 0; $i < 4; $i++)
                    <path d="M0 -5 C4 -12 4 -20 0 -26 C-4 -20 -4 -12 0 -5 Z" fill="{{ $GOLD }}"
                        opacity=".7" transform="rotate({{ $i * 90 + 22.5 }})" />
                @endfor
                <circle r="4.5" fill="{{ $GOLD }}" />
            </svg>
            <div style="min-width:0">
                <p class="hl-label">{{ $K['bride'] }}</p>
                <p class="kc-name kc-moul" style="margin-top:6px">{{ $bride['name'] }}</p>
            </div>
        </div>

        <div class="royal-enter" style="--d:1800ms;position:relative;z-index:10;margin-top:16px">
            <p class="kc-date js-date" data-iso="{{ $w['date'] }}">{{ $w['dateText'] }}</p>
            <div class="royal-ornament-glow" style="margin-top:8px">
                <div class="chakra-band" style="width:140px"></div>
            </div>
        </div>

        <div class="royal-enter" style="--d:2050ms;position:relative;z-index:10;margin-top:8px;width:100%">
            <p class="royal-bottom-ornament" style="font-size:14px;letter-spacing:.4em">❧ ✥ ❧</p>
            {!! $kbach('kc-kb-bottom') !!}
        </div>
    </div>

    <div class="kc-scroll" aria-hidden="true">
        <span>{{ $K['scroll'] }}</span>
        {!! $ic('down', 18) !!}
    </div>
</section>
