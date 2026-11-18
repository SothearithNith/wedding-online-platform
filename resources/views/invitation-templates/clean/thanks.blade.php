{{-- THANKS: night section, blooming lotus, fireflies --}}
<section class="cl dark" id="thanks" style="padding-bottom:120px">
    <div class="dk-fx" aria-hidden="true"></div>
    <div class="cl-wrap">
        <div class="bloom-big rv" data-v="zoom">{!! $bloom('cl-bloom big') !!}</div>
        <h2 class="cl-h rv" data-v="blur">{{ $K['thanks'] }}</h2>{!! $orn !!}
        <p class="txt rv">{{ $w['thanks'] }}</p>
        <p class="names rv" data-delay="200">{{ $groom['name'] }} {{ $K['and'] }} {{ $bride['name'] }}</p>
        <button class="btn shine rv" data-delay="300" type="button" id="share" style="margin-top:28px">{!! $ic('share', 16) !!} <span id="share-t">{{ $K['share'] }}</span></button>
    </div>
</section>
