<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Notification;
use App\Models\User;

new class extends Component {
    use Toast;

    public ?string $from = '';
    public ?array $recipient = [];
    public ?string $body = '';

    public function mount(): void
    {
        $this->from = auth()->user()->name;
    }

    public function with(): array
    {
        return [];
    }

    public function save(): void
    {
        $data = $this->validate([
            'recipient' => 'required',
            'body' => 'required',
        ]);

        $data['sender_id'] = auth()->user()->id;
        $data['opened'] = 0;

        unset($data['recipient']);

        $sent = 0;
        if (count($this->recipient) > 0)
        {
            foreach ($this->recipient as $recipient)
            {
                if (strtolower($recipient) == 'all')
                {
                    $users = User::where('id', '<>', auth()->user()->id)->get();

                    foreach ($users as $user) {
                        $data['receiver_id'] = $user->id;

                        if ( Notification::create($data) ) {
                            $sent++;
                        }
                    }
                }
            }
        }

        $this->success('Notification has been sent ('.$sent.')', redirectTo: '/notification');
    }
}; ?>
@php
$config = [
    'spellChecker' => false,
    'toolbar' => [
        'heading', 'bold', 'italic', 'strikethrough',
        '|', 'code', 'quote', 'unordered-list', 'ordered-list', 'horizontal-rule',
        '|', 'link', 'upload-image', 'table',
        '|', 'clean-block', 'fullscreen'
    ],
];
@endphp
<div>
    <x-header title="Create Notification" separator>
        <x-slot:actions>
            <x-button label="Back" link="/notification" icon="o-arrow-uturn-left" />
        </x-slot:actions>
    </x-header>
    <x-form wire:submit="save">
        <x-card>
            <div class="space-y-4">
                <div class="space-y-4 lg:space-y-0 lg:grid grid-cols-2 gap-4">
                    <x-tags label="Recipient" wire:model="recipient" />
                    <x-input label="From" wire:model="from" readonly />
                </div>
                <x-markdown label="Body" wire:model="body" :config="$config" />
            </div>
        </x-card>
        <x-slot:actions>
            <x-button label="Cancel" link="/notification" />
            <x-button label="Send" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
