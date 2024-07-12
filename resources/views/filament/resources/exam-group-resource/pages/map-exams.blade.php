<x-filament-panels::page>
<x-filament::section>
<x-slot name="heading">
        Map Exams With Exam Group
    </x-slot>
    <x-filament-panels::form wire:submit='save'>
        {{ $this->form }}
        <x-filament-panels::form.actions :actions="$this->getFormActions()" alignment="end"/>
    </x-filament-panels::form>
</x-filament::section>
<x-filament::section>
<x-slot name="heading">
        Mapped Exams Table
    </x-slot>
        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>


