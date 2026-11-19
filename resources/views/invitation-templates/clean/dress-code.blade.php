@if (count($colors))
    @php
        $dcTitle = $w['dressCode']['title'] ?? null;
        $dcNote = $w['dressCode']['note'] ?? '';
        $copied = isset($K['copied']) ? $K['copied'] : 'Copied';
    @endphp
    <section class="cl alt" id="dresscode">
        <div class="cl-wrap">
            <span class="dc-ico rv" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 7.5a2.5 2.5 0 1 0-2.5-2.5" />
                    <path d="M12 7.5v2L3.6 15.3A1.6 1.6 0 0 0 4.5 18h15a1.6 1.6 0 0 0 .9-2.7L12 9.5" />
                </svg>
            </span>

            <h2 class="cl-h rv title-font"
                @if ($dcTitle) data-en="{{ $w['dressCode']['title_en'] ?? '' }}" @else data-i18n="dressCode" @endif>
                {{ $dcTitle ?: 'ឈុតសម្លៀកបំពាក់' }}</h2>{!! $orn !!}

            @if ($dcNote)
                <p class="txt dc-note rv" data-en="{{ $w['dressCode']['note_en'] ?? '' }}">{{ $dcNote }}</p>
            @endif
            <div class="sw dc-sw" id="dc-sw" data-copied="{{ $copied }}">
                @foreach ($colors as $c)
                    <button type="button" class="dc-chip rv" data-v="zoom"
                        style="--c:{{ $c }};--d:{{ $loop->index * 90 }}ms" data-color="{{ $c }}"
                        aria-label="{{ $c }}">
                        <i style="background:{{ $c }}"></i>
                        @if (\Illuminate\Support\Str::startsWith($c, '#'))
                            <small>{{ strtoupper($c) }}</small>
                        @endif
                    </button>
                @endforeach
            </div>

            {{-- blended preview strip --}}
            @if (count($colors) > 1)
                <div class="dc-strip rv" aria-hidden="true">
                    @foreach ($colors as $c)
                        <span style="background:{{ $c }}"></span>
                    @endforeach
                </div>
            @endif

            <p class="dc-toast" id="dc-toast" role="status" aria-live="polite"></p>
        </div>
    </section>

    <style>
        #dresscode .dc-ico {
            display: inline-grid;
            place-items: center;
            width: 58px;
            height: 58px;
            margin-bottom: .4rem;
            border-radius: 50%;
            color: var(--accent, #c9a063);
            border: 1px solid color-mix(in srgb, var(--accent, #c9a063) 40%, transparent);
            background: color-mix(in srgb, var(--accent, #c9a063) 10%, transparent)
        }

        #dresscode .dc-note {
            max-width: 34rem;
            margin-inline: auto;
            line-height: 1.8
        }

        #dresscode .dc-sw {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.25rem 1.1rem;
            margin: 1.6rem auto 0;
            max-width: 36rem
        }

        #dresscode .dc-chip {
            all: unset;
            box-sizing: border-box;
            cursor: pointer;
            display: grid;
            justify-items: center;
            gap: .5rem;
            transition: transform .3s cubic-bezier(.3, 1.5, .5, 1)
        }

        #dresscode .dc-chip:hover,
        #dresscode .dc-chip:focus-visible {
            transform: translateY(-6px)
        }

        #dresscode .dc-chip:focus-visible {
            outline: 2px solid var(--accent, #c9a063);
            outline-offset: 6px;
            border-radius: 14px
        }

        #dresscode .dc-chip i {
            display: block;
            width: 62px;
            height: 62px;
            border-radius: 50%;
            position: relative;
            border: 3px solid var(--bg, #fff);
            box-shadow: 0 0 0 1.5px color-mix(in srgb, var(--c) 70%, #000 10%),
                0 12px 22px -10px var(--c);
            transition: box-shadow .3s, transform .3s
        }

        #dresscode .dc-chip:hover i {
            box-shadow: 0 0 0 1.5px var(--c), 0 18px 28px -10px var(--c);
            transform: scale(1.06)
        }

        #dresscode .dc-chip i::after {
            /* glossy highlight */
            content: "";
            position: absolute;
            inset: 7px 18px auto 9px;
            height: 14px;
            border-radius: 50%;
            background: linear-gradient(rgba(255, 255, 255, .55), transparent);
            opacity: .7
        }

        #dresscode .dc-chip small {
            font-size: .68rem;
            letter-spacing: .08em;
            opacity: .55;
            transition: opacity .2s
        }

        #dresscode .dc-chip:hover small {
            opacity: 1
        }

        #dresscode .dc-chip.copied i {
            animation: dcPop .45s ease
        }

        @keyframes dcPop {
            40% {
                transform: scale(1.18)
            }
        }

        /* gradient preview strip */
        #dresscode .dc-strip {
            display: flex;
            max-width: 22rem;
            height: 10px;
            margin: 1.8rem auto 0;
            border-radius: 99px;
            overflow: hidden;
            box-shadow: 0 6px 16px -8px rgba(0, 0, 0, .35)
        }

        #dresscode .dc-strip span {
            flex: 1
        }

        #dresscode .dc-toast {
            height: 1.4rem;
            margin: .9rem 0 0;
            font-size: .8rem;
            letter-spacing: .04em;
            opacity: 0;
            color: var(--accent, #c9a063);
            transition: opacity .25s
        }

        #dresscode .dc-toast.on {
            opacity: 1
        }

        @media (max-width:420px) {
            #dresscode .dc-chip i {
                width: 52px;
                height: 52px
            }
        }

        @media (prefers-reduced-motion:reduce) {
            #dresscode * {
                animation: none !important;
                transition: none !important
            }
        }
    </style>

    <script>
        /* additive: tap a swatch → copy its code */
        (function() {
            var box = document.getElementById('dc-sw'),
                toast = document.getElementById('dc-toast'),
                t;
            if (!box) return;
            box.addEventListener('click', function(e) {
                var b = e.target.closest('.dc-chip');
                if (!b) return;
                var v = b.dataset.color;
                (navigator.clipboard ? navigator.clipboard.writeText(v) : Promise.reject()).catch(function() {
                    var x = document.createElement('textarea');
                    x.value = v;
                    document.body.appendChild(x);
                    x.select();
                    try {
                        document.execCommand('copy')
                    } catch (_) {}
                    x.remove();
                });
                b.classList.remove('copied');
                void b.offsetWidth;
                b.classList.add('copied');
                toast.textContent = box.dataset.copied + ' · ' + v;
                toast.classList.add('on');
                clearTimeout(t);
                t = setTimeout(function() {
                    toast.classList.remove('on')
                }, 1600);
            });
        })();
    </script>
@endif
