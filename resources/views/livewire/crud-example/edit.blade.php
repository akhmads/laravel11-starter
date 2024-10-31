<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Post;

new class extends Component {
    use Toast;

    public Post $post;

    public string $title = '';
    public string $date = '';
    public string $body = '';
    public string $status = '';

    public function mount(): void
    {
        $this->fill($this->post);
    }

    public function with(): array
    {
        return [];
    }

    public function save(): void
    {
        $data = $this->validate([
            'title' => 'required',
            'date' => 'required',
            'body' => 'required',
            'status' => 'required',
        ]);

        $this->post->update($data);

        $this->success('Post has been updated.', redirectTo: '/crud-example');
    }
}; ?>
@php
$config = [
    'spellChecker' => false,
    'toolbar' => [
        'heading', 'bold', 'italic', 'strikethrough',
        '|', 'code', 'quote', 'unordered-list', 'ordered-list', 'horizontal-rule',
        '|', 'link', 'upload-image', 'table',
        '|', 'clean-block', 'side-by-side', 'fullscreen'
    ],
];
@endphp
<div>
    <x-header title="Update Post" separator>
        <x-slot:actions>
            <x-button label="Back" link="/crud-example" icon="o-arrow-uturn-left" />
        </x-slot:actions>
    </x-header>
    <x-form wire:submit="save">
        <div class="space-y-4 lg:space-y-0 lg:grid grid-cols-12 gap-4">
            <x-card class="col-span-8">
                <div class="space-y-4">
                    <x-input label="Title" wire:model="title" />
                    <x-markdown label="Body" wire:model="body" :config="$config" />
                </div>
            </x-card>
            <x-card class="col-span-4">
                <div class="space-y-4">
                    <x-input label="Date" wire:model="date" type="date" />
                    <x-select label="Status" :options="\App\Enums\PostStatus::toSelect(true)" wire:model="status" />
                </div>
            </x-card>
        </div>
        <x-slot:actions>
            <x-button label="Cancel" link="/crud-example" />
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
