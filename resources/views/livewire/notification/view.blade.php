<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Notification;

new class extends Component {
    use Toast;

    public Notification $notification;

    public string $body = '';

    public function mount(): void
    {
        $this->fill($this->notification);
    }

    public function with(): array
    {
        return [];
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
    <x-header title="View Notification" separator>
        <x-slot:actions>
            <x-button label="Back" link="/notification" icon="o-arrow-uturn-left" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <div class="font-semibold">
            From : {{ $notification->sender->name }}
        </div>
        <x-hr />
        <div class="prose">
        {!! \Illuminate\Support\Str::markdown($notification->body) !!}
        </div>
    </x-card>
</div>
