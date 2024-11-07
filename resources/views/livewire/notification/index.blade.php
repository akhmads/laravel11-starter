<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\Attributes\Session;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Models\Notification;

new class extends Component {
    use Toast, WithPagination;

    #[Session(key: 'notification_per_page')]
    public int $perPage = 10;

    #[Session(key: 'notification_body')]
    public string $body = '';

    #[Session(key: 'notification_opened')]
    public string $opened = '';

    public int $filterCount = 0;
    public bool $drawer = false;
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function mount(): void
    {
        $this->updateFilterCount();
    }

    public function clear(): void
    {
        $this->warning('Filters cleared');
        $this->reset();
        $this->resetPage();
        $this->updateFilterCount();
    }

    public function delete(Notification $notification): void
    {
        $notification->delete();
        $this->warning("Notification has been deleted");
    }

    public function headers(): array
    {
        return [
            ['key' => 'body', 'label' => 'Body', 'sortable' => false],
            ['key' => 'sender_name', 'label' => 'Sender', 'class' => "lg:w-[200px]"],
            ['key' => 'created_at', 'label' => 'Created At', 'class' => "lg:w-[180px]"],
        ];
    }

    public function notifications(): LengthAwarePaginator
    {
        return Notification::query()
        ->where('receiver_id', auth()->user()->id)
        ->withAggregate('sender', 'name')
        ->orderBy(...array_values($this->sortBy))
        ->filterLike('body', $this->body)
        ->filterWhere('opened', $this->opened)
        ->paginate($this->perPage);
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'notifications' => $this->notifications(),
        ];
    }

    public function updated($property): void
    {
        if (! is_array($property) && $property != "") {
            $this->resetPage();
            $this->updateFilterCount();
        }
    }

    public function updateFilterCount(): void
    {
        $count = 0;
        if (!empty($this->body)) {
            $count++;
        }
        if (!empty($this->opened)) {
            $count++;
        }
        $this->filterCount = $count;
    }
}; ?>
<div>
    {{-- HEADER --}}
    <x-header title="Notification" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" badge="{{ $filterCount }}" />
            <x-button label="Create" link="/notification/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE --}}
    @php
    $row_decoration = [
        'font-bold' => fn(Notification $notification) => $notification->opened == '0'
    ];
    @endphp
    <x-card>
        <x-table :headers="$headers" :rows="$notifications" :row-decoration="$row_decoration" :sort-by="$sortBy" with-pagination per-page="perPage" show-empty-text link="notification/{id}/view">
            @scope('cell_body', $notification)
            {{ Str::limit($notification->body, 100, preserveWords: true) }}
            @endscope
            @scope('cell_created_at', $notification)
            {{ \App\Helpers\Cast::date($notification->created_at, 'd-M-Y, H:i') }}
            @endscope
            @scope('actions', $notification)
            <x-button icon="o-trash" wire:click="delete({{ $notification->id }})" spinner="delete({{ $notification->id }})" wire:confirm="Are you sure?" class="btn-ghost btn-sm text-red-500" />
            @endscope
        </x-table>
    </x-card>

    {{-- FILTER DRAWER --}}
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <div class="grid gap-5">
            <x-input label="Body" wire:model.live.debounce="body" />
        </div>
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
