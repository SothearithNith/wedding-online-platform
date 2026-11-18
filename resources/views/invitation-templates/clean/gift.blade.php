{{-- GIFT --}}
@if (!empty($gift['accountNumber']))
<section class="cl alt" id="gift"><div class="cl-wrap">
    <h2 class="cl-h rv">{{ $K['gift'] }}</h2>{!! $orn !!}
    <p class="txt rv">{{ $K['giftSub'] }}</p>
    <div class="card rv" style="margin-top:26px">
        <img src="{{ $giftQr }}" alt="QR" onerror="this.style.display='none'">
        <p class="mute" style="margin:8px 0 0">{{ $K['scan'] }}</p>
        <p style="margin:12px 0 0;font-weight:700">{{ $gift['bank'] ?? '' }}</p>
        <p class="mute" style="margin:0">{{ $gift['accountName'] ?? '' }}</p>
        <p class="no" id="acc">{{ $gift['accountNumber'] }}</p>
        <button class="btn line" type="button" id="copy">{!! $ic('copy', 16) !!} <span id="copy-t">{{ $K['copy'] }}</span></button>
    </div>
</div></section>
@endif
