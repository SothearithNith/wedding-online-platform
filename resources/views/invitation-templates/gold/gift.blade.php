@if (!empty($gift['accountNumber']))
    <section class="g-sec g-photo" id="gift" style="padding:112px 20px;text-align:center">
        <div class="g-bgimg" data-bg="{{ $bg('gift') }}"></div>
        <div class="g-ov" style="{{ $overlay(0.08) }}"></div>
        <div class="petals" aria-hidden="true">
            @for ($i = 0; $i < 12; $i++)
                <i
                    style="left:{{ ($i * 43) % 100 }}%;animation-duration:{{ 10 + ($i % 5) * 2.5 }}s;animation-delay:{{ ($i % 7) * 1.6 }}s;--dx:{{ (($i % 5) - 2) * 36 }}px;--rot:{{ ($i % 2 ? 1 : -1) * (240 + $i * 20) }}deg"></i>
            @endfor
        </div>

        <div class="g-arch-glass">
            <div class="gloss"></div>
            {!! $title($K['gift']) !!}
            <p class="kf-sub rv ts" style="color:rgba(255,255,255,.9);opacity:1">{{ $K['giftSub'] }}</p>

            <div class="rv" data-delay="150">
                <div class="gold-card float">
                    <div class="gold-card-in">
                        {!! $corner('tl', 'gcn') !!}{!! $corner('tr', 'gcn') !!}{!! $corner('bl', 'gcn') !!}{!! $corner('br', 'gcn') !!}
                        <div class="kf-qr"><img src="{{ $giftQr }}" alt="QR Code"
                                onerror="this.closest('.kf-qr').style.display='none'"></div>
                        <p class="kf-scan title-font">{{ $K['scan'] }}</p>
                        <p class="kf-bank">{{ $gift['bank'] ?? '' }}</p>

                        <p class="kf-label title-font">{{ $K['accName'] }}</p>
                        <p class="kf-name">{{ $gift['accountName'] ?? '' }}</p>

                        <div class="kf-divider" aria-hidden="true"><span></span><b></b><span></span></div>

                        <p class="kf-label title-font">{{ $K['accNo'] }}</p>
                        <p class="kf-num tnum" id="acc-num">{{ $gift['accountNumber'] }}</p>

                        <button type="button" class="kf-btn title-font" id="copy-btn">
                            <span id="copy-ic">{!! $ic('copy', 16) !!}</span>
                            <span id="copy-tx">{{ $K['copy'] }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <style>
            /* Shared glass look */
            #gift .g-arch-glass,
            #gift .gold-card,
            #gift .gold-card-in {
                background: rgba(255, 255, 255, 0.08) !important;
                background-image: none !important;
                -webkit-backdrop-filter: blur(18px) saturate(140%);
                backdrop-filter: blur(18px) saturate(140%);
                border: 1px solid rgba(255, 255, 255, 0.28);
                box-shadow:
                    0 8px 32px rgba(0, 0, 0, 0.18),
                    inset 0 1px 0 rgba(255, 255, 255, 0.35);
            }

            /* Outer arch container */
            #gift .g-arch-glass {
                background: rgba(255, 255, 255, 0.06) !important;
            }

            /* Gold card: the outer wrapper stays clear, only the inner panel blurs,
   so the blur isn't applied twice */
            #gift .gold-card {
                background: transparent !important;
                -webkit-backdrop-filter: none;
                backdrop-filter: none;
                box-shadow: none;
                border: 0;
            }

            /* Remove any gradient or gloss fill inside the card */
            #gift .gold-card::before,
            #gift .gold-card::after {
                background: none !important;
            }

            /* Text stays readable on glass */
            #gift .kf-bank,
            #gift .kf-name,
            #gift .kf-num,
            #gift .kf-scan,
            #gift .kf-label {
                color: #fff;
                text-shadow: 0 1px 6px rgba(0, 0, 0, 0.35);
            }

            /* Divider and copy button */
            #gift .kf-divider span {
                background: rgba(255, 255, 255, 0.4) !important;
            }

            #gift .kf-btn {
                background: rgba(255, 255, 255, 0.12) !important;
                border: 1px solid rgba(255, 255, 255, 0.4);
                color: #fff;
                -webkit-backdrop-filter: blur(10px);
                backdrop-filter: blur(10px);
            }

            #gift .kf-btn:hover {
                background: rgba(255, 255, 255, 0.22) !important;
            }

            /* QR code: glass frame, but keep a light tile behind the image so it still scans */
            #gift .kf-qr {
                background: rgba(255, 255, 255, 0.12) !important;
                border: 1px solid rgba(255, 255, 255, 0.35);
                -webkit-backdrop-filter: blur(10px);
                backdrop-filter: blur(10px);
                padding: 10px;
                border-radius: 16px;
            }

            #gift .kf-qr img {
                background: #fff;
                border-radius: 10px;
                padding: 6px;
            }
        </style>
    </section>
@endif
