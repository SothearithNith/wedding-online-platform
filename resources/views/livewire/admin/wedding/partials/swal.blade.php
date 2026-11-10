{{-- Toast helper. Include once, inside the Livewire component root. --}}
<script>
    (() => {
        const toast = (type, title, text = '') => {
            if (typeof Swal === 'undefined') {
                console.warn('SweetAlert2 (Swal) is not loaded:', type, title, text);
                return;
            }

            Swal.fire({
                icon: type,
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                timer: 3500,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        };

        // $this->dispatch('swal', type: 'success', title: '...', text: '...')
        $wire.on('swal', (e) => {
            const d = Array.isArray(e) ? e[0] : e;
            toast(d.type ?? 'info', d.title ?? '', d.text ?? '');
        });

        // session()->flash('success' | 'error', '...') after a redirect
        @if (session('success'))
            toast('success', @js(session('success')));
        @endif

        @if (session('error'))
            toast('error', @js(session('error')));
        @endif
    })();
</script>
