<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        @if ($this->appointment)
            <div class="mt-6">
                <x-filament::button type="submit" size="lg">
                    Actualizar Información
                </x-filament::button>
            </div>
        @endif
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>