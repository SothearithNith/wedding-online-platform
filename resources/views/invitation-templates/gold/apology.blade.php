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
                <div class="gold-card ap-c">
                    <div class="gold-card-in" style="padding-bottom:2rem;color:#3b2503;text-shadow:none">
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
        /* Shared glass look (apology) */
        #apology .g-arch-glass,
        #apology .gold-card-in {
            background: rgba(255, 255, 255, 0.08) !important;
            background-image: none !important;
            -webkit-backdrop-filter: blur(18px) saturate(140%);
            backdrop-filter: blur(18px) saturate(140%);
            border: 1px solid rgba(255, 255, 255, 0.28);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.18),
                inset 0 1px 0 rgba(255, 255, 255, 0.35);
        }

        #apology .g-arch-glass {
            background: rgba(255, 255, 255, 0.06) !important;
        }

        /* Outer wrapper stays clear so the blur isn't applied twice */
        #apology .gold-card,
        #apology .ap-card-w {
            background: transparent !important;
            -webkit-backdrop-filter: none;
            backdrop-filter: none;
            box-shadow: none;
            border: 0;
        }

        #apology .gold-card::before,
        #apology .gold-card::after {
            background: none !important;
        }

        /* Text readable on glass */
        #apology .ap-line,
        #apology .gold-card-in {
            color: #fff !important;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.35) !important;
        }

        /* Dividers */
        #apology .ap-divider span {
            background: rgba(255, 255, 255, 0.4) !important;
        }
    </style>
</section>
