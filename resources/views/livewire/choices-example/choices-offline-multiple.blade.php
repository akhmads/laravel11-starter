<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use App\Models\Country;

new class extends Component {

    public array $country_id = [];
    public Collection $countrySearchable;

    public function mount(): void
    {
        $this->searchCountry();
    }

    public function save(): void
    {
        $data = $this->validate([
            'country_id' => 'required|array|min:2',
        ]);
    }

    public function searchCountry(): void
    {
        $this->countrySearchable = Country::orderBy('name')->get();
    }
}; ?>

<div>
    <x-header title="Choices Offline Multiple" separator />
    <div class="space-y-8 lg:max-w-screen-md">
        <x-form wire:submit="save">
            <x-card title="Choice offline Multiple" separator>
                <div class="space-y-4">
                    <x-choices-offline label="Country" wire:model="country_id" :options="$countrySearchable" option-value="id" option-label="name" searchable />
                </div>
                <x-slot:actions>
                    <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
                </x-slot:actions>
            </x-card>
        </x-form>
        <x-card>
            Selected ID : <span class="font-semibold">{{ @implode(', ', $country_id) }}</span>
        </x-card>
    </div>
</div>
