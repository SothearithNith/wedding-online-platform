<section class="g-sec g-photo" id="thanks" style="padding:112px 20px 128px;text-align:center">
    <div class="g-bgimg" data-bg="{{ $bg('thanks') }}"></div>
    <div class="g-ov" style="{{ $overlay(0.35) }}"></div>
    <div class="petals" aria-hidden="true">
        @for ($i = 0; $i < 10; $i++)
            <i
                style="left:{{ ($i * 41 + 6) % 100 }}%;animation-duration:{{ 10 + ($i % 4) * 3 }}s;animation-delay:{{ ($i % 6) * 1.7 }}s;--dx:{{ (($i % 5) - 2) * 34 }}px;--rot:{{ ($i % 2 ? 1 : -1) * (220 + $i * 25) }}deg"></i>
        @endfor
    </div>

    <div class="g-arch-glass" style="padding:48px 24px 44px">
        <div class="gloss"></div>

        <div id="th">
            <h2 class="rv g-foil font-display th-title title-font" data-v="blur">{{ $K['thanks'] }}</h2>

            <div class="th-rule rv" data-delay="100" aria-hidden="true"><span></span><b></b><span></span></div>

            <p class="rv ts" data-delay="150"
                style="max-width:20rem;margin:0 auto;font-size:14px;line-height:2;color:rgba(255,255,255,.92)">
                {{ $w['thanks'] }}</p>

            <div class="th-rule rv" data-delay="250" aria-hidden="true"><span></span><b></b><span></span></div>

            <p class="rv g-foil font-display title-font" data-delay="300" style="font-size:17px;line-height:1.9">
                {{ $groom['name'] }} {{ $K['and'] }} {{ $bride['name'] }}</p>

            <div class="rv" data-delay="450">
                <button type="button" class="th-btn btn-foil title-font" id="share-btn">
                    <span id="share-ic">{!! $ic('share', 16) !!}</span>
                    <span id="share-tx">{{ $K['share'] }}</span>
                </button>
            </div>
        </div>
    </div>
</section>
