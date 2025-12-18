<div style="display: flex; justify-content: end; width: 100%;">
    <input
        type="number"
        wire:model.live.debounce.200ms="quantities.{{ $getRecord()->id }}"
        min="1"
        max="{{ $getRecord()->stock }}"
        @disabled($this instanceof \App\Filament\Resources\Orders\Pages\EditOrder)
        class="block w-48 rounded-lg border-gray-300 shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-primary-600 sm:text-sm sm:leading-6"
    />
</div>
