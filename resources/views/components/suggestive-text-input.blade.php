<div
    class="relative"
    x-data="{
        suggestions: [],
        query: '{{ $value ?? '' }}',
        showSuggestions: false,
        loading: false,
        debounceTimer: null,
        async fetchSuggestions() {
            clearTimeout(this.debounceTimer);
            this.loading = true;

            this.debounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/suggestions?field={{ $name }}&query=${encodeURIComponent(this.query)}`);
                    if (!response.ok) throw new Error('Failed to fetch suggestions');
                    this.suggestions = await response.json();
                } catch (error) {
                    console.error(error);
                } finally {
                    this.loading = false;
                }
            }, 300);
        }
    }"
    @click.away="showSuggestions = false"
>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        x-model="query"
        @input="fetchSuggestions"
        @focus="showSuggestions = true"
        class="w-full rounded-md border-0 py-1.5 px-2.5 text-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:ring-2"
    />

    <!-- Loading Spinner -->
    <div
        x-show="loading"
        class="absolute inset-y-0 right-8 flex items-center"
    >
        <div class="h-3 w-3 rounded-full border-2 border-t-transparent border-blue-500 animate-spin"></div>
    </div>

    <!-- Suggestions Dropdown -->
    <div
        x-show="showSuggestions && suggestions.length > 0"
        class="absolute left-0 z-10 mt-1 w-full rounded-md bg-white shadow-lg"
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
