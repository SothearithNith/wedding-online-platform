@php $variant = $variant ?? 'ornament'; @endphp
{{-- ornament: breathing space + diamond | band: flowing kbach strip --}}
@if ($variant === 'band')
    <div class="cl-kbach rv" aria-hidden="true"></div>
@else
    <div class="cl-div rv" aria-hidden="true"><span></span><i></i><span></span></div>
@endif
