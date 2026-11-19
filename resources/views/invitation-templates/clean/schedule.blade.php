@if (count($events))
    <section class="cl alt" id="schedule">
        <div class="cl-wrap">
            <h2 class="cl-h rv title-font" data-v="blur" data-i18n="schedule">{{ $K['schedule'] }}</h2>
            {!! $orn !!}
            <p class="date-t rv title-font" data-i18n-date="long">{{ $w['dateText'] }}</p>
            <ol class="tl" data-tl>
                <span class="tl-fill" aria-hidden="true"></span>
                @foreach ($events as $i => $e)
                    @php $link = $safeLink($e['mapUrl'] ?? null); @endphp
                    <li class="rv" data-v="{{ $i % 2 ? 'right' : 'left' }}">
                        <span class="nd">{!! $ic($icons[$i % count($icons)], 16) !!}</span>
                        <div class="box">
                            <span class="no"
                                aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            @if (!empty($e['time']))
                                <p class="t">
                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M12 7v5l3 2" />
                                    </svg>
                                    <span>{{ $e['time'] }}</span>
                                </p>
                            @endif

                            <h3 class="title-font"data-en="{{ $e['title_en'] ?? '' }}">{{ $e['title'] ?? '' }}</h3>

                            @if (!empty($e['place']))
                                <p class="pl">{!! $ic('pin', 14) !!} <span
                                        data-en="{{ $e['place_en'] ?? '' }}">{{ $e['place'] }}</span></p>
                            @endif

                            @if ($link)
                                <a class="maplink" href="{{ $link }}" target="_blank" rel="noopener noreferrer">
                                    <span data-i18n="map">{{ $K['map'] }}</span>
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M5 12h14M13 6l6 6-6 6" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <style>
        #schedule {
            --ac: var(--accent, #d9b44a);
            --ac-d: color-mix(in srgb, var(--ac) 60%, #6b4a08)
        }

        /* date chip */
        #schedule .date-t {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            margin: .2rem auto 0;
            padding: .45rem 1.1rem;
            border-radius: 99px;
            border: 1px solid color-mix(in srgb, var(--ac) 55%, transparent);
            background: color-mix(in srgb, var(--ac) 10%, transparent);
            color: var(--ac-d);
            font-weight: 600
        }

        #schedule .date-t::before {
            content: "";
            flex: none;
            width: 16px;
            height: 16px;
            background: currentColor;
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='5' width='18' height='16' rx='2'/%3E%3Cpath d='M16 3v4M8 3v4M3 10h18'/%3E%3C/svg%3E") center/contain no-repeat;
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='5' width='18' height='16' rx='2'/%3E%3Cpath d='M16 3v4M8 3v4M3 10h18'/%3E%3C/svg%3E") center/contain no-repeat
        }

        #schedule .cl-wrap {
            text-align: center
        }

        /* rail */
        #schedule .tl {
            position: relative;
            list-style: none;
            margin: 2.2rem auto 0;
            padding: 0;
            max-width: 640px;
            text-align: left
        }

        #schedule .tl::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 19px;
            width: 2px;
            background: repeating-linear-gradient(to bottom, color-mix(in srgb, var(--ac) 45%, transparent) 0 6px, transparent 6px 12px)
        }

        #schedule .tl-fill {
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

        /* item */
        #schedule .tl li {
            position: relative;
            padding-left: 58px;
            margin-bottom: 1.5rem
        }

        #schedule .tl li:last-child {
            margin-bottom: 0
        }

        /* node */
        #schedule .nd {
            position: absolute;
            left: 0;
            top: 14px;
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #fff;
            color: var(--ac-d);
            border: 1.5px solid var(--ac);
            z-index: 2;
            box-shadow: 0 0 0 5px var(--bg, #fbf7ee);
            transition: background .4s, color .4s, transform .4s, box-shadow .4s
        }

        #schedule .tl li.on .nd {
            background: linear-gradient(135deg, var(--ac), var(--ac-d));
            color: #fff;
            transform: scale(1.08);
            box-shadow: 0 0 0 5px var(--bg, #fbf7ee), 0 0 0 6px color-mix(in srgb, var(--ac) 50%, transparent), 0 8px 18px -6px var(--ac)
        }

        #schedule .tl li.on .nd::after {
            content: "";
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            border: 1px solid var(--ac);
            animation: tlPulse 2.2s ease-out infinite
        }

        @keyframes tlPulse {
            0% {
                opacity: .7;
                transform: scale(1)
            }

            100% {
                opacity: 0;
                transform: scale(1.6)
            }
        }

        /* card */
        #schedule .box {
            position: relative;
            overflow: hidden;
            padding: 1rem 1.15rem 1.05rem;
            border-radius: 16px;
            background: color-mix(in srgb, #fff 82%, transparent);
            border: 1px solid color-mix(in srgb, var(--ac) 38%, transparent);
            box-shadow: 0 14px 28px -20px rgba(110, 78, 18, .5);
            transition: transform .35s cubic-bezier(.2, .8, .2, 1), box-shadow .35s, border-color .35s
        }

        #schedule .box:hover {
            transform: translateY(-3px);
            border-color: var(--ac);
            box-shadow: 0 20px 34px -20px rgba(110, 78, 18, .6)
        }

        #schedule .box::before {
            /* notch pointing to the node */
            content: "";
            position: absolute;
            left: -7px;
            top: 26px;
            width: 12px;
            height: 12px;
            transform: rotate(45deg);
            background: inherit;
            border-left: 1px solid color-mix(in srgb, var(--ac) 38%, transparent);
            border-bottom: 1px solid color-mix(in srgb, var(--ac) 38%, transparent)
        }

        #schedule .box .no {
            position: absolute;
            right: .9rem;
            top: .25rem;
            font-size: 2.6rem;
            line-height: 1;
            font-weight: 700;
            letter-spacing: .02em;
            color: color-mix(in srgb, var(--ac) 22%, transparent);
            pointer-events: none;
            user-select: none
        }

        #schedule .box .t {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin: 0 0 .55rem;
            padding: .22rem .7rem;
            border-radius: 99px;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .04em;
            color: var(--ac-d);
            background: color-mix(in srgb, var(--ac) 16%, transparent)
        }

        #schedule .box h3 {
            margin: 0 0 .3rem;
            font-size: 1.12rem;
            line-height: 1.5;
            padding-right: 2.4rem
        }

        #schedule .box .pl {
            display: flex;
            align-items: flex-start;
            gap: .4rem;
            margin: .15rem 0 0;
            font-size: .9rem;
            opacity: .8
        }

        #schedule .box .pl svg {
            flex: none;
            margin-top: .28em;
            color: var(--ac-d)
        }

        #schedule .maplink {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-top: .8rem;
            padding: .38rem .9rem;
            border-radius: 99px;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--ac-d);
            border: 1px solid color-mix(in srgb, var(--ac) 65%, transparent);
            transition: background .25s, color .25s, gap .25s
        }

        #schedule .maplink:hover,
        #schedule .maplink:focus-visible {
            background: var(--ac);
            color: #fff;
            gap: .65rem;
            outline: 0
        }

        /* wide screens: zig-zag around a centre line */
        @media (min-width:720px) {
            #schedule .tl {
                max-width: 820px
            }

            #schedule .tl::before,
            #schedule .tl-fill {
                left: calc(50% - 1px)
            }

            #schedule .tl li {
                width: 50%;
                padding: 0 52px 0 0
            }

            #schedule .tl li:nth-of-type(even) {
                margin-left: 50%;
                padding: 0 0 0 52px
            }

            #schedule .tl li:nth-of-type(odd) .nd {
                left: auto;
                right: -20px
            }

            #schedule .tl li:nth-of-type(even) .nd {
                left: -20px
            }

            #schedule .tl li:nth-of-type(odd) .box::before {
                left: auto;
                right: -7px;
                transform: rotate(-135deg)
            }

            #schedule .tl li:nth-of-type(even) {
                margin-top: -.4rem
            }
        }

        @media (prefers-reduced-motion:reduce) {

            #schedule *,
            #schedule *::after {
                animation: none !important;
                transition: none !important
            }
        }
    </style>

    <script>
        /* progress line + "reached" state for each stop */
        (function() {
            var tl = document.querySelector('#schedule .tl');
            if (!tl) return;
            var fill = tl.querySelector('.tl-fill'),
                lis = [].slice.call(tl.querySelectorAll('li')),
                tick = false;

            function upd() {
                tick = false;
                var vh = window.innerHeight,
                    line = vh * .62,
                    r = tl.getBoundingClientRect();
                var p = Math.max(0, Math.min(line - r.top, r.height));
                fill.style.setProperty('--tl', p + 'px');
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
