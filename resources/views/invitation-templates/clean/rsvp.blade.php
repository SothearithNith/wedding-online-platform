{{-- RSVP --}}
<section class="cl" id="rsvp"><div class="cl-wrap">
    <h2 class="cl-h rv">{{ $K['rsvp'] }}</h2>{!! $orn !!}
    <p class="mute rv">{{ $K['rsvpSub'] }}</p>
    <div class="ok" id="ok"><b>✓</b><p>{{ $K['sent'] }}</p></div>
    <form class="form rv" id="rs" method="POST" action="{{ $rsvpAction }}" novalidate>
        @csrf
        <div><input class="f" id="rs-name" name="guest_name" placeholder="{{ $K['yourName'] }}" maxlength="60" autocomplete="name">
            <p class="err-t" id="rs-err" hidden>{{ $K['required'] }}</p></div>
        <input type="hidden" name="attending" id="rs-att" value="1">
        <div class="seg"><button type="button" class="on" data-v="1">{{ $K['attend'] }}</button><button type="button" data-v="0">{{ $K['decline'] }}</button></div>
        <select class="f" name="guests_count" id="rs-g" aria-label="{{ $K['guests'] }}">
            @for ($n = 1; $n <= 5; $n++)<option value="{{ $n }}">{{ $K['guests'] }}: {{ $n }}</option>@endfor
        </select>
        <textarea class="f" name="message" rows="3" maxlength="200" placeholder="{{ $K['wish'] }}"></textarea>
        <p class="err-t" id="rs-fail" hidden style="text-align:center">{{ $K['failed'] }}</p>
        <button class="btn" type="submit" id="rs-send" style="justify-self:center">{!! $ic('send', 16) !!} {{ $K['send'] }}</button>
    </form>
</div></section>
