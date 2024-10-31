<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use App\Models\Country;

new class extends Component {

    public string $country_id = '';

    public function save(): void
    {
        $data = $this->validate([
            'country_id' => 'required',
        ]);
    }
}; ?>

<div>
    <x-header title="Choices Offline Single" separator />
    <div class="space-y-8 lg:max-w-screen-md">
        <x-form wire:submit="save">
            <x-card title="Choice offline Single" separator>
                <div class="space-y-4">
                    <x-choices-offline
                        label="Country"
                        wire:model="country_id"
                        :options="\App\Models\Country::orderBy('name')->get()"
                        option-value="id"
                        option-label="name"
                        single searchable
                    />
                </div>
                <x-slot:actions>
                    <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
                </x-slot:actions>
            </x-card>
        </x-form>
        <x-card>
            Selected ID : <span class="font-semibold">{{ $country_id }}</span>
        </x-card>
    </div>
</div>
