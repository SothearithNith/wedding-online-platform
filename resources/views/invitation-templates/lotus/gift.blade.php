@if (!empty($gift['accountNumber']))
    <section class="g-sec g-photo" id="gift" style="padding:112px 20px;text-align:center">
        <div class="g-bgimg" data-bg="{{ $bg('gift') }}"></div>
        <div class="g-ov" style="{{ $overlay(0.08) }}"></div>
        <div class="petals" aria-hidden="true">
            @for ($i = 0; $i < 12; $i++)
                <i
                    style="left:{{ ($i * 43) % 100 }}%;animation-duration:{{ 10 + ($i % 5) * 2.5 }}s;animation-delay:{{ ($i % 7) * 1.6 }}s;--dx:{{ (($i % 5) - 2) * 36 }}px;--rot:{{ ($i % 2 ? 1 : -1) * (240 + $i * 20) }}deg"></i>
            @endfor
        </div>

        <div class="g-arch-glass">
            <div class="gloss"></div>
            {!! $title($K['gift']) !!}
            <p class="kf-sub rv ts" style="color:rgba(255,255,255,.9);opacity:1">{{ $K['giftSub'] }}</p>

            <div class="rv" data-delay="150">
                <div class="gold-card float">
                    <div class="gold-card-in">
                        {!! $corner('tl', 'gcn') !!}{!! $corner('tr', 'gcn') !!}{!! $corner('bl', 'gcn') !!}{!! $corner('br', 'gcn') !!}
                        <div class="kf-qr"><img src="{{ $giftQr }}" alt="QR Code"
                                onerror="this.closest('.kf-qr').style.display='none'"></div>
                        <p class="kf-scan">{{ $K['scan'] }}</p>
                        <p class="kf-bank">{{ $gift['bank'] ?? '' }}</p>

                        <p class="kf-label">{{ $K['accName'] }}</p>
                        <p class="kf-name">{{ $gift['accountName'] ?? '' }}</p>

                        <div class="kf-divider" aria-hidden="true"><span></span><b></b><span></span></div>

                        <p class="kf-label">{{ $K['accNo'] }}</p>
                        <p class="kf-num tnum" id="acc-num">{{ $gift['accountNumber'] }}</p>

                        <button type="button" class="kf-btn" id="copy-btn">
                            <span id="copy-ic">{!! $ic('copy', 16) !!}</span>
                            <span id="copy-tx">{{ $K['copy'] }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
