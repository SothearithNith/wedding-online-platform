@php
    $rsvpInvitationId = $invitationId ?? ($invitation->id ?? null);
    $rsvpAction = $rsvpUrl ?? ($rsvpInvitationId ? route('invitations.rsvp', $rsvpInvitationId) : '');
@endphp
<section class="g-sec g-photo" id="rsvp" style="padding:112px 20px">
    <div class="g-bgimg" data-bg="{{ $bg('rsvp') }}"></div>
    <div class="g-ov" style="{{ $overlay(0.08) }}"></div>
    {!! $edge('top', '#ffffff') !!}

    {!! $title($K['rsvp']) !!}

    <div class="g-arch-glass" style="padding:40px 24px">
        <div class="gloss"></div>
        <div class="ts" style="position:relative">
            <p class="rs-sub">{{ $K['rsvpSub'] }}</p>

            <div id="rs-done" class="rs-done" hidden>
                <span class="tick">
                    <span class="kx-ringwave" aria-hidden="true"></span>
                    <span class="kx-ringwave b" aria-hidden="true" style="inset:0"></span>
                    <b>{!! $ic('check', 32) !!}</b>
                </span>
                <p style="margin-top:20px;font-size:14px;line-height:1.7">{{ $K['sent'] }}</p>
            </div>

            <form id="rs-form" class="rs-form" method="POST" action="{{ $rsvpAction }}" novalidate>
                @csrf
                <div>
                    <input class="rs-field h" id="rs-name" name="guest_name" placeholder="{{ $K['yourName'] }}"
                        maxlength="60" autocomplete="name">
                    <p class="rs-err" id="rs-err" hidden>{{ $K['required'] }}</p>
                </div>

                <input type="hidden" name="attending" id="rs-attending" value="1">
                <div class="rs-toggle">
                    <button type="button" class="on" data-v="1"
                        aria-pressed="true">{{ $K['attend'] }}</button>
                    <button type="button" data-v="0" aria-pressed="false">{{ $K['decline'] }}</button>
                </div>

                <label id="rs-guests-wrap" style="display:block;font-size:14px">
                    <span class="rs-label">{{ $K['guests'] }}</span>
                    <select class="rs-field h" name="guests_count" id="rs-guests">
                        @for ($n = 1; $n <= 5; $n++)
                            <option value="{{ $n }}">{{ $n }}</option>
                        @endfor
                    </select>
                </label>

                <textarea class="rs-field" name="message" id="rs-msg" rows="3" maxlength="200"
                    placeholder="{{ $K['wish'] }}"></textarea>

                <p class="rs-err" id="rs-fail" hidden style="text-align:center">{{ $K['failed'] }}</p>

                <button class="btn-foil rs-send title-font" type="submit" id="rs-send">{!! $ic('send', 16) !!}
                    {{ $K['send'] }}</button>
            </form>​
        </div>
    </div>
    {!! $edge('bottom', '#fbf6ea') !!}
</section>
