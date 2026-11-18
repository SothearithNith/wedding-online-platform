@php $variant = $variant ?? 'ornament'; @endphp
{{-- ornament: gold line + lotus | band: flowing kbach strip --}}
@if ($variant === 'band')
    <div class="cl-kbach" aria-hidden="true"></div>
@else
    <div class="cl-div rv" data-v="zoom" aria-hidden="true"><span></span>{!! $lotus(12, 40, 'cl-spin', 1.1) !!}<span></span></div>
@endif
