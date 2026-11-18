{{-- STORY --}}
@if (count($story))
<section class="cl" id="story"><div class="cl-wrap">
    <h2 class="cl-h rv">{{ $K['story'] }}</h2>{!! $orn !!}
    <ol class="tl">
        @foreach ($story as $i => $s)
            <li class="rv" style="--d:{{ $i * 90 }}ms">
                <p class="t">{{ $s['year'] ?? '' }}</p><h3>{{ $s['title'] ?? '' }}</h3><p>{{ $s['text'] ?? '' }}</p>
            </li>
        @endforeach
    </ol>
</div></section>
@endif
