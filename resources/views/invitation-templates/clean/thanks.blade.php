<section class="cl dark thanks" id="thanks">
    <div class="dk-fx" aria-hidden="true"></div>

    {{-- floating petals (pure CSS) --}}
    <div class="thanks-petals" aria-hidden="true">
        @for ($i = 1; $i <= 9; $i++)
            <i style="--i:{{ $i }}"></i>
        @endfor
    </div>

    <div class="cl-wrap thanks-wrap">

        {{-- bloom with halo --}}
        <div class="thanks-bloom rv" data-v="zoom" aria-hidden="true">
            <span class="thanks-halo"></span>
            <span class="thanks-ring"></span>
            {!! $bloom('cl-bloom big') !!}
        </div>

        {{-- title --}}
        <h2 class="cl-h thanks-title rv title-font" data-v="blur" data-i18n="thanks">{{ $K['thanks'] }}</h2>
        {!! $orn !!}

        {{-- message --}}
        <blockquote class="thanks-msg rv" data-delay="100">
            <p class="txt" data-en="{{ $w['thanks_en'] ?? '' }}">{{ $w['thanks'] }}</p>
        </blockquote>

        {{-- names --}}
        <div class="thanks-names rv title-font" data-delay="200">
            <span class="thanks-name">{{ $groom['name'] }}</span>
            <span class="thanks-and" data-i18n="and">{{ $K['and'] }}</span>
            <span class="thanks-name">{{ $bride['name'] }}</span>
        </div>

        {{-- actions --}}
        <div class="thanks-actions rv" data-delay="300">
            <button class="btn shine" type="button" id="share">
                {!! $ic('share', 16) !!}
                <span id="share-t" data-i18n="share">{{ $K['share'] }}</span>
            </button>

            <a href="#top" class="thanks-top" aria-label="Back to top">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 19V5M5 12l7-7 7 7" />
                </svg>
            </a>
        </div>
    </div>
    <style>
        .thanks {
            position: relative;
            overflow: hidden;
            padding: 110px 0 120px;
        }

        .thanks-wrap {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* bloom + halo (sizes itself, never clipped) */
        .thanks-bloom {
            position: relative;
            display: grid;
            place-items: center;
            padding: 40px;
            margin-bottom: 18px;
            overflow: visible;
        }

        .thanks-bloom svg {
            overflow: visible;
        }

        .thanks-bloom .cl-bloom {
            position: relative;
            z-index: 2;
        }

        .thanks-halo {
            position: absolute;
            inset: -10%;
            border-radius: 50%;
            background: radial-gradient(circle, color-mix(in srgb, var(--gold, #d9b878) 38%, transparent) 0%, transparent 68%);
            animation: thHalo 5s ease-in-out infinite;
        }

        .thanks-ring {
            position: absolute;
            inset: 10%;
            border-radius: 50%;
            border: 1px solid color-mix(in srgb, var(--gold, #d9b878) 55%, transparent);
            animation: thRing 6s ease-out infinite;
        }

        .thanks-ring::after {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            border: inherit;
            animation: inherit;
            animation-delay: 3s;
        }

        /* title */
        .thanks-title {
            font-size: clamp(2rem, 6vw, 3rem);
            letter-spacing: .04em;
            margin: 0 0 6px;
        }

        /* message card (quote mark sits inside, no background box) */
        .thanks-msg {
            position: relative;
            max-width: 520px;
            margin: 22px auto 0;
            padding: 54px 30px 26px;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(255, 255, 255, .06), rgba(255, 255, 255, .02));
            backdrop-filter: blur(6px);
        }

        .thanks-msg::before {
            content: "\201C";
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            font: 56px/1 Georgia, serif;
            color: var(--gold, #d9b878);
            background: none;
            padding: 0;
            pointer-events: none;
        }

        .thanks-msg p {
            position: relative;
            z-index: 1;
            margin: 0;
            line-height: 1.9;
        }

        /* names */
        .thanks-names {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            margin-top: 34px;
        }

        .thanks-name {
            font-size: clamp(1.6rem, 5vw, 2.4rem);
            line-height: 1.15;
        }

        .thanks-and {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: .95rem;
            font-style: italic;
            color: var(--gold, #d9b878);
            opacity: .9;
        }

        .thanks-and::before,
        .thanks-and::after {
            content: "";
            width: 44px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold, #d9b878));
        }

        .thanks-and::after {
            transform: scaleX(-1);
        }

        /* actions */
        .thanks-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 34px;
        }

        .thanks-top {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .22);
            color: inherit;
            transition: .3s;
        }

        .thanks-top:hover {
            transform: translateY(-3px);
            border-color: var(--gold, #d9b878);
            color: var(--gold, #d9b878);
        }

        /* petals */
        .thanks-petals {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .thanks-petals i {
            position: absolute;
            top: -8%;
            left: calc(var(--i) * 10.5%);
            width: 10px;
            height: 14px;
            border-radius: 80% 0 80% 0;
            background: color-mix(in srgb, var(--gold, #d9b878) 70%, transparent);
            opacity: 0;
            animation: thFall calc(9s + var(--i) * 1.3s) linear infinite;
            animation-delay: calc(var(--i) * -1.7s);
        }

        @keyframes thHalo {

            0%,
            100% {
                opacity: .55;
                transform: scale(.95);
            }

            50% {
                opacity: 1;
                transform: scale(1.08);
            }
        }

        @keyframes thRing {
            0% {
                transform: scale(.8);
                opacity: .8;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        @keyframes thFall {
            0% {
                transform: translate3d(0, 0, 0) rotate(0);
                opacity: 0;
            }

            10% {
                opacity: .7;
            }

            100% {
                transform: translate3d(calc(var(--i) * 6px - 30px), 110vh, 0) rotate(420deg);
                opacity: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .thanks-halo,
            .thanks-ring,
            .thanks-ring::after,
            .thanks-petals i {
                animation: none;
            }

            .thanks-petals {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .thanks {
                padding: 80px 0 90px;
            }

            .thanks-msg {
                padding: 50px 20px 22px;
                margin-inline: 12px;
            }
        }
    </style>
</section>
