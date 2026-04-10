@props(['name', 'label', 'value' => 0])

<div class="w-full max-w-sm relative mt-4">
    <label class="block mb-1 text-sm">{{ $label }}</label>
    <div class="relative">
        <button
            type="button"
            class="decrease-btn absolute right-9 top-1 rounded-md border border-transparent p-1.5 text-center text-sm transition-all hover:bg-main/10 focus:bg-main/10 active:bg-main/10 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
            </svg>
        </button>
        <input
            type="number"
            name="{{ $name }}"
            class="measurement-input w-full bg-transparent placeholder:text-secondary-light/50 text-sm border border-c-border rounded-md pl-3 pr-20 py-2 transition duration-300 ease focus:outline-none focus:border-c-border hover:border-c-border shadow-sm focus:shadow appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
            value="{{ $value }}"
            step="0.1" />
        <button
            type="button"
            class="increase-btn absolute right-1 top-1 rounded-md border border-transparent p-1.5 text-center text-sm transition-all hover:bg-main/10 focus:bg-main/10 active:bg-main/10 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
            </svg>
        </button>
    </div>
</div>