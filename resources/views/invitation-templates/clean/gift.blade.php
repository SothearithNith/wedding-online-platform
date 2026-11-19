@if (!empty($gift['accountNumber']))
    <section class="cl alt gift" id="gift">
        <div class="cl-wrap gift-wrap">
            <h2 class="cl-h rv title-font" data-v="blur" data-i18n="gift">{{ $K['gift'] }}</h2>
            {!! $orn !!}
            <p class="txt gift-sub rv" data-delay="100" data-i18n="giftSub">{{ $K['giftSub'] }}</p>
            <div class="gc rv" data-v="zoom" data-delay="150">
                <div class="gc-top">
                    <svg class="gc-chip" width="42" height="32" viewBox="0 0 42 32" fill="none"
                        aria-hidden="true">
                        <rect x=".5" y=".5" width="41" height="31" rx="6" fill="url(#gc-chip-g)"
                            stroke="rgba(0,0,0,.25)" />
                        <path
                            d="M1 11h13a4 4 0 0 1 4 4v2a4 4 0 0 1-4 4H1M41 11H28a4 4 0 0 0-4 4v2a4 4 0 0 0 4 4h13M21 1v30"
                            stroke="rgba(60,40,10,.45)" stroke-width="1" />
                        <defs>
                            <linearGradient id="gc-chip-g" x1="0" y1="0" x2="42" y2="32">
                                <stop offset="0" stop-color="#f6e3a8" />
                                <stop offset=".55" stop-color="#d9b878" />
                                <stop offset="1" stop-color="#b8923a" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <span class="gc-bank">{{ $gift['bank'] ?? '' }}</span>
                </div>
                <p class="gc-no" id="acc">{{ $gift['accountNumber'] }}</p>

                <span class="gc-line" aria-hidden="true"></span> 
                <div class="gc-bottom">
                    <div class="gc-holder">
                        <small data-i18n="accName">{{ $K['accName'] ?? 'Account name' }}</small>
                        <strong>{{ $gift['accountName'] ?? '' }}</strong>
                    </div>

                    <button class="gc-qr" type="button" id="qr-open" aria-haspopup="dialog" aria-controls="qr-modal"
                        aria-label="{{ $K['showQr'] ?? 'Show QR' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1" />
                        </svg>
                        <span data-i18n="showQr">{{ $K['showQr'] ?? trans_json('Show QR') }}</span>
                    </button>
                </div>
            </div>
            <button class="gc-copy rv" data-delay="250" type="button" id="copy">
                {!! $ic('copy', 16) !!}
                <span id="copy-t" data-i18n="copy">{{ $K['copy'] }}</span>
            </button>
        </div>

        {{-- ========== 4. QR POPUP ========== --}}
        <div class="qr-modal" id="qr-modal" role="dialog" aria-modal="true" aria-label="QR" aria-hidden="true">
            <div class="qr-backdrop" data-qr-close></div>

            <div class="qr-sheet">
                <button class="qr-close" type="button" data-qr-close aria-label="{{ $K['close'] ?? 'Close' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>

                <p class="qr-title" data-i18n="scan">{{ $K['scan'] }}</p>

                <div class="qr-tile">
                    <img src="{{ $giftQr }}" alt="QR" onerror="this.style.display='none'">
                </div>

                <p class="qr-bank">{{ $gift['bank'] ?? '' }}</p>
                <p class="qr-name">{{ $gift['accountName'] ?? '' }}</p>

                <a class="qr-save" href="{{ $giftQr }}" download="gift-qr">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 4v11M7 11l5 5 5-5M5 20h14" />
                    </svg>
                    <span data-i18n="saveQr">{{ $K['saveQr'] ?? trans_json('Save QR') }}</span>
                </a>
            </div>
        </div>

        <style>
            .gift {
                padding: 90px 0 100px;
            }

            .gift-wrap {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .gift-sub {
                max-width: 420px;
                margin: 12px auto 0;
                line-height: 1.9;
            }

            /* ---- card: espresso + gold ---- */
            .gc {
                --g1: #f6e3a8;
                --g2: #d9b878;
                --g3: #b8923a;
                position: relative;
                overflow: hidden;
                isolation: isolate;
                width: 100%;
                max-width: 380px;
                margin: 34px auto 0;
                padding: 24px 26px 22px;
                text-align: left;
                color: #f3e7c9;
                border-radius: 24px;
                background:
                    radial-gradient(circle at 100% 0%, rgba(217, 184, 120, .22), transparent 55%),
                    radial-gradient(circle at 0% 100%, rgba(217, 184, 120, .10), transparent 50%),
                    linear-gradient(160deg, #2b2016 0%, #17100a 100%);
                border: 1px solid rgba(217, 184, 120, .4);
                box-shadow: 0 28px 54px -28px rgba(0, 0, 0, .7);
            }

            /* thin inner gold frame */
            .gc::before {
                content: "";
                position: absolute;
                inset: 8px;
                pointer-events: none;
                border-radius: 17px;
                border: 1px solid rgba(217, 184, 120, .18);
            }

            /* one light sweep when the card appears — the only ambient motion */
            .gc::after {
                content: "";
                position: absolute;
                inset: 0;
                z-index: 2;
                pointer-events: none;
                background: linear-gradient(105deg, transparent 38%, rgba(246, 227, 168, .22) 50%, transparent 62%);
                transform: translateX(-120%);
            }

            .gc.in::after,
            .gc.is-in::after,
            .gc.visible::after {
                animation: gc-sweep 1.6s .5s ease-out 1 forwards;
            }

            @keyframes gc-sweep {
                to {
                    transform: translateX(120%);
                }
            }

            .gc>* {
                position: relative;
                z-index: 1;
            }

            .gc-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }

            .gc-chip {
                display: block;
                flex: none;
            }

            .gc-bank {
                font-weight: 700;
                font-size: 1rem;
                letter-spacing: .06em;
                color: var(--g1);
                text-align: right;
                overflow-wrap: anywhere;
            }

            /* gold foil number */
            .gc-no {
                margin: 30px 0 22px;
                font-size: clamp(1.45rem, 6.6vw, 1.9rem);
                font-weight: 700;
                letter-spacing: .08em;
                line-height: 1.25;
                font-variant-numeric: tabular-nums;
                word-break: break-all;
                user-select: all;
                color: var(--g2);
                /* fallback */
                background: linear-gradient(120deg, var(--g3) 0%, var(--g1) 35%, var(--g2) 55%, var(--g1) 75%, var(--g3) 100%);
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
                text-shadow: none;
            }

            .gc-line {
                display: block;
                height: 1px;
                margin-bottom: 16px;
                background: linear-gradient(90deg, rgba(217, 184, 120, .6), transparent);
            }

            .gc-bottom {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 14px;
            }

            .gc-holder {
                display: flex;
                flex-direction: column;
                gap: 3px;
                min-width: 0;
            }

            .gc-holder small {
                font-size: .72rem;
                letter-spacing: .04em;
                opacity: .6;
            }

            .gc-holder strong {
                font-size: 1.02rem;
                font-weight: 600;
                color: #fff;
                overflow-wrap: anywhere;
            }

            /* QR pill: icon + label so its purpose is obvious */
            .gc-qr {
                flex: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                min-height: 44px;
                padding: 0 16px 0 13px;
                border-radius: 999px;
                cursor: pointer;
                font: inherit;
                font-size: .82rem;
                font-weight: 600;
                color: var(--g1);
                background: rgba(217, 184, 120, .1);
                border: 1px solid rgba(217, 184, 120, .55);
                transition: background .25s, color .25s;
            }

            .gc-qr:hover {
                background: var(--g2);
                color: #1b130b;
            }

            /* copy button (gold) */
            .gc-copy {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                max-width: 380px;
                min-height: 52px;
                margin-top: 16px;
                padding: 14px 20px;
                border: 0;
                border-radius: 999px;
                cursor: pointer;
                font: inherit;
                font-weight: 700;
                letter-spacing: .02em;
                color: #1f160c;
                background: linear-gradient(120deg, #b8923a, #f6e3a8 50%, #b8923a);
                background-size: 200% 100%;
                box-shadow: 0 14px 28px -14px rgba(184, 146, 58, .8);
                transition: background-position .6s, transform .2s;
            }

            .gc-copy:hover {
                background-position: 100% 0;
                transform: translateY(-2px);
            }

            .gc-copy:active {
                transform: translateY(0) scale(.98);
            }

            /* feedback state (added by script below when the button is pressed) */
            .gc-copy.is-done {
                background-position: 100% 0;
            }

            .gc-qr:focus-visible,
            .gc-copy:focus-visible,
            .qr-close:focus-visible,
            .qr-save:focus-visible {
                outline: 2px solid #f6e3a8;
                outline-offset: 3px;
            }

            .qr-close:focus-visible,
            .qr-save:focus-visible {
                outline-color: #b8923a;
            }

            /* ---- popup ---- */
            .qr-modal {
                position: fixed;
                inset: 0;
                z-index: 9999;
                display: grid;
                place-items: center;
                padding: 20px;
                visibility: hidden;
                opacity: 0;
                transition: opacity .3s ease, visibility 0s linear .3s;
            }

            .qr-modal.is-open {
                visibility: visible;
                opacity: 1;
                transition: opacity .3s ease, visibility 0s;
            }

            .qr-backdrop {
                position: absolute;
                inset: 0;
                background: rgba(12, 8, 4, .78);
                -webkit-backdrop-filter: blur(6px);
                backdrop-filter: blur(6px);
            }

            .qr-sheet {
                position: relative;
                width: 100%;
                max-width: 320px;
                max-height: 100%;
                overflow-y: auto;
                padding: 30px 24px 24px;
                text-align: center;
                border-radius: 26px;
                background: #fffdf8;
                color: #2a2118;
                box-shadow: 0 30px 70px -20px rgba(0, 0, 0, .65);
                transform: translateY(18px) scale(.96);
                transition: transform .35s cubic-bezier(.2, .8, .2, 1);
            }

            .qr-modal.is-open .qr-sheet {
                transform: none;
            }

            .qr-sheet::before {
                content: "";
                position: absolute;
                top: 0;
                left: 18%;
                right: 18%;
                height: 3px;
                background: linear-gradient(90deg, transparent, #b8923a, transparent);
            }

            .qr-close {
                position: absolute;
                top: 12px;
                right: 12px;
                display: grid;
                place-items: center;
                width: 40px;
                height: 40px;
                border: 0;
                border-radius: 50%;
                cursor: pointer;
                background: rgba(42, 33, 24, .07);
                color: #2a2118;
                transition: background .2s;
            }

            .qr-close:hover {
                background: rgba(42, 33, 24, .14);
            }

            .qr-title {
                margin: 0 0 16px;
                padding: 0 40px;
                font-size: .9rem;
                font-weight: 600;
                opacity: .7;
            }

            .qr-tile {
                padding: 12px;
                border-radius: 18px;
                line-height: 0;
                border: 1px solid rgba(184, 146, 58, .35);
                background: #fff;
            }

            .qr-tile img {
                display: block;
                width: 100%;
                height: auto;
            }

            .qr-bank {
                margin: 18px 0 0;
                font-weight: 700;
            }

            .qr-name {
                margin: 3px 0 0;
                font-size: .9rem;
                opacity: .65;
            }

            .qr-save {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-height: 44px;
                margin-top: 18px;
                padding: 0 20px;
                border-radius: 999px;
                font-size: .88rem;
                font-weight: 600;
                text-decoration: none;
                color: #2a2118;
                border: 1px solid rgba(184, 146, 58, .6);
                transition: background .2s;
            }

            .qr-save:hover {
                background: rgba(184, 146, 58, .12);
            }

            html.qr-lock,
            html.qr-lock body {
                overflow: hidden;
            }

            @media (max-width: 480px) {
                .gift {
                    padding: 70px 0 80px;
                }

                .gc,
                .gc-copy {
                    width: calc(100% - 24px);
                }

                .gc {
                    padding: 22px 20px 20px;
                }
            }

            @media (prefers-reduced-motion: reduce) {

                .gc::after {
                    animation: none !important;
                }

                .gc-qr,
                .gc-copy,
                .qr-modal,
                .qr-sheet,
                .qr-close,
                .qr-save {
                    transition: none;
                }
            }
        </style>

        <script>
            (function() {
                var modal = document.getElementById('qr-modal');
                var openBtn = document.getElementById('qr-open');
                if (!modal || !openBtn) return;

                // move popup to <body> so page animations can't break position:fixed
                document.body.appendChild(modal);

                var lastFocus = null;
                var FOCUSABLE = 'button, a[href], [tabindex]:not([tabindex="-1"])';

                function openModal() {
                    lastFocus = document.activeElement;
                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.documentElement.classList.add('qr-lock');
                    var c = modal.querySelector('.qr-close');
                    if (c) c.focus();
                }

                function closeModal() {
                    modal.classList.remove('is-open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.documentElement.classList.remove('qr-lock');
                    if (lastFocus && lastFocus.focus) lastFocus.focus();
                }

                openBtn.addEventListener('click', openModal);

                modal.addEventListener('click', function(e) {
                    if (e.target.closest('[data-qr-close]')) closeModal();
                });

                document.addEventListener('keydown', function(e) {
                    if (!modal.classList.contains('is-open')) return;

                    if (e.key === 'Escape') {
                        closeModal();
                        return;
                    }

                    // keep keyboard focus inside the popup
                    if (e.key === 'Tab') {
                        var items = modal.querySelectorAll(FOCUSABLE);
                        if (!items.length) return;
                        var first = items[0];
                        var last = items[items.length - 1];
                        if (e.shiftKey && document.activeElement === first) {
                            e.preventDefault();
                            last.focus();
                        } else if (!e.shiftKey && document.activeElement === last) {
                            e.preventDefault();
                            first.focus();
                        }
                    }
                });

                // small visual confirmation on the copy button (text is handled by your existing copy script)
                var copyBtn = document.getElementById('copy');
                if (copyBtn) {
                    copyBtn.addEventListener('click', function() {
                        copyBtn.classList.add('is-done');
                        setTimeout(function() {
                            copyBtn.classList.remove('is-done');
                        }, 1800);
                    });
                }
            })();
        </script>
    </section>
@endif
