{{-- THANKS: night section, blooming lotus, fireflies --}}
<section class="cl dark" id="thanks" style="padding-bottom:120px">
    <div class="dk-fx" aria-hidden="true"></div>
    <div class="cl-wrap">
        <div class="bloom-big rv" data-v="zoom">{!! $bloom('cl-bloom big') !!}</div>
        <h2 class="cl-h rv" data-v="blur" data-i18n="thanks">{{ $K['thanks'] }}</h2>{!! $orn !!}
        <p class="txt rv" data-en="{{ $w['thanks_en'] ?? '' }}">{{ $w['thanks'] }}</p>
        <p class="names rv" data-delay="200">{{ $groom['name'] }} <span data-i18n="and">{{ $K['and'] }}</span> {{ $bride['name'] }}</p>
        <button class="btn shine rv" data-delay="300" type="button" id="share" style="margin-top:28px">{!! $ic('share', 16) !!} <span id="share-t" data-i18n="share">{{ $K['share'] }}</span></button>
    </div>
</section>
