<div style="display: flex; justify-content: center; width: 100%;">
    <input
        type="checkbox"
        wire:model.live="selectedProductIds.{{ $getRecord()->id }}"
        class="fi-checkbox-input rounded border-gray-300 text-primary-600 shadow-sm outline-none transition duration-75 focus:ring-2 focus:ring-primary-600 disabled:pointer-events-none disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current dark:border-white/10 dark:bg-white/5 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:disabled:bg-transparent"
    />
</div>
