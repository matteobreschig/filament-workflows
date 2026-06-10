<x-filament::page>

    {{ $this->form }}

</x-filament::page>

@script
<script>
    const scrollToBottom = (id) => {
        const el = document.getElementById(id);
        if (el) {
            el.scrollTop = el.scrollHeight;
        }
    };

    setTimeout(() => {
        scrollToBottom('logs');
        scrollToBottom('execution_logs');
    }, 200);
</script>
@endscript
