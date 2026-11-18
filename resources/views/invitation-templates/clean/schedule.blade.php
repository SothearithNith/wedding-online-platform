{{-- SCHEDULE --}}
@if (count($events))
<section class="cl alt" id="schedule"><div class="cl-wrap">
    <h2 class="cl-h rv">{{ $K['schedule'] }}</h2>{!! $orn !!}
    <ol class="tl">
        @foreach ($events as $i => $e)
            @php $link = $safeLink($e['mapUrl'] ?? null); @endphp
            <li class="rv" style="--d:{{ $i * 90 }}ms">
                <p class="t">{{ $e['time'] ?? '' }}</p><h3>{{ $e['title'] ?? '' }}</h3>
                @if (!empty($e['place']))<p>{{ $e['place'] }}</p>@endif
                @if ($link)<a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $K['map'] }} →</a>@endif
            </li>
        @endforeach
    </ol>
</div></section>
@endif
