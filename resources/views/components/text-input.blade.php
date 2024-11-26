<div
    class="relative"
    x-data="{
        suggestions: [],
        query: '',
        showSuggestions: false,
        loading: false,
        activeRequest: null,
        debounceTimer: null,
        fetchSuggestions(fieldName) {
            clearTimeout(this.debounceTimer);
            this.loading = true;
            const currentQuery = this.query;

            this.debounceTimer = setTimeout(() => {
                if (this.activeRequest) {
                    this.activeRequest.abort();
                }

                const controller = new AbortController();
                this.activeRequest = controller;

                fetch(`/api/suggestions?field=${fieldName}&query=${encodeURIComponent(this.query)}`, {
                    signal: controller.signal,
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (currentQuery === this.query) {
                            this.suggestions = data;
                        }
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            console.error('Error fetching suggestions:', error);
                        }
                    })
                    .finally(() => {
                        if (currentQuery === this.query) {
                            this.loading = false;
                        }
                    });
            }, 300); // Debounce delay
        },
        clearInput() {
            this.query = '';
            this.suggestions = [];
            this.loading = false;
            this.showSuggestions = false;
        }
    }"
    @click.away="showSuggestions = false"
>
    <input
        x-ref="input-{{ $name }}"
        type="text"
        placeholder="{{ $placeholder }}"
        name="{{ $name }}"
        value="{{ $value }}"
        id="{{ $name }}"
        x-model="query"
        @input="fetchSuggestions('{{ $name }}')"
        @focus="showSuggestions = true"
        class="w-full rounded-md border-0 py-1.5 px-2.5 pr-8 text-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:ring-2"
    />

    <!-- Clear Button -->
    <button
        type="button"
        class="absolute top-0 right-2 flex h-full items-center"
        @click="clearInput"
        x-show="query.length > 0"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="h-4 w-4 text-slate-500"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Improved Loading Spinner -->
    <div
        x-show="loading"
        class="absolute inset-y-0 right-8 flex items-center"
    >
        <div class="h-3 w-3 rounded-full border-2 border-t-transparent border-blue-500 animate-spin"></div>
    </div>

    <!-- Suggestions dropdown -->
    <div
        x-show="showSuggestions && suggestions.length > 0"
        class="absolute left-0 z-10 mt-1 w-full rounded-md bg-white shadow-lg"
        style="display: none;"
    >
        <ul>
            <template x-for="suggestion in suggestions" :key="suggestion.id">
                <li
                    @click="$refs['input-{{ $name }}'].value = suggestion.text; showSuggestions = false;"
                    class="cursor-pointer px-4 py-2 text-sm hover:bg-slate-100"
                >
                    <span x-text="suggestion.text"></span>
                </li>
            </template>
        </ul>
    </div>
</div>
