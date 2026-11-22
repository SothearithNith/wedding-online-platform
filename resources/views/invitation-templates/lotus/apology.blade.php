@php
    preg_match_all('/[^។]+។?/u', (string) $w['apology'], $m);
    $lines = array_values(array_filter(array_map('trim', $m[0] ?? [])));
@endphp
<section class="g-sec g-photo" id="apology" style="padding:112px 20px;text-align:center">
    <div class="g-bgimg" data-bg="{{ $bg('apology') }}"></div>
    <div class="g-ov" style="{{ $overlay(0.08) }}"></div>

    <div class="petals" aria-hidden="true">
        @for ($i = 0; $i < 8; $i++)
            <i
                style="left:{{ ($i * 47 + 8) % 100 }}%;width:11px;height:15px;animation-duration:{{ 11 + ($i % 4) * 3 }}s;animation-delay:{{ ($i % 6) * 1.8 }}s;--dx:{{ (($i % 5) - 2) * 34 }}px;--rot:{{ ($i % 2 ? 1 : -1) * (220 + $i * 25) }}deg"></i>
        @endfor
    </div>

    <div class="g-arch-glass">
        <div class="gloss"></div>

        <div id="ap">
            {!! $title($K['apology']) !!}
            <div class="ap-card-w">
                {{-- glass card (shares .kf-glass with the gift section) --}}
                <div class="kf-glass ap-glass">
                    <div class="kf-glass-in">
                        <span class="kf-gloss" aria-hidden="true"></span>
                        {!! $corner('tl', 'gcn', 44) !!}{!! $corner('tr', 'gcn', 44) !!}{!! $corner('bl', 'gcn', 44) !!}{!! $corner('br', 'gcn', 44) !!}
                        <svg aria-hidden="true" viewBox="0 0 80 80" width="64" height="64" class="ap-bud">
                            <path d="M40 8 C50 22 52 38 40 56 C28 38 30 22 40 8 Z" fill="{{ $GOLD }}"
                                opacity=".9" />
                            <path d="M40 56 C26 52 14 40 12 26 C26 28 36 38 40 56 Z" fill="none"
                                stroke="{{ $GOLD }}" stroke-width="1.5" />
                            <path d="M40 56 C54 52 66 40 68 26 C54 28 44 38 40 56 Z" fill="none"
                                stroke="{{ $GOLD }}" stroke-width="1.5" />
                            <path d="M40 56 C20 58 8 52 2 42 C16 40 30 46 40 56 Z" fill="none"
                                stroke="{{ $GOLD }}" stroke-width="1.2" opacity=".7" />
                            <path d="M40 56 C60 58 72 52 78 42 C64 40 50 46 40 56 Z" fill="none"
                                stroke="{{ $GOLD }}" stroke-width="1.2" opacity=".7" />
                            <path d="M32 66 H48 M36 72 H44" stroke="{{ $GOLD }}" stroke-width="1.4"
                                stroke-linecap="round" />
                        </svg>
                        <div class="ap-divider" aria-hidden="true"><span></span><b></b><span></span></div>
                        @foreach ($lines as $i => $t)
                            <p class="ap-line" style="--i:{{ $i }}">{{ $t }}</p>
                        @endforeach
                        <div class="ap-divider" aria-hidden="true"><span></span><b></b><span></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .ap-glass {
            margin-top: 0;
        }

        .ap-glass .kf-glass-in {
            padding: 2.2rem 1.6rem 2rem;
        }

        .ap-glass .ap-line {
            color: #fbf6ea;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.6);
        }

        .ap-glass .ap-bud {
            margin-top: 0.2rem;
        }
    </style>
</section>
