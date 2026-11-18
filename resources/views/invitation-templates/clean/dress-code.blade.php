{{-- DRESS CODE --}}
@if (count($colors))
<section class="cl alt" id="dresscode"><div class="cl-wrap">
    <h2 class="cl-h rv">{{ $w['dressCode']['title'] ?? 'ឈុតសម្លៀកបំពាក់' }}</h2>{!! $orn !!}
    <p class="txt rv">{{ $w['dressCode']['note'] ?? '' }}</p>
    <div class="sw">@foreach ($colors as $c)<i class="rv" style="background:{{ $c }};--d:{{ $loop->index * 90 }}ms"></i>@endforeach</div>
</div></section>
@endif
