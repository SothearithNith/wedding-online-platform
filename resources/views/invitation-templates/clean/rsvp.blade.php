{{-- RSVP (enhanced) — keeps all original IDs, names and data-i18n hooks so your existing JS still works --}}
<section class="cl" id="rsvp">
    <div class="cl-wrap">
        <h2 class="cl-h rv" data-i18n="rsvp">{{ $K['rsvp'] }}</h2>{!! $orn !!}
        <p class="mute rv" data-i18n="rsvpSub">{{ $K['rsvpSub'] }}</p>

        {{-- Success state --}}
        <div class="ok" id="ok" role="status">
            <span class="ok-burst" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i></span>
            <b>✓</b>
            <p data-i18n="sent">{{ $K['sent'] }}</p>
        </div>

        <form class="form rsvp-card rv" id="rs" method="POST" action="{{ $rsvpAction }}" data-att="1"
            novalidate>
            @csrf

            {{-- Name --}}
            <div class="rsvp-field">
                <input class="f" id="rs-name" name="guest_name" data-i18n-ph="yourName"
                    placeholder="{{ $K['yourName'] }}" maxlength="60" autocomplete="name" enterkeyhint="next">
                <p class="err-t" id="rs-err" role="alert" hidden data-i18n="required">{{ $K['required'] }}</p>
            </div>

            {{-- Attendance: two big choice cards --}}
            <input type="hidden" name="attending" id="rs-att" value="1">
            <div class="seg rsvp-seg" role="group">
                <button type="button" class="on" data-v="1" aria-pressed="true">
                    <span class="rsvp-emo" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z" />
                        </svg>
                    </span>
                    <span data-i18n="attend">{{ $K['attend'] }}</span>
                </button>
                <button type="button" data-v="0" aria-pressed="false">
                    <span class="rsvp-emo" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <path d="m3 7 9 6 9-6" />
                        </svg>
                    </span>
                    <span data-i18n="decline">{{ $K['decline'] }}</span>
                </button>
            </div>

            {{-- Guests: native select kept for JS/back-end, stepper added on top --}}
            <div class="rsvp-guests">
                <select class="f" name="guests_count" id="rs-g" data-i18n-aria="guests"
                    aria-label="{{ $K['guests'] }}">
                    @for ($n = 1; $n <= 5; $n++)
                        <option value="{{ $n }}" data-i18n="guests" data-n="{{ $n }}">
                            {{ $K['guests'] }}: {{ $n }}</option>
                    @endfor
                </select>
                <div class="rsvp-step" aria-hidden="true">
                    <button type="button" data-step="-1" tabindex="-1">−</button>
                    <span id="rs-gv">1</span>
                    <button type="button" data-step="1" tabindex="-1">+</button>
                </div>
            </div>

            {{-- Wish with counter --}}
            <div class="rsvp-field">
                <textarea class="f" id="rs-msg" name="message" rows="3" maxlength="200" data-i18n-ph="wish"
                    placeholder="{{ $K['wish'] }}"></textarea>
                <span class="rsvp-count" id="rs-cnt" aria-hidden="true">0/200</span>
            </div>

            <p class="err-t" id="rs-fail" role="alert" hidden style="text-align:center" data-i18n="failed">
                {{ $K['failed'] }}</p>

            <button class="btn shine" type="submit" id="rs-send" style="justify-self:center">
                {!! $ic('send', 16) !!} <span data-i18n="send">{{ $K['send'] }}</span>
            </button>
        </form>
    </div>
</section>

<style>
    /* ---------- RSVP enhancements (scoped; uses your theme vars with fallbacks) ---------- */
    #rsvp .rsvp-card {
        position: relative;
        max-width: 460px;
        margin: 1.5rem auto 0;
        padding: 1.75rem 1.5rem;
        display: grid;
        gap: 1rem;
        border-radius: 22px;
        background: color-mix(in srgb, var(--bg, #fff) 70%, transparent);
        border: 1px solid color-mix(in srgb, var(--accent, #c9a063) 35%, transparent);
        box-shadow: 0 18px 50px -20px color-mix(in srgb, var(--accent, #c9a063) 55%, transparent);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    #rsvp .rsvp-card::before {
        /* soft glowing edge */
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        z-index: -1;
        opacity: .5;
        background: linear-gradient(135deg, var(--accent, #c9a063), transparent 40%, transparent 60%, var(--accent, #c9a063));
        filter: blur(14px);
    }

    #rsvp .rsvp-field {
        position: relative
    }

    #rsvp .f {
        transition: border-color .25s, box-shadow .25s, transform .25s
    }

    #rsvp .f:focus {
        outline: 0;
        transform: translateY(-1px);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent, #c9a063) 28%, transparent)
    }

    #rsvp .f.bad {
        border-color: #d9534f;
        animation: rsShake .35s
    }

    @keyframes rsShake {
        25% {
            transform: translateX(-5px)
        }

        75% {
            transform: translateX(5px)
        }
    }

    /* attendance cards */
    #rsvp .rsvp-seg {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem
    }

    #rsvp .rsvp-seg button {
        display: grid;
        justify-items: center;
        gap: .35rem;
        padding: 1rem .5rem;
        cursor: pointer;
        border-radius: 16px;
        border: 1.5px solid color-mix(in srgb, var(--accent, #c9a063) 30%, transparent);
        background: transparent;
        color: inherit;
        font: inherit;
        transition: transform .25s cubic-bezier(.3, 1.4, .5, 1), background .25s, border-color .25s, box-shadow .25s;
    }

    #rsvp .rsvp-seg button:hover {
        transform: translateY(-2px)
    }

    #rsvp .rsvp-seg button.on {
        background: color-mix(in srgb, var(--accent, #c9a063) 16%, transparent);
        border-color: var(--accent, #c9a063);
        box-shadow: 0 8px 22px -10px var(--accent, #c9a063);
        transform: scale(1.03);
    }

    #rsvp .rsvp-emo {
        display: grid;
        place-items: center;
        line-height: 0;
        opacity: .7;
        transition: transform .35s cubic-bezier(.3, 1.6, .5, 1), opacity .25s, color .25s
    }

    #rsvp .rsvp-emo svg {
        display: block
    }

    #rsvp .rsvp-seg button.on .rsvp-emo {
        transform: scale(1.2) rotate(-8deg);
        opacity: 1;
        color: var(--accent, #c9a063)
    }

    #rsvp .rsvp-seg button.on[data-v="1"] .rsvp-emo svg {
        fill: color-mix(in srgb, var(--accent, #c9a063) 25%, transparent)
    }

    /* guests: hide native look, show stepper */
    #rsvp .rsvp-guests {
        position: relative;
        display: grid;
        transition: opacity .3s, max-height .35s, margin .35s;
        max-height: 90px
    }

    #rsvp .rsvp-step {
        position: absolute;
        inset: 0 .5rem 0 auto;
        display: flex;
        align-items: center;
        gap: .6rem;
        pointer-events: none
    }

    #rsvp .rsvp-step button {
        pointer-events: auto;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        line-height: 1;
        font-size: 1.1rem;
        border: 1px solid var(--accent, #c9a063);
        background: transparent;
        color: inherit;
        transition: background .2s, transform .15s;
    }

    #rsvp .rsvp-step button:hover {
        background: color-mix(in srgb, var(--accent, #c9a063) 20%, transparent)
    }

    #rsvp .rsvp-step button:active {
        transform: scale(.88)
    }

    #rsvp .rsvp-step span {
        min-width: 1ch;
        text-align: center;
        font-weight: 600
    }

    #rsvp .rsvp-card[data-att="0"] .rsvp-guests {
        opacity: 0;
        max-height: 0;
        margin: -.5rem 0;
        overflow: hidden;
        pointer-events: none
    }

    /* counter */
    #rsvp .rsvp-count {
        position: absolute;
        right: .8rem;
        bottom: .55rem;
        font-size: .7rem;
        opacity: .5;
        pointer-events: none
    }

    /* success burst */
    #rsvp .ok-burst {
        position: absolute;
        left: 50%;
        top: 1.6rem;
        width: 0;
        height: 0;
        pointer-events: none
    }

    #rsvp .ok-burst i {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent, #c9a063);
        opacity: 0
    }

    #rsvp .ok.show .ok-burst i,
    #rsvp .ok.on .ok-burst i,
    #rsvp .ok[style*="block"] .ok-burst i {
        animation: rsBurst .9s ease-out forwards
    }

    #rsvp .ok-burst i:nth-child(1) {
        --x: -60px;
        --y: -40px
    }

    #rsvp .ok-burst i:nth-child(2) {
        --x: 60px;
        --y: -44px
    }

    #rsvp .ok-burst i:nth-child(3) {
        --x: -80px;
        --y: 6px
    }

    #rsvp .ok-burst i:nth-child(4) {
        --x: 80px;
        --y: 10px
    }

    #rsvp .ok-burst i:nth-child(5) {
        --x: -36px;
        --y: -70px
    }

    #rsvp .ok-burst i:nth-child(6) {
        --x: 38px;
        --y: -72px
    }

    @keyframes rsBurst {
        0% {
            opacity: 1;
            transform: translate(0, 0) scale(1)
        }

        100% {
            opacity: 0;
            transform: translate(var(--x), var(--y)) scale(.3)
        }
    }

    /* send button loading */
    #rsvp #rs-send.is-loading {
        pointer-events: none;
        opacity: .75
    }

    #rsvp #rs-send.is-loading span::after {
        content: "…";
        animation: rsDots 1s steps(3) infinite
    }

    @keyframes rsDots {
        0% {
            content: "."
        }

        33% {
            content: ".."
        }

        66% {
            content: "..."
        }
    }

    @media (prefers-reduced-motion:reduce) {
        #rsvp * {
            animation: none !important;
            transition: none !important
        }
    }
</style>

<script>
    /* Purely additive — does not replace your existing RSVP script */
    (function() {
        var f = document.getElementById('rs');
        if (!f) return;
        var g = document.getElementById('rs-g'),
            gv = document.getElementById('rs-gv'),
            msg = document.getElementById('rs-msg'),
            cnt = document.getElementById('rs-cnt'),
            name = document.getElementById('rs-name'),
            send = document.getElementById('rs-send');

        // attendance -> toggle guests row
        f.querySelectorAll('.seg button').forEach(function(b) {
            b.addEventListener('click', function() {
                f.dataset.att = b.dataset.v;
            });
        });

        // stepper <-> select sync
        function sync() {
            gv.textContent = g.value;
        }
        f.querySelectorAll('[data-step]').forEach(function(b) {
            b.addEventListener('click', function() {
                var v = Math.min(5, Math.max(1, (+g.value || 1) + (+b.dataset.step)));
                g.value = v;
                g.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
                sync();
            });
        });
        g.addEventListener('change', sync);
        sync();

        // char counter
        function count() {
            cnt.textContent = msg.value.length + '/' + msg.maxLength;
        }
        msg.addEventListener('input', count);
        count();

        // shake on invalid name + clear on typing
        f.addEventListener('submit', function() {
            if (!name.value.trim()) {
                name.classList.add('bad');
                name.focus();
            } else send.classList.add('is-loading');
        });
        name.addEventListener('input', function() {
            name.classList.remove('bad');
        });
        new MutationObserver(function() {
                send.classList.remove('is-loading');
            })
            .observe(document.getElementById('rs-fail'), {
                attributes: true
            });
    })();
</script>
