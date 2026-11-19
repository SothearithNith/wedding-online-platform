{{-- STORY (enhanced): love-story timeline — big year, heart nodes, zig-zag on wide screens, fills as you scroll.
     Keeps: .tl, .tl-fill, li.rv[data-v], .nd, .box, .t, h3[data-en], p[data-en], data-i18n="story".
     Fixed: the old <p class="title-font" class="t"> had two class attributes (the 2nd was ignored).
     `data-fill` -> `data-tl`: the script at the bottom drives the progress line (put `data-fill` back to use your old one). --}}
@if (count($story))
    <section class="cl" id="story">
        <div class="cl-wrap">
            <h2 class="cl-h rv title-font" data-v="blur" data-i18n="story">{{ $K['story'] }}</h2>{!! $orn !!}

            <ol class="tl" data-tl>
                <span class="tl-fill" aria-hidden="true"></span>
                @foreach ($story as $i => $s)
                    <li class="rv" data-v="{{ $i % 2 ? 'right' : 'left' }}">
                        <span class="nd">{!! $ic('heart', 16) !!}</span>
                        <div class="box">
                            @if (!empty($s['year']))
                                <p class="t title-font">{{ $s['year'] }}</p>
                            @endif
                            <h3 class="title-font" data-en="{{ $s['title_en'] ?? '' }}">{{ $s['title'] ?? '' }}</h3>
                            @if (!empty($s['text']))
                                <p class="tx" data-en="{{ $s['text_en'] ?? '' }}">{{ $s['text'] }}</p>
                            @endif
                            <svg class="bg-heart" viewBox="0 0 24 24" width="64" height="64" fill="none"
                                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path
                                    d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z" />
                            </svg>
                        </div>
                    </li>
                @endforeach
            </ol>

            {{-- closing heart --}}
            <div class="st-end rv" data-v="zoom" aria-hidden="true"><span></span>{!! $ic('heart', 22) !!}<span></span>
            </div>
        </div>
    </section>

    <style>
        #story {
            --ac: var(--accent, #d9b44a);
            --ac-d: color-mix(in srgb, var(--ac) 60%, #6b4a08)
        }

        #story .cl-wrap {
            text-align: center
        }

        /* rail */
        #story .tl {
            position: relative;
            list-style: none;
            margin: 2.2rem auto 0;
            padding: 0;
            max-width: 640px;
            text-align: left
        }

        #story .tl::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 19px;
            width: 2px;
            background: repeating-linear-gradient(to bottom, color-mix(in srgb, var(--ac) 45%, transparent) 0 6px, transparent 6px 12px)
        }

        #story .tl-fill {
            position: absolute;
            top: 0;
            left: 19px;
            width: 2px;
            height: var(--tl, 0px);
            display: block;
            border-radius: 2px;
            background: linear-gradient(to bottom, var(--ac), var(--ac-d));
            box-shadow: 0 0 10px color-mix(in srgb, var(--ac) 70%, transparent);
            transition: height .12s linear
        }

        #story .tl li {
            position: relative;
            padding-left: 58px;
            margin-bottom: 1.8rem
        }

        #story .tl li:last-child {
            margin-bottom: 0
        }

        /* heart node */
        #story .nd {
            position: absolute;
            left: 0;
            top: 16px;
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #fff;
            color: color-mix(in srgb, var(--ac) 80%, #fff);
            border: 1.5px solid var(--ac);
            z-index: 2;
            box-shadow: 0 0 0 5px var(--bg, #fbf7ee);
            transition: background .4s, color .4s, transform .4s, box-shadow .4s
        }

        #story .tl li.on .nd {
            background: linear-gradient(135deg, var(--ac), var(--ac-d));
            color: #fff;
            box-shadow: 0 0 0 5px var(--bg, #fbf7ee), 0 0 0 6px color-mix(in srgb, var(--ac) 50%, transparent), 0 8px 18px -6px var(--ac);
            animation: stBeat 1.8s ease-in-out infinite
        }

        @keyframes stBeat {

            0%,
            100% {
                transform: scale(1.04)
            }

            15% {
                transform: scale(1.16)
            }

            30% {
                transform: scale(1.04)
            }

            45% {
                transform: scale(1.12)
            }
        }

        /* card */
        #story .box {
            position: relative;
            overflow: hidden;
            padding: 1.15rem 1.25rem 1.2rem;
            border-radius: 18px;
            background: linear-gradient(160deg, color-mix(in srgb, #fff 88%, transparent), color-mix(in srgb, var(--ac) 7%, #fff));
            border: 1px solid color-mix(in srgb, var(--ac) 38%, transparent);
            box-shadow: 0 16px 30px -22px rgba(110, 78, 18, .55);
            transition: transform .35s cubic-bezier(.2, .8, .2, 1), box-shadow .35s, border-color .35s
        }

        #story .box:hover {
            transform: translateY(-3px);
            border-color: var(--ac);
            box-shadow: 0 22px 36px -22px rgba(110, 78, 18, .65)
        }

        #story .box::before {
            content: "";
            position: absolute;
            left: -7px;
            top: 28px;
            width: 12px;
            height: 12px;
            transform: rotate(45deg);
            background: color-mix(in srgb, #fff 90%, var(--ac));
            border-left: 1px solid color-mix(in srgb, var(--ac) 38%, transparent);
            border-bottom: 1px solid color-mix(in srgb, var(--ac) 38%, transparent)
        }

        /* year: big, with a fading rule */
        #story .box .t {
            display: flex;
            align-items: center;
            gap: .7rem;
            margin: 0 0 .45rem;
            font-size: 1.7rem;
            line-height: 1.2;
            color: var(--ac-d);
            letter-spacing: .03em
        }

        #story .box .t::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, color-mix(in srgb, var(--ac) 60%, transparent), transparent)
        }

        #story .box h3 {
            margin: 0 0 .4rem;
            font-size: 1.1rem;
            line-height: 1.55
        }

        #story .box .tx {
            margin: 0;
            font-size: .93rem;
            line-height: 1.95;
            opacity: .82
        }

        #story .bg-heart {
            position: absolute;
            right: -10px;
            bottom: -12px;
            color: color-mix(in srgb, var(--ac) 28%, transparent);
            transform: rotate(-12deg);
            pointer-events: none
        }

        /* closing heart */
        #story .st-end {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .9rem;
            margin-top: 2rem;
            color: var(--ac)
        }

        #story .st-end span {
            width: min(90px, 22vw);
            height: 1px;
            background: linear-gradient(to right, transparent, var(--ac))
        }

        #story .st-end span:last-child {
            transform: scaleX(-1)
        }

        /* wide screens: zig-zag around a centre line */
        @media (min-width:720px) {
            #story .tl {
                max-width: 820px
            }

            #story .tl::before,
            #story .tl-fill {
                left: calc(50% - 1px)
            }

            #story .tl li {
                width: 50%;
                padding: 0 52px 0 0
            }

            #story .tl li:nth-of-type(even) {
                margin-left: 50%;
                padding: 0 0 0 52px;
                margin-top: -.4rem
            }

            #story .tl li:nth-of-type(odd) .nd {
                left: auto;
                right: -20px
            }

            #story .tl li:nth-of-type(even) .nd {
                left: -20px
            }

            #story .tl li:nth-of-type(odd) .box::before {
                left: auto;
                right: -7px;
                transform: rotate(-135deg)
            }
        }

        @media (prefers-reduced-motion:reduce) {

            #story *,
            #story *::after {
                animation: none !important;
                transition: none !important
            }
        }
    </style>

    <script>
        /* progress line + "reached" state for each memory */
        (function() {
            var tl = document.querySelector('#story .tl');
            if (!tl) return;
            var fill = tl.querySelector('.tl-fill'),
                lis = [].slice.call(tl.querySelectorAll('li')),
                tick = false;

            function upd() {
                tick = false;
                var vh = window.innerHeight,
                    line = vh * .62,
                    r = tl.getBoundingClientRect();
                fill.style.setProperty('--tl', Math.max(0, Math.min(line - r.top, r.height)) + 'px');
                lis.forEach(function(li) {
                    var n = li.querySelector('.nd').getBoundingClientRect();
                    li.classList.toggle('on', n.top + n.height / 2 < line);
                });
            }

            function req() {
                if (!tick) {
                    tick = true;
                    requestAnimationFrame(upd);
                }
            }
            addEventListener('scroll', req, {
                passive: true
            });
            addEventListener('resize', req);
            upd();
        })();
    </script>
@endif
