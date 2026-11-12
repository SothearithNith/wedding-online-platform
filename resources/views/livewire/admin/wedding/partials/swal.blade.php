{{-- Flash-message toasts. Include once, inside the Livewire component root. --}}
@script
<script>
    @if (session('success'))
        window.swalToast('success', @js(session('success')));
    @endif

    @if (session('error'))
        window.swalToast('error', @js(session('error')));
    @endif
</script>
@endscript
