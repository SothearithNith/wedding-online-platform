{{-- DRESS CODE --}}
@if (count($colors))
@php $dcTitle = $w['dressCode']['title'] ?? null; @endphp
<section class="cl alt" id="dresscode"><div class="cl-wrap">
    <h2 class="cl-h rv"
        @if ($dcTitle) data-en="{{ $w['dressCode']['title_en'] ?? '' }}" @else data-i18n="dressCode" @endif>{{ $dcTitle ?: 'ឈុតសម្លៀកបំពាក់' }}</h2>{!! $orn !!}
    <p class="txt rv" data-en="{{ $w['dressCode']['note_en'] ?? '' }}">{{ $w['dressCode']['note'] ?? '' }}</p>
    <div class="sw">@foreach ($colors as $c)<i class="rv" data-v="zoom" style="background:{{ $c }};--d:{{ $loop->index * 90 }}ms"></i>@endforeach</div>
</div></section>
@endif
