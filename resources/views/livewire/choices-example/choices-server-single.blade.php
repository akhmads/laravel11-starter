<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use App\Models\Country;

new class extends Component {

    public string $country_id = '';
    public Collection $countrySearchable;

    public function mount(): void
    {
        $this->searchCountry();
    }

    public function save(): void
    {
        $data = $this->validate([
            'country_id' => 'required',
        ]);
    }

    public function searchCountry(string $value = ''): void
    {
        $selectedOption = Country::where('id', intval($this->country_id))->get();
        $this->countrySearchable = Country::query()
            ->filterLike('name', $value)
            ->take(20)
            ->get()
            ->merge($selectedOption);
    }

    public function setValue(): void
    {
        $this->country_id = 2;
    }
}; ?>

<div>
    <x-header title="Choices" separator />
    <div class="space-y-8 lg:max-w-screen-md">
        <x-form wire:submit="save">
            <x-card title="Choice Server" separator>
                <div class="space-y-4">
                    <x-choices label="Country" wire:model="country_id" :options="$countrySearchable" search-function="searchCountry" option-value="id" option-label="name" single searchable />
                </div>
                <x-slot:actions>
                    <x-button label="Set Value" icon="o-funnel" wire:click="setValue" spinner="setValue" class="" />
                    <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
                </x-slot:actions>
            </x-card>
        </x-form>

        <x-card>
            Selected ID : <span class="font-semibold">{{ $country_id }}</span>
        </x-card>
    </div>
</div>
