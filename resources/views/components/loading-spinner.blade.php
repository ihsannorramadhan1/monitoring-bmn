<div x-data="{ loading: false }" x-show="loading" @submit.window="loading = true" @loading-start.window="loading = true"
    @loading-end.window="loading = false" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
    <div class="bg-white p-4 rounded-lg shadow-xl flex items-center space-x-3">
        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        <span class="text-gray-700 font-medium">Processing...</span>
    </div>
</div>

<script>
    // Hook into form submissions globally if needed, or rely on x-data in layout
    document.addEventListener('submit', function (e) {
        // Dispatch event to show spinner
        window.dispatchEvent(new CustomEvent('loading-start'));
    });

    // Handle back button (hide spinner if user navigates back)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.dispatchEvent(new CustomEvent('loading-end'));
        }
    });
</script>