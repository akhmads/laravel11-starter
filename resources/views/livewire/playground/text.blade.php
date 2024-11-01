<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public string $index = '';
    public string $text = '';

    public function mount($index = '', $body = '[]'): void
    {
        $data = json_decode(base64_decode($body));
        $this->index = $index;
        $this->text = $data->text ?? '';
    }

    public function with(): array
    {
        return [];
    }

    public function save(): void
    {
        $data = $this->validate([
            'text' => 'required|min:1',
        ]);

        $body = base64_encode(json_encode([ 'text' => $this->text ]));
        $this->dispatch('block-updated', index: $this->index, body: $body);
    }

    public function delete(): void
    {
        $this->dispatch('block-deleted', index: $this->index);
    }
}; ?>
<div>
    <x-header title="Text" separator class="mb-2" />
    <x-form wire:submit="save">
        <div class="space-y-4">
            <x-textarea label="Text" wire:model="text" />
        </div>
        <x-slot:actions>
            <x-button label="Delete" wire:click="delete" wire:confirm="Are you sure?" icon="o-x-mark" spinner="delete" class="btn-error" />
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
