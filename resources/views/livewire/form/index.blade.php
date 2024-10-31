<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public string $name;
    public string $address;
    public string $user_id;

    public function with(): array
    {
        return [];
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|max:100',
            'address' => 'nullable',
            'user_id' => 'nullable',
        ]);

        $this->success('Form submitted.');
    }
}; ?>
<div>
    <x-header title="Form" separator />

    <div class="lg:max-w-screen-md">
        <x-form wire:submit="save">
            <x-card>
                <div class="space-y-4">
                    <x-input label="Name" wire:model="name" />
                    <x-textarea label="Address" wire:model="address" />
                    <x-select label="Users" :options="\App\Models\User::get()" wire:model="user_id" option-value="id" option-label="name" placeholder="-- Select --" />
                </div>
            </x-card>

            <x-slot:actions>
                <x-button label="Cancel" link="/form/index" />
                <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </div>
</div>
