@php $variant = $variant ?? 'ornament'; @endphp
{{-- ornament: animated gold divider | band: flowing kbach strip --}}
@if ($variant === 'band')
    <div class="cl-kbach" aria-hidden="true"></div>
@else
    @once
        {{-- shared defs, rendered one time per page --}}
        <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
            <defs>
                <linearGradient id="gdBase" gradientUnits="userSpaceOnUse" x1="0" y1="20" x2="0"
                    y2="56">
                    <stop offset="0" stop-color="#ffe98a" />
                    <stop offset=".5" stop-color="#e8b923" />
                    <stop offset="1" stop-color="#b8860b" />
                </linearGradient>

                {{-- light band that sweeps across the whole divider --}}
                <linearGradient id="gdSweep" gradientUnits="userSpaceOnUse" x1="-170" y1="0" x2="0"
                    y2="0">
                    <stop offset="0" stop-color="#fff6c4" stop-opacity="0" />
                    <stop offset=".5" stop-color="#fff6c4" stop-opacity=".95" />
                    <stop offset="1" stop-color="#fff6c4" stop-opacity="0" />
                    <animateTransform attributeName="gradientTransform" type="translate" values="0 0; 850 0; 850 0"
                        keyTimes="0; .5; 1" dur="6s" begin="1.6s" repeatCount="indefinite" />
                </linearGradient>

                <path id="gdL" fill-rule="evenodd"
                    d="M34.50 26.83 L33.83 27.17 L27.17 33.83 L23.67 36.83 L22.33 38.67 L22.17 39.67 L22.67 40.50 L25.50 42.33 L30.33 46.67 L33.50 50.17 L34.67 50.67 L35.17 50.50 L42.17 43.33 L46.00 40.67 L46.83 39.83 L47.00 39.00 L44.83 36.00 L40.00 32.33 L38.00 30.33 L35.50 27.17Z M34.33 35.00 L35.17 35.00 L35.83 35.67 L36.67 37.67 L37.67 38.83 L37.67 39.83 L35.00 42.67 L33.83 42.50 L31.50 39.83 L31.50 38.83 L32.83 37.67 L33.67 35.67Z M48.83 30.83 L48.83 32.17 L49.83 34.67 L52.83 37.50 L54.17 37.67 L55.17 37.17 L57.17 34.33 L57.00 32.67 L55.67 31.67 L54.67 32.00 L53.67 31.83 L53.67 31.00 L54.83 30.17 L57.17 30.17 L59.67 31.33 L62.00 33.67 L63.50 36.17 L64.00 38.50 L64.00 39.67 L63.50 41.00 L60.50 45.17 L57.83 47.33 L55.33 47.83 L54.33 47.50 L53.17 46.17 L53.33 45.00 L54.17 45.00 L55.00 45.67 L56.00 45.67 L57.33 44.50 L57.50 43.50 L57.17 42.67 L55.50 41.17 L53.33 41.00 L51.67 41.50 L49.67 43.17 L48.83 45.00 L48.83 46.50 L49.33 47.83 L51.33 50.00 L54.00 51.17 L57.67 51.00 L60.50 49.83 L62.17 48.67 L68.00 42.50 L68.83 42.50 L70.83 43.50 L71.83 43.50 L74.00 42.17 L81.00 42.67 L84.67 42.00 L100.50 41.83 L277.17 41.83 L279.17 42.00 L284.67 43.33 L291.83 46.00 L293.33 46.17 L295.33 45.33 L303.17 39.83 L303.17 38.83 L301.50 37.17 L300.50 35.67 L294.83 31.83 L293.67 31.33 L292.17 31.33 L285.67 34.17 L278.50 36.00 L105.00 36.00 L85.17 35.83 L82.67 35.00 L73.67 35.50 L71.67 34.33 L70.33 34.50 L69.17 35.33 L67.83 35.33 L65.17 31.83 L62.17 28.83 L60.00 27.33 L56.50 26.00 L54.00 26.00 L51.17 27.17 L49.50 29.00Z" />
                <path id="gdR" fill-rule="evenodd"
                    d="M595.00 26.83 L590.33 31.67 L585.50 36.00 L584.67 37.50 L583.33 39.00 L583.33 39.67 L584.17 40.67 L588.67 44.33 L594.50 50.50 L595.83 50.83 L602.17 44.17 L607.33 40.17 L607.50 38.83 L606.00 36.33 L603.83 34.83 L596.17 26.83Z M595.00 35.33 L595.83 35.33 L596.50 36.00 L597.17 37.83 L598.17 39.00 L598.17 40.17 L595.67 42.83 L594.67 42.83 L592.33 40.00 L592.33 38.83 L593.50 37.67 L594.17 36.17Z M581.17 31.00 L580.33 28.83 L579.17 27.50 L577.00 26.17 L574.83 25.83 L571.67 26.50 L568.83 27.83 L565.83 30.50 L562.17 35.17 L560.83 35.17 L559.67 34.33 L558.50 34.17 L556.17 35.50 L554.00 35.17 L550.83 35.33 L547.00 35.00 L544.00 36.00 L361.33 36.00 L355.33 34.50 L348.50 31.50 L346.67 31.17 L345.17 31.67 L338.83 36.00 L336.50 38.67 L336.50 39.50 L338.33 41.33 L346.33 46.33 L349.00 45.67 L355.50 43.17 L360.00 42.00 L370.17 41.83 L371.83 41.50 L386.17 42.00 L394.00 41.67 L416.50 41.67 L437.50 42.00 L537.83 41.83 L544.50 42.00 L546.67 42.50 L555.83 42.17 L558.00 43.67 L559.00 43.67 L560.83 42.50 L562.17 42.50 L566.50 47.33 L569.33 49.67 L572.83 51.00 L576.00 51.17 L578.33 50.33 L580.33 48.33 L580.83 47.33 L581.00 45.67 L580.17 43.33 L579.00 42.00 L577.33 41.00 L574.83 41.00 L572.83 42.50 L572.50 43.17 L572.50 44.50 L572.83 45.17 L573.83 45.83 L574.83 45.83 L575.83 45.17 L576.67 45.17 L576.67 46.50 L576.00 47.17 L574.67 47.67 L573.33 47.67 L570.83 46.50 L568.50 44.50 L566.17 40.83 L566.00 38.17 L566.50 36.00 L568.00 33.67 L570.67 31.00 L572.83 30.00 L574.83 30.00 L575.83 30.50 L575.83 31.33 L573.83 31.67 L573.00 33.00 L572.83 34.00 L573.17 34.83 L575.83 37.50 L577.17 37.67 L577.83 37.33 L579.83 35.00 L581.00 32.67Z" />
                <path id="gdS" fill-rule="evenodd"
                    d="M320.50 18.17 L319.67 18.17 L318.67 19.00 L315.67 23.83 L313.33 26.67 L310.67 27.50 L304.50 27.50 L303.50 27.83 L303.17 28.33 L303.33 29.50 L306.67 36.33 L307.33 38.83 L307.17 40.33 L303.50 47.50 L303.50 48.83 L304.50 49.50 L313.17 50.17 L314.17 51.00 L318.83 58.67 L319.67 59.17 L320.83 58.50 L325.83 50.33 L326.83 49.83 L329.67 50.00 L335.17 49.50 L336.17 48.83 L336.00 47.00 L332.67 39.67 L332.67 38.50 L333.33 36.00 L336.33 29.50 L336.50 28.50 L336.17 27.83 L335.33 27.50 L330.17 27.67 L327.50 27.50 L326.67 27.17 L325.67 26.17 L323.00 21.67Z M319.33 30.83 L320.50 30.83 L321.17 31.50 L322.50 33.83 L325.17 36.33 L326.50 38.50 L326.50 39.50 L326.17 40.00 L323.83 41.33 L320.17 46.00 L319.17 46.00 L317.00 43.00 L313.00 39.17 L313.00 37.83 L313.33 37.00 L315.33 35.67 L317.33 33.67Z" />
                <path id="gdSp" d="M0 -6 Q1 -1 6 0 Q1 1 0 6 Q-1 1 -6 0 Q-1 -1 0 -6Z" />
            </defs>
        </svg>

        <style>
            .gd-orn {
                width: 100%;
                max-width: 460px;
                margin: 0 auto;
                padding: 14px 18px;
                line-height: 0;
            }

            .gd-orn svg {
                display: block;
                width: 100%;
                height: auto;
                overflow: visible;
            }

            .gd-s,
            .gd-sp {
                transform-box: fill-box;
                transform-origin: center;
            }

            .gd-sp {
                fill: #fff3b0;
                opacity: 0;
            }

            /* hidden start state (only when JS is running, so no-JS still shows the divider) */
            .js .gd-orn:not(.on) .gd-l {
                clip-path: inset(0 0 0 100%);
            }

            .js .gd-orn:not(.on) .gd-r {
                clip-path: inset(0 100% 0 0);
            }

            .js .gd-orn:not(.on) .gd-s {
                opacity: 0;
                transform: scale(.2) rotate(-120deg);
            }

            /* 1) star pops + spins in, then keeps a soft glow pulse */
            .gd-orn.on .gd-s {
                animation: gdPop .9s cubic-bezier(.2, 1.4, .4, 1) both,
                    gdGlow 3.4s 1.4s ease-in-out infinite;
            }

            /* 2) lines draw outward from the star */
            .gd-orn.on .gd-l {
                animation: gdRevL 1.4s .5s cubic-bezier(.65, 0, .2, 1) both;
            }

            .gd-orn.on .gd-r {
                animation: gdRevR 1.4s .5s cubic-bezier(.65, 0, .2, 1) both;
            }

            /* 3) sparkles twinkle around the star */
            .gd-orn.on .gd-sp {
                animation: gdTw 3s ease-in-out infinite;
            }

            .gd-orn.on .gd-sp1 {
                animation-delay: 1.5s;
            }

            .gd-orn.on .gd-sp2 {
                animation-delay: 2.6s;
            }

            .gd-orn.on .gd-sp3 {
                animation-delay: 3.4s;
            }

            @keyframes gdPop {
                from {
                    opacity: 0;
                    transform: scale(.2) rotate(-120deg);
                }

                to {
                    opacity: 1;
                    transform: none;
                }
            }

            @keyframes gdGlow {

                0%,
                100% {
                    filter: drop-shadow(0 0 0 rgba(255, 214, 90, 0));
                }

                50% {
                    filter: drop-shadow(0 0 7px rgba(255, 214, 90, .95));
                }
            }

            @keyframes gdRevL {
                from {
                    clip-path: inset(0 0 0 100%);
                }

                to {
                    clip-path: inset(0 0 0 0);
                }
            }

            @keyframes gdRevR {
                from {
                    clip-path: inset(0 100% 0 0);
                }

                to {
                    clip-path: inset(0 0 0 0);
                }
            }

            @keyframes gdTw {

                0%,
                100% {
                    opacity: 0;
                    transform: scale(.2) rotate(0deg);
                }

                50% {
                    opacity: 1;
                    transform: scale(1) rotate(90deg);
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .gd-orn * {
                    animation: none !important;
                }

                .gd-orn .gd-l,
                .gd-orn .gd-r {
                    clip-path: none !important;
                }

                .gd-orn .gd-s {
                    opacity: 1 !important;
                    transform: none !important;
                }
            }
        </style>

        <script>
            (function() {
                function init() {
                    var els = document.querySelectorAll('.gd-orn');
                    if (!('IntersectionObserver' in window)) {
                        els.forEach(function(e) {
                            e.classList.add('on');
                        });
                        return;
                    }
                    var io = new IntersectionObserver(function(es) {
                        es.forEach(function(e) {
                            if (e.isIntersecting) {
                                e.target.classList.add('on');
                                io.unobserve(e.target);
                            }
                        });
                    }, {
                        threshold: .5
                    });
                    els.forEach(function(e) {
                        io.observe(e);
                    });
                }
                if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
                else init();
            })
            ();
        </script>
    @endonce

    <div class="gd-orn" aria-hidden="true">
        <svg viewBox="0 0 642 74" xmlns="http://www.w3.org/2000/svg">
            <g class="gd-l">
                <use href="#gdL" fill="url(#gdBase)" />
                <use href="#gdL" fill="url(#gdSweep)" />
            </g>
            <g class="gd-r">
                <use href="#gdR" fill="url(#gdBase)" />
                <use href="#gdR" fill="url(#gdSweep)" />
            </g>
            <g class="gd-s">
                <use href="#gdS" fill="url(#gdBase)" />
                <use href="#gdS" fill="url(#gdSweep)" />
            </g>

            <g transform="translate(346 20)">
                <use class="gd-sp gd-sp1" href="#gdSp" />
            </g>
            <g transform="translate(294 56)">
                <use class="gd-sp gd-sp2" href="#gdSp" />
            </g>
            <g transform="translate(322 9) scale(.7)">
                <use class="gd-sp gd-sp3" href="#gdSp" />
            </g>
        </svg>
    </div>
@endif
