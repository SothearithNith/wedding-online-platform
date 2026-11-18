{{-- RSVP --}}
<section class="cl" id="rsvp"><div class="cl-wrap">
    <h2 class="cl-h rv" data-i18n="rsvp">{{ $K['rsvp'] }}</h2>{!! $orn !!}
    <p class="mute rv" data-i18n="rsvpSub">{{ $K['rsvpSub'] }}</p>
    <div class="ok" id="ok" role="status"><b>✓</b><p data-i18n="sent">{{ $K['sent'] }}</p></div>
    <form class="form rv" id="rs" method="POST" action="{{ $rsvpAction }}" novalidate>
        @csrf
        <div><input class="f" id="rs-name" name="guest_name" data-i18n-ph="yourName" placeholder="{{ $K['yourName'] }}" maxlength="60" autocomplete="name" enterkeyhint="next">
            <p class="err-t" id="rs-err" role="alert" hidden data-i18n="required">{{ $K['required'] }}</p></div>
        <input type="hidden" name="attending" id="rs-att" value="1">
        <div class="seg" role="group"><button type="button" class="on" data-v="1" aria-pressed="true" data-i18n="attend">{{ $K['attend'] }}</button><button type="button" data-v="0" aria-pressed="false" data-i18n="decline">{{ $K['decline'] }}</button></div>
        <select class="f" name="guests_count" id="rs-g" data-i18n-aria="guests" aria-label="{{ $K['guests'] }}">
            @for ($n = 1; $n <= 5; $n++)<option value="{{ $n }}" data-i18n="guests" data-n="{{ $n }}">{{ $K['guests'] }}: {{ $n }}</option>@endfor
        </select>
        <textarea class="f" name="message" rows="3" maxlength="200" data-i18n-ph="wish" placeholder="{{ $K['wish'] }}"></textarea>
        <p class="err-t" id="rs-fail" role="alert" hidden style="text-align:center" data-i18n="failed">{{ $K['failed'] }}</p>
        <button class="btn shine" type="submit" id="rs-send" style="justify-self:center">{!! $ic('send', 16) !!} <span data-i18n="send">{{ $K['send'] }}</span></button>
    </form>
</div></section>
