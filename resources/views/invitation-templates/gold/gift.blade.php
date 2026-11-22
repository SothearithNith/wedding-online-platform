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

                        {{-- QR is hidden; this button opens it in a popup --}}
                        @if (!empty($giftQr))
                            <button type="button" class="kf-btn kf-qr-open title-font" id="qr-open"
                                aria-haspopup="dialog" aria-controls="qr-modal">
                                {!! $ic('qr', 16) !!}
                                <span>{{ $K['openQr'] ?? 'បើក QR Code' }}</span>
                            </button>
                        @endif

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

        {{-- QR popup --}}
        @if (!empty($giftQr))
            <div class="qr-modal" id="qr-modal" role="dialog" aria-modal="true" aria-label="QR Code" hidden>
                <div class="qr-backdrop" data-close></div>
                <div class="qr-box">
                    <button type="button" class="qr-close" data-close aria-label="Close">&times;</button>
                    <div class="qr-img">
                        <img src="{{ $giftQr }}" alt="QR Code"
                            onerror="document.getElementById('qr-open') && (document.getElementById('qr-open').style.display='none')">
                    </div>
                    <p class="qr-scan title-font">{{ $K['scan'] }}</p>
                    <p class="qr-bank">{{ $gift['bank'] ?? '' }}</p>
                    <p class="qr-name">{{ $gift['accountName'] ?? '' }}</p>
                </div>
            </div>
        @endif

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

            #gift .g-arch-glass {
                background: rgba(255, 255, 255, 0.06) !important;
            }

            #gift .gold-card {
                background: transparent !important;
                -webkit-backdrop-filter: none;
                backdrop-filter: none;
                box-shadow: none;
                border: 0;
            }

            #gift .gold-card::before,
            #gift .gold-card::after {
                background: none !important;
            }

            #gift .kf-bank,
            #gift .kf-name,
            #gift .kf-num,
            #gift .kf-scan,
            #gift .kf-label {
                color: #fff;
                text-shadow: 0 1px 6px rgba(0, 0, 0, 0.35);
            }

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

            /* "Open QR" button sits above the bank name */
            #gift .kf-qr-open {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin: 4px auto 14px;
            }

            /* ===== QR popup (moved to <body> by script) ===== */
            .qr-modal[hidden] {
                display: none;
            }

            .qr-modal {
                position: fixed;
                inset: 0;
                z-index: 100;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                animation: qrFade .25s ease both;
            }

            .qr-backdrop {
                position: absolute;
                inset: 0;
                background: rgba(10, 4, 18, .72);
                -webkit-backdrop-filter: blur(6px);
                backdrop-filter: blur(6px);
            }

            .qr-box {
                position: relative;
                width: min(88vw, 340px);
                padding: 26px 22px 22px;
                text-align: center;
                border-radius: 22px;
                background: rgba(31, 10, 51, .92);
                border: 1px solid rgba(227, 189, 90, .5);
                box-shadow: 0 18px 60px rgba(0, 0, 0, .55);
                animation: qrPop .35s cubic-bezier(.2, .8, .2, 1) both;
            }

            .qr-close {
                position: absolute;
                top: 8px;
                right: 10px;
                width: 34px;
                height: 34px;
                border: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, .1);
                color: #fbeec1;
                font-size: 22px;
                line-height: 1;
                cursor: pointer;
            }

            .qr-close:hover {
                background: rgba(255, 255, 255, .2);
            }

            .qr-img {
                background: #fff;
                border-radius: 14px;
                padding: 12px;
                margin: 8px auto 14px;
                width: min(70vw, 240px);
            }

            .qr-img img {
                display: block;
                width: 100%;
                height: auto;
            }

            .qr-scan {
                color: #e3bd5a;
                font-size: 13px;
            }

            .qr-bank {
                margin-top: 6px;
                color: #fbeec1;
                font-weight: 700;
            }

            .qr-name {
                margin-top: 2px;
                color: rgba(251, 238, 193, .85);
                font-size: 13px;
            }

            @keyframes qrFade {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes qrPop {
                from {
                    opacity: 0;
                    transform: scale(.92) translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: none;
                }
            }
        </style>

        <script>
            (function() {
                var openBtn = document.getElementById('qr-open');
                var modal = document.getElementById('qr-modal');
                if (!openBtn || !modal) return;

                // Move to <body> so the glass cards' backdrop-filter can't clip the fixed popup
                document.body.appendChild(modal);

                function open() {
                    modal.hidden = false;
                    document.documentElement.style.overflow = 'hidden';
                    var c = modal.querySelector('.qr-close');
                    if (c) c.focus();
                }

                function close() {
                    modal.hidden = true;
                    document.documentElement.style.overflow = '';
                    openBtn.focus();
                }

                openBtn.addEventListener('click', open);
                modal.addEventListener('click', function(e) {
                    if (e.target.hasAttribute('data-close')) close();
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !modal.hidden) close();
                });
            })();
        </script>
    </section>
@endif
