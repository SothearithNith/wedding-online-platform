{{-- STORY: timeline that fills as you scroll --}}
@if (count($story))
<section class="cl" id="story"><div class="cl-wrap">
    <h2 class="cl-h rv" data-v="blur">{{ $K['story'] }}</h2>{!! $orn !!}
    <ol class="tl" data-fill>
        <span class="tl-fill" aria-hidden="true"></span>
        @foreach ($story as $i => $s)
            <li class="rv" data-v="{{ $i % 2 ? 'right' : 'left' }}">
                <span class="nd">{!! $ic('heart', 12) !!}</span>
                <div class="box">
                    <p class="t">{{ $s['year'] ?? '' }}</p><h3>{{ $s['title'] ?? '' }}</h3><p>{{ $s['text'] ?? '' }}</p>
                </div>
            </li>
        @endforeach
    </ol>
</div></section>
@endif
