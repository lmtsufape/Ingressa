document.addEventListener('livewire:initialized', () => {
    if (!window.Echo || !window.Livewire) return;

    const userId = document
        .querySelector('meta[name="user-id"]')
        ?.getAttribute('content');

    if (!userId) return;

    window.Echo
        .private(`user.${userId}`)
        .listen('.ZipGerado', (e) => {
            Livewire.dispatchTo(
                'zip-status-listener',
                'zip-gerado',
                { download_url: e.download_url }
            );
        });
});
