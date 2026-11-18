{{-- SCHEDULE: timeline cards with icons --}}
@if (count($events))
<section class="cl alt" id="schedule"><div class="cl-wrap">
    <h2 class="cl-h rv" data-v="blur" data-i18n="schedule">{{ $K['schedule'] }}</h2>{!! $orn !!}
    <p class="date-t rv" data-i18n-date="long">{{ $w['dateText'] }}</p>
    <ol class="tl" data-fill>
        <span class="tl-fill" aria-hidden="true"></span>
        @foreach ($events as $i => $e)
            @php $link = $safeLink($e['mapUrl'] ?? null); @endphp
            <li class="rv" data-v="{{ $i % 2 ? 'right' : 'left' }}">
                <span class="nd">{!! $ic($icons[$i % count($icons)], 13) !!}</span>
                <div class="box">
                    <p class="t">{{ $e['time'] ?? '' }}</p>
                    <h3 data-en="{{ $e['title_en'] ?? '' }}">{{ $e['title'] ?? '' }}</h3>
                    @if (!empty($e['place']))<p>{!! $ic('pin', 13) !!} <span data-en="{{ $e['place_en'] ?? '' }}">{{ $e['place'] }}</span></p>@endif
                    @if ($link)<a class="maplink" href="{{ $link }}" target="_blank" rel="noopener noreferrer"><span data-i18n="map">{{ $K['map'] }}</span>&nbsp;→</a>@endif
                </div>
            </li>
        @endforeach
    </ol>
</div></section>
@endif
