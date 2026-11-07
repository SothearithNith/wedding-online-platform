{{-- ============================ DRESS CODE ============================ --}}
@if (count($colors))
    <section class="g-sec g-paper" id="dresscode" style="padding:80px 24px;text-align:center">
        <div class="g-chakra"></div>
        {!! $title($w['dressCode']['title'] ?? 'ឈុតសម្លៀកបំពាក់', 'dark') !!}
        <p class="rv" style="max-width:20rem;margin:0 auto;font-size:14px;line-height:1.7;opacity:.8">
            {{ $w['dressCode']['note'] ?? '' }}</p>
        <div class="dc-colors">
            @foreach ($colors as $i => $c)
                <div class="rv" data-v="zoom" data-delay="{{ $i * 100 }}">
                    <span class="dc-sw"><span style="background:{{ $c }}"></span></span>
                </div>
            @endforeach
        </div>
    </section>
@endif
