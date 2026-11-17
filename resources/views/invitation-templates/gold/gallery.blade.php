 @if (count($gallery))
     @php $galBg = $bg('gallery'); @endphp
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
                         <svg aria-hidden="true" viewBox="0 0 40 30" width="34" height="26" class="kg-finial">
                             <path d="M20 0 L24 10 L20 8 L16 10 Z" fill="{{ $GOLD }}" />
                             <path d="M20 8 C26 12 30 18 30 28 L10 28 C10 18 14 12 20 8 Z" fill="none"
                                 stroke="{{ $GOLD }}" stroke-width="1.4" />
                             <circle cx="20" cy="20" r="2.2" fill="{{ $GOLD }}" />
                         </svg>
                         <button type="button" class="kg-frame" data-i="{{ $i }}"
                             aria-label="{{ $K['gallery'] }} {{ $i + 1 }}">
                             <div class="kg-inner"><img src="{{ $src }}" alt="" loading="lazy"></div>
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
