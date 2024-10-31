<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use App\Models\Country;

new class extends Component {

    public $country_ids = [];
    public Collection $countrySearchable;

    public function mount(): void
    {
        $this->searchCountry();
    }

    public function save(): void
    {
        $data = $this->validate([
            'country_ids' => 'required|array|min:2',
        ]);
    }

    public function searchCountry(string $value = ''): void
    {
        $selectedOption = Country::whereIn('id', $this->country_ids)->get();
        $this->countrySearchable = Country::query()
            ->filterLike('name', $value)
            ->take(20)
            ->get()
            ->merge($selectedOption);
    }
}; ?>

<div>
    <x-header title="Choices" separator />
    <div class="space-y-8 lg:max-w-screen-md">
        <x-form wire:submit="save">
            <x-card title="Choice Server" separator>
                <div class="space-y-4">
                    <x-choices label="Country" wire:model="country_ids" :options="$countrySearchable" search-function="searchCountry" option-value="id" option-label="name" searchable />
                </div>
                <x-slot:actions>
                    <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
                </x-slot:actions>
            </x-card>
        </x-form>

        <x-card>
            Selected ID : <span class="font-semibold">{{ @implode(', ', $country_ids) }}</span>
        </x-card>
    </div>
</div>
