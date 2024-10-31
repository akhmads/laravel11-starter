<?php

use Livewire\Volt\Component;

new class extends Component {

    public function mount(): void
    {

    }
}; ?>
<div>
    {{-- HEADER --}}
    <x-header title="Badge" separator />

    <x-card>
        <div class="space-y-4">
            <h1 class="font-bold pt-5">Default</h1>
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.badge value="Default" />
                <x-ui.badge value="Red" color="red" />
                <x-ui.badge value="Green" color="green" />
                <x-ui.badge value="Lime" color="lime" />
                <x-ui.badge value="Blue" color="blue" />
                <x-ui.badge value="Yellow" color="yellow" />
            </div>

            <h1 class="font-bold pt-5">Sizing</h1>
            <div class="flex items-center gap-3">
                <x-ui.badge value="Small" size="sm" />
                <x-ui.badge value="Medium" color="green" />
                <x-ui.badge value="Large" color="red" size="lg" />
            </div>

            <h1 class="font-bold pt-5">Solid</h1>
            <div class="flex items-center gap-3">
                <x-ui.badge value="Default" solid />
                <x-ui.badge value="Red" color="red" solid />
                <x-ui.badge value="Green" color="green" solid />
                <x-ui.badge value="Lime" color="lime" solid />
                <x-ui.badge value="Blue" color="blue" solid />
                <x-ui.badge value="Yellow" color="yellow" solid />
            </div>
        </div>
    </x-card>
</div>
