@if (count($story)) 
    <section class="g-sec g-photo" id="story" style="padding:112px 20px">
        <div class="g-bgimg" data-bg="{{ $bg('story') }}"></div>
        <div class="g-ov" style="{{ $overlay(0.08) }}"></div>
        {!! $edge('top', '#ffffff') !!}
        <div class="g-arch-glass">
            <div class="gloss"></div>
            {!! $title($K['story']) !!}
            <ol class="tl ts" id="tl">
                <span class="tl-line" aria-hidden="true"></span>
                @foreach ($story as $i => $s)
                    {{ $s['title'] }}
                    <li>
                        <span class="node ts-none">{!! $ic('heart', 14) !!}</span>
                        <div class="rv" data-v="{{ $i % 2 ? 'right' : 'left' }}">
                            <p class="yr">{{ $s['year'] ?? '' }}</p>
                            <h3 class="font-display">{{ $s['title'] ?? '' }}</h3>
                            <p>{{ $s['text'] ?? '' }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
        {!! $edge('bottom', '#ffffff') !!}
    </section>
@endif
