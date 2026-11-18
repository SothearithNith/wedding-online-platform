{{-- SCHEDULE: timeline cards with icons --}}
@if (count($events))
<section class="cl alt" id="schedule"><div class="cl-wrap">
    <h2 class="cl-h rv" data-v="blur">{{ $K['schedule'] }}</h2>{!! $orn !!}
    <p class="date-t rv">{{ $w['dateText'] }}</p>
    <ol class="tl" data-fill>
        <span class="tl-fill" aria-hidden="true"></span>
        @foreach ($events as $i => $e)
            @php $link = $safeLink($e['mapUrl'] ?? null); @endphp
            <li class="rv" data-v="{{ $i % 2 ? 'right' : 'left' }}">
                <span class="nd">{!! $ic($icons[$i % count($icons)], 13) !!}</span>
                <div class="box">
                    <p class="t">{{ $e['time'] ?? '' }}</p><h3>{{ $e['title'] ?? '' }}</h3>
                    @if (!empty($e['place']))<p>{!! $ic('pin', 13) !!} {{ $e['place'] }}</p>@endif
                    @if ($link)<a class="maplink" href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $K['map'] }} →</a>@endif
                </div>
            </li>
        @endforeach
    </ol>
</div></section>
@endif
