<section class="cl apology" id="apology">
    <div class="cl-wrap apology-wrap">
        <div class="apology-icon rv" data-v="zoom" aria-hidden="true">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z" />
            </svg>
        </div>
        <h2 class="cl-h apology-title rv title-font" data-v="blur" data-i18n="apology">{{ $K['apology'] }}</h2>
        {!! $orn !!}

        {{-- message card --}}
        <div class="apology-card rv" data-delay="100">
            <i class="apology-corner tl" aria-hidden="true"></i>
            <i class="apology-corner br" aria-hidden="true"></i>
            <p class="txt" data-en="{{ $w['apology_en'] ?? '' }}">{{ $w['apology'] }}</p>
        </div>

    </div>
    <style>
        .apology {
            position: relative;
            overflow: hidden;
            padding: 90px 0 100px;
        }

        .apology-wrap {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* icon medallion */
        .apology-icon {
            display: grid;
            place-items: center;
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            border-radius: 50%;
            color: var(--gold, #b8923a);
            border: 1px solid color-mix(in srgb, var(--gold, #b8923a) 45%, transparent);
            background: radial-gradient(circle, color-mix(in srgb, var(--gold, #b8923a) 14%, transparent), transparent 70%);
            box-shadow: 0 0 0 6px color-mix(in srgb, var(--gold, #b8923a) 7%, transparent);
            animation: apBeat 4.5s ease-in-out infinite;
        }

        /* title */
        .apology-title {
            font-size: clamp(1.8rem, 5.5vw, 2.6rem);
            letter-spacing: .03em;
            margin: 0 0 6px;
        }

        /* message card */
        .apology-card {
            position: relative;
            max-width: 540px;
            margin: 24px auto 0;
            padding: 34px 32px;
            border-radius: 18px;
            border: 1px solid color-mix(in srgb, var(--gold, #b8923a) 30%, transparent);
            background: linear-gradient(180deg,
                    color-mix(in srgb, var(--gold, #b8923a) 8%, transparent),
                    color-mix(in srgb, var(--gold, #b8923a) 2%, transparent));
        }

        .apology-card p {
            position: relative;
            z-index: 1;
            margin: 0;
            line-height: 1.95;
        }

        /* gold corner accents */
        .apology-corner {
            position: absolute;
            width: 26px;
            height: 26px;
            pointer-events: none;
            border: 0 solid var(--gold, #b8923a);
        }

        .apology-corner.tl {
            top: 10px;
            left: 10px;
            border-top-width: 1.5px;
            border-left-width: 1.5px;
            border-top-left-radius: 10px;
        }

        .apology-corner.br {
            bottom: 10px;
            right: 10px;
            border-bottom-width: 1.5px;
            border-right-width: 1.5px;
            border-bottom-right-radius: 10px;
        }

        @keyframes apBeat {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.06);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .apology-icon {
                animation: none;
            }
        }

        @media (max-width: 480px) {
            .apology {
                padding: 70px 0 80px;
            }

            .apology-card {
                padding: 28px 20px;
                margin-inline: 12px;
            }
        }
    </style>
</section>
