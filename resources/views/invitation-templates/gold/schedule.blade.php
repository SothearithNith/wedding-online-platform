{{-- ============================ SCHEDULE ============================ --}}
@if (count($events))
    @php $schedBg = $bg('schedule'); @endphp
    <section class="g-sec" id="schedule" style="padding:112px 20px;background:#1b1209">
        <div class="g-bgimg" data-bg="{{ $schedBg }}"></div>
        <div class="g-ov" style="background:rgba(0,0,0,.08)"></div>
        <div class="g-ov"
            style="background:linear-gradient(180deg,#1b1209 0%,transparent 12%,transparent 88%,#1b1209 100%);pointer-events:none">
        </div>

        <div class="g-in" style="z-index:1">
            <div class="g-arch-glass"
                style="padding:40px 24px 48px;{{ $schedBg ? '' : 'background:rgba(27,18,9,.92)' }}">
                <div class="gloss"></div>
                <div style="position:absolute;inset:10px;border:1px solid rgba(246,227,160,.25);border-radius:2.5rem;pointer-events:none"
                    aria-hidden="true">
                    <span class="dia" style="position:absolute;left:-3px;top:50%;margin-top:-3px"></span>
                    <span class="dia" style="position:absolute;right:-3px;top:50%;margin-top:-3px"></span>
                    <span class="dia" style="position:absolute;left:50%;top:-3px;margin-left:-3px"></span>
                    <span class="dia" style="position:absolute;left:50%;bottom:-3px;margin-left:-3px"></span>
                </div>

                <div class="ts" style="position:relative">
                    {!! $title($K['schedule']) !!}

                    <div class="rv" data-v="rise">
                        <div class="sc-date">
                            <span class="badge ts-none">{!! $ic('cal', 20) !!}</span>
                            <p class="font-khmer js-date" data-iso="{{ $w['date'] }}"
                                style="font-size:14px;line-height:1.7;color:#f6e3a0">{{ $w['dateText'] }}</p>
                            <div class="dl" aria-hidden="true"><span class="l1"></span><span
                                    class="dia"></span><span class="l2"></span></div>
                        </div>
                    </div>

                    <ol class="ev-list" id="ev-list">
                        <span class="ev-track" aria-hidden="true"></span>
                        <span class="ev-fill" id="ev-fill" aria-hidden="true"></span>

                        @foreach ($events as $i => $e)
                            @php $link = $safeLink($e['mapUrl'] ?? null); @endphp
                            <li data-i="{{ $i }}">
                                <span class="ev-node ts-none">{!! $ic($icons[$i % count($icons)], 16) !!}</span>
                                <div class="rv" data-v="{{ $i % 2 ? 'right' : 'left' }}"
                                    data-delay="{{ $i * 80 }}">
                                    <div class="ev-card">
                                        <span class="edge" aria-hidden="true"></span>
                                        <div>
                                            <p class="ev-time tnum">{{ $e['time'] ?? '' }}</p>
                                            <div class="dl" style="margin-top:12px" aria-hidden="true">
                                                <span class="l1" style="width:32px"></span><span class="dia"
                                                    style="width:4px;height:4px"></span>
                                            </div>
                                            <h3 class="font-khmer">{{ $e['title'] ?? '' }}</h3>
                                            @if (!empty($e['place']))
                                                <p class="ev-place font-khmer">
                                                    {!! $ic('pin', 14) !!}<span>{{ $e['place'] }}</span></p>
                                            @endif
                                            @if ($link)
                                                <a class="btn-foil ev-map" href="{{ $link }}" target="_blank"
                                                    rel="noopener noreferrer">{!! $ic('pin', 14) !!}
                                                    {{ $K['map'] }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                        <li aria-hidden="true" class="ev-end" id="ev-end"><span>{!! $ic('heart', 12) !!}</span></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endif
