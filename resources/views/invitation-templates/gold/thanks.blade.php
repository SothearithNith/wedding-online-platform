<section class="g-sec g-photo" id="thanks" style="padding:128px 24px 160px;text-align:center">
    <div class="g-bgimg" data-bg="{{ $bg('thanks') }}"></div>
    <div class="g-ov" style="{{ $overlay(0.6) }}"></div>
    {!! $edge('top', '#fbf6ea') !!}

    <div id="th">
        {!! $bloom('th-bloom') !!}
        <h2 class="rv g-foil font-display th-title" data-v="blur">{{ $K['thanks'] }}</h2>
        <p class="rv" data-delay="150"
            style="max-width:20rem;margin:20px auto 0;font-size:14px;line-height:2;color:rgba(255,255,255,.9)">
            {{ $w['thanks'] }}</p>
        <p class="rv font-display" data-delay="300" style="margin-top:32px;font-size:16px">
            {{ $groom['name'] }} {{ $K['and'] }} {{ $bride['name'] }}</p>
        <div class="rv" data-delay="450">
            <button type="button" class="th-btn" id="share-btn">
                <span id="share-ic">{!! $ic('share', 16) !!}</span>
                <span id="share-tx">{{ $K['share'] }}</span>
            </button>
        </div>
    </div>
</section>
