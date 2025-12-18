<div class="flex items-center justify-center w-full">
    <input
        type="number"
        wire:model.live.debounce.200ms="quantities.{{ $getRecord()->id }}"
        min="1"
        class="w-20 rounded-lg border-gray-300 shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-primary-600 sm:text-sm sm:leading-6"
    />
</div>
