
@if (count($colors))
    <section class="g-sec g-photo" id="dresscode" style="padding:112px 20px;text-align:center">
        <div class="g-bgimg" data-bg="{{ $bg('dresscode') }}"></div>
        <div class="g-ov" style="{{ $overlay(0.08) }}"></div> 

        <div class="g-arch-glass">
            <div class="gloss"></div>
            {!! $title($w['dressCode']['title'] ?? 'ឈុតសម្លៀកបំពាក់') !!}

            <p class="rv ts"
                style="max-width:20rem;margin:0 auto;font-size:14px;line-height:1.7;color:rgba(255,255,255,.9)">
                {{ $w['dressCode']['note'] ?? '' }}</p>

            <div class="dc-colors">
                @foreach ($colors as $i => $c)
                    <div class="rv" data-v="zoom" data-delay="{{ $i * 100 }}">
                        <span class="dc-sw"><span style="background:{{ $c }}"></span></span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
