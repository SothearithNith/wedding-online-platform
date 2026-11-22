@if (count($gallery))
    @php $galBg = $bg('gallery'); @endphp
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="kg-gold" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#fdf7e8" />
                <stop offset=".35" stop-color="#e5c162" />
                <stop offset=".7" stop-color="#a97d2f" />
                <stop offset="1" stop-color="#fceebc" />
            </linearGradient>

            <path id="kg-flame"
                d="M0 0C-10 -12 -4 -22 -8 -32C-4 -30 0 -34 -2 -44C4 -38 6 -44 6 -58C14 -44 12 -28 6 -18C10 -20 12 -14 8 -8C6 -4 3 -2 0 0Z" />
            <g id="kg-side" fill="url(#kg-gold)" stroke="#4b1a6b" stroke-width=".7" stroke-linejoin="round">
                <use href="#kg-flame" transform="translate(69 195) rotate(-14) scale(1.2)" />
                <use href="#kg-flame" transform="translate(67 254) rotate(-6) scale(.9)" />
                <use href="#kg-flame" transform="translate(86 319) rotate(-76) scale(1.2)" />
                <use href="#kg-flame" transform="translate(102 349) rotate(-100) scale(.95)" />
                <use href="#kg-flame" transform="translate(127 375) rotate(-150) scale(.8)" />
            </g>

            <symbol id="kg-art" viewBox="0 0 340 440">
                <use href="#kg-side" />
                <use href="#kg-side" transform="translate(340 0) scale(-1 1)" />

                {{-- crown (same as couple, moved down onto the taller ring) --}}
                <g transform="translate(0 20)" fill="url(#kg-gold)" stroke="#4b1a6b" stroke-width=".8"
                    stroke-linejoin="round">
                    <path d="M170 4C181 17 189 23 185 38L155 38C151 23 159 17 170 4Z" />
                    <path d="M155 38C147 29 138 29 131 35C140 36 147 40 152 46Z" />
                    <path d="M185 38C193 29 202 29 209 35C200 36 193 40 188 46Z" />
                </g>
                <path transform="translate(0 20)" d="M170 14C175 22 177 27 175 34L165 34C163 27 165 22 170 14Z"
                    fill="#fdf7e8" opacity=".7" />
                <ellipse cx="170" cy="222" rx="110" ry="174" fill="none" stroke="#e5c162"
                    stroke-width="1" opacity=".5" />
                <ellipse cx="170" cy="222" rx="104" ry="168" fill="none"
                    stroke="url(#kg-gold)" stroke-width="8" />
                <ellipse cx="170" cy="222" rx="98.5" ry="162.5" fill="none" stroke="#fdf7e8"
                    stroke-width="1.4" opacity=".85" />
                <ellipse cx="170" cy="222" rx="108" ry="172" fill="none" stroke="#fdf7e8"
                    stroke-width="2" stroke-dasharray=".1 7" stroke-linecap="round" opacity=".55" /> 
                <g fill="url(#kg-gold)" stroke="#4b1a6b" stroke-width=".8" stroke-linejoin="round">
                    <path d="M162 396C152 396 144 391 140 383C150 383 158 387 164 393Z" />
                    <path d="M178 396C188 396 196 391 200 383C190 383 182 387 176 393Z" />
                </g>
                <path d="M170 380L181 392L170 404L159 392Z" fill="#fdf7e8" stroke="#4b1a6b" stroke-width=".8"
                    stroke-linejoin="round" />
            </symbol>
        </defs>
    </svg>

    <section class="kg-section" id="gallery">
        <div class="g-bgimg on" data-bg="{{ $galBg }}" data-eager style="z-index:0"></div>
        <div class="kg-overlay" aria-hidden="true"></div>
        <svg aria-hidden="true" class="kg-kanok top" width="100%" height="34">
            <defs>
                <pattern id="kn-t" width="34" height="34" patternUnits="userSpaceOnUse">
                    <path d="M17 2 C19 11 27 13 27 22 C27 28 22 32 17 32 C12 32 7 28 7 22 C7 13 15 11 17 2 Z"
                        fill="none" stroke="{{ $GOLD }}" stroke-width="1.4" />
                    <path
                        d="M17 12 C18.5 17 22 18 22 23 C22 26 19.5 28 17 28 C14.5 28 12 26 12 23 C12 18 15.5 17 17 12 Z"
                        fill="{{ $GOLD }}" opacity=".55" />
                    <circle cx="3" cy="30" r="1.6" fill="{{ $GOLD }}" />
                    <circle cx="31" cy="30" r="1.6" fill="{{ $GOLD }}" />
                </pattern>
            </defs>
            <rect width="100%" height="34" fill="url(#kn-t)" />
        </svg>
        <svg aria-hidden="true" class="kg-kanok bottom" width="100%" height="34" style="transform:scaleY(-1)">
            <rect width="100%" height="34" fill="url(#kn-t)" />
        </svg>

        <div class="kg-dust" aria-hidden="true">
            @for ($i = 0; $i < 16; $i++)
                <i
                    style="left:{{ ($i * 37) % 100 }}%;animation-duration:{{ 9 + ($i % 6) * 2 }}s;animation-delay:{{ ($i % 8) * 1.3 }}s;--dx:{{ (($i % 5) - 2) * 24 }}px"></i>
            @endfor
        </div>

        <div style="position:relative;z-index:2">
            <div class="kg-orn" aria-hidden="true"><span></span>{!! $lotus(12, 64, 'g-spin', 1.2) !!}<span></span></div>
            {!! $title($K['gallery']) !!}
            <div class="kg-grid">
                @foreach ($gallery as $i => $src)
                    <div class="kg-tile {{ $i % 2 ? 'kg-odd' : '' }}" style="--d:{{ ($i % 4) * 140 }}ms">
                        <button type="button" class="kg-frame" data-i="{{ $i }}"
                            aria-label="{{ $K['gallery'] }} {{ $i + 1 }}">
                            <span class="kg-art">
                                <span class="kg-halo" aria-hidden="true"></span>
                                <span class="kg-photo">
                                    <img src="{{ $src }}" alt="" loading="lazy">
                                </span>
                                <svg class="kg-frame-svg" viewBox="0 0 340 440" aria-hidden="true">
                                    <use href="#kg-art" width="340" height="440" />
                                </svg>
                            </span>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="kg-lb" id="kg-lb" role="dialog" aria-modal="true">
        <div class="kg-lb-frame" id="kg-lb-frame"><img id="kg-lb-img" src="" alt=""></div>
        <div class="kg-thumbs" id="kg-thumbs">
            @foreach ($gallery as $i => $src)
                <button type="button" class="kg-thumb" data-i="{{ $i }}"
                    aria-label="{{ $K['gallery'] }} {{ $i + 1 }}"><img src="{{ $src }}"
                        alt=""></button>
            @endforeach
        </div>
        <p class="kg-count" id="kg-count"></p>
        <button type="button" class="kg-circle x" id="kg-x"
            aria-label="{{ $K['close'] }}">{!! $ic('x', 24) !!}</button>
        <button type="button" class="kg-circle p" id="kg-p"
            aria-label="{{ $K['prev'] }}">{!! $ic('left', 24) !!}</button>
        <button type="button" class="kg-circle n" id="kg-n"
            aria-label="{{ $K['next'] }}">{!! $ic('right', 24) !!}</button>
    </div>
@endif
