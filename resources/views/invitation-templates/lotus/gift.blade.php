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
                <div class="kf-glass float">
                    <div class="kf-glass-in">
                        <span class="kf-gloss" aria-hidden="true"></span>
                        {!! $corner('tl', 'gcn') !!}{!! $corner('tr', 'gcn') !!}{!! $corner('bl', 'gcn') !!}{!! $corner('br', 'gcn') !!}

                        <p class="kf-bank">{{ $gift['bank'] ?? '' }}</p>

                        <div class="kf-divider" aria-hidden="true"><span></span><b></b><span></span></div>

                        <p class="kf-label">{{ $K['accName'] }}</p>
                        <p class="kf-name">{{ $gift['accountName'] ?? '' }}</p>

                        <p class="kf-label">{{ $K['accNo'] }}</p>
                        <p class="kf-num tnum" id="acc-num">{{ $gift['accountNumber'] }}</p>

                        <div class="kf-actions">
                            <button type="button" class="kf-btn kf-btn-solid" id="qr-open" aria-haspopup="dialog"
                                aria-controls="qr-modal">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7" rx="1" />
                                    <rect x="14" y="3" width="7" height="7" rx="1" />
                                    <rect x="3" y="14" width="7" height="7" rx="1" />
                                    <path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1" />
                                </svg>
                                <span class="title-font">{{ $K['scan'] }}</span>
                            </button>

                            <button type="button" class="kf-btn title-font" id="copy-btn">
                                <span id="copy-ic">{!! $ic('copy', 16) !!}</span>
                                <span id="copy-tx">{{ $K['copy'] }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
    <div class="kf-modal" id="qr-modal" role="dialog" aria-modal="true" aria-label="{{ $K['scan'] }}" hidden>
        <div class="kf-modal-bg" data-close></div>
        <div class="kf-modal-card">
            <button type="button" class="kf-modal-x" data-close
                aria-label="{{ $K['close'] }}">{!! $ic('x', 20) !!}</button>
            <p class="kf-modal-bank">{{ $gift['bank'] ?? '' }}</p>
            <div class="kf-modal-qr">
                <img src="{{ $giftQr }}" alt="QR Code"
                    onerror="document.getElementById('qr-open').style.display='none'">
            </div>
            <p class="kf-modal-name">{{ $gift['accountName'] ?? '' }}</p>
            <p class="kf-modal-scan">{{ $K['scan'] }}</p>
        </div>
    </div>
    <style>
        /* =====================================================================
   GIFT · glass card + QR popup
   Paste at the very bottom of lotus-khmer-royal.css.
   ===================================================================== */

        /* ---------- glass card ---------- */
        .kf-glass {
            position: relative;
            max-width: 24rem;
            margin: 32px auto 0;
            padding: 2px;
            border-radius: 4px;
            background: linear-gradient(135deg, rgba(246, 227, 160, 0.9), rgba(227, 189, 90, 0.25) 45%, rgba(246, 227, 160, 0.8));
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.4);
        }

        .kf-glass.float {
            animation: kf-float 7s ease-in-out infinite;
        }

        .kf-glass-in {
            position: relative;
            overflow: hidden;
            border-radius: 2px;
            padding: 2.4rem 1.5rem 1.8rem;
            color: #fbf6ea;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0.04) 45%, rgba(255, 255, 255, 0.08)),
                rgba(27, 10, 44, 0.5);
            -webkit-backdrop-filter: blur(10px) saturate(1.3);
            backdrop-filter: blur(10px) saturate(1.3);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.4),
                inset 0 0 0 5px rgba(27, 10, 44, 0.35),
                inset 0 0 0 6px rgba(227, 189, 90, 0.28);
        }

        /* gloss: bright cap on top + a slow diagonal sweep */
        .kf-gloss {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 38%;
            z-index: 0;
            pointer-events: none;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0.04) 70%, transparent);
        }

        .kf-glass-in::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(115deg, transparent 38%, rgba(255, 244, 205, 0.28) 50%, transparent 62%);
            transform: translateX(-130%);
            animation: kf-shine 6s ease-in-out infinite 1.2s;
        }

        .kf-glass-in>*:not(.kf-gloss):not(.g-corner) {
            position: relative;
            z-index: 1;
        }

        /* text on glass */
        .kf-glass .kf-bank {
            margin: 0;
            font-size: 0.78rem;
            letter-spacing: 0.32em;
            color: #f6e3a0;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.6);
        }

        .kf-glass .kf-label {
            margin-top: 1rem;
            color: rgba(251, 246, 234, 0.75);
            opacity: 1;
        }

        .kf-glass .kf-name {
            color: #fff;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.6);
        }

        .kf-glass .kf-num {
            font-size: 1.55rem;
            color: #f6e3a0;
            -webkit-text-fill-color: #f6e3a0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
        }

        .kf-glass .kf-divider {
            margin: 0.9rem auto 0;
        }

        /* buttons */
        .kf-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 1.4rem;
        }

        .kf-glass .kf-btn {
            margin-top: 0;
            padding: 0 1.2rem;
            color: #f6e3a0;
            border: 1px solid rgba(246, 227, 160, 0.8);
            background: rgba(27, 10, 44, 0.35);
        }

        .kf-glass .kf-btn:hover,
        .kf-glass .kf-btn.done {
            color: #1b0a2c;
            background: var(--foil);
        }

        .kf-glass .kf-btn-solid {
            color: #1b0a2c;
            background: var(--foil);
            border-color: #fff1b8;
        }

        .kf-glass .kf-btn-solid:hover {
            filter: brightness(1.08);
        }

        /* ---------- QR popup ---------- */
        .kf-modal {
            position: fixed;
            inset: 0;
            z-index: 95;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: kf-modal-fade 0.3s ease both;
        }

        .kf-modal[hidden] {
            display: none;
        }

        .kf-modal-bg {
            position: absolute;
            inset: 0;
            background: rgba(14, 5, 22, 0.82);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
        }

        .kf-modal-card {
            position: relative;
            width: min(100%, 22rem);
            padding: 26px 22px 20px;
            text-align: center;
            border-radius: 4px;
            color: #2a1238;
            background: #fbf6ea;
            border: 1px solid #e3bd5a;
            box-shadow:
                0 30px 70px rgba(0, 0, 0, 0.55),
                inset 0 0 0 4px #fbf6ea,
                inset 0 0 0 5px rgba(227, 189, 90, 0.6);
            animation: kf-modal-pop 0.45s cubic-bezier(0.2, 0.9, 0.2, 1) both;
        }

        .kf-modal-x {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 2;
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(168, 124, 46, 0.5);
            border-radius: 2px;
            background: transparent;
            color: #4a1f66;
            cursor: pointer;
        }

        .kf-modal-x:hover {
            background: rgba(227, 189, 90, 0.25);
        }

        .kf-modal-bank {
            margin: 0 0 12px;
            padding: 0 40px;
            font-size: 0.78rem;
            letter-spacing: 0.3em;
            font-weight: 600;
            color: #a87c2e;
        }

        .kf-modal-qr {
            width: min(100%, 16rem);
            margin: 0 auto;
            padding: 12px;
            background: #fff;
            border: 1px solid #e3bd5a;
            border-radius: 2px;
            box-shadow:
                inset 0 0 0 3px #fff,
                inset 0 0 0 4px rgba(227, 189, 90, 0.6);
        }

        .kf-modal-qr img {
            display: block;
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1;
            object-fit: contain;
        }

        .kf-modal-name {
            margin: 14px 0 0;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .kf-modal-scan {
            margin: 2px 0 0;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            opacity: 0.7;
        }

        @keyframes kf-modal-fade {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes kf-modal-pop {
            from {
                opacity: 0;
                transform: scale(0.88) translateY(20px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .kf-glass.float,
            .kf-glass-in::after,
            .kf-modal,
            .kf-modal-card {
                animation: none !important;
            }
        }
    </style>
    <script>
        (function() {
            var m = document.getElementById('qr-modal'),
                o = document.getElementById('qr-open');
            if (!m || !o) return;
            var last = null;

            function openQr() {
                last = document.activeElement;
                m.hidden = false;
                document.body.style.overflow = 'hidden';
                var x = m.querySelector('.kf-modal-x');
                if (x) x.focus();
            }

            function closeQr() {
                m.hidden = true;
                document.body.style.overflow = '';
                if (last && last.focus) last.focus();
            }
            o.addEventListener('click', openQr);
            m.addEventListener('click', function(e) {
                if (e.target.closest('[data-close]')) closeQr();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !m.hidden) closeQr();
            });
        })();
    </script>
@endif
