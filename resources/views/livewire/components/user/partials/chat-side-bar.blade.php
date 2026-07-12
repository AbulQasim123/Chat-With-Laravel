

@push('scripts')
    <script>
        let newChatModalInstance;

        window.addEventListener('show-new-chat-modal', () => {
            const modalEl = document.getElementById('newChat');

            // Destroy any previous modal instance if it exists
            if (newChatModalInstance) {
                newChatModalInstance.hide();
                newChatModalInstance.dispose();
            }

            // Create a new instance and show it
            newChatModalInstance = new bootstrap.Modal(modalEl, {
                backdrop: 'static',
                keyboard: false
            });

            newChatModalInstance.show();

            // Clean up redundant backdrops if any
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 0) {
                    // Remove all except the last
                    for (let i = 0; i < backdrops.length - 0; i++) {
                        backdrops[i].remove();
                    }
                }
            }, 300); // Give Bootstrap time to create the backdrop
        });
    </script>
@endpush
