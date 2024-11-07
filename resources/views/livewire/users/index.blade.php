<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\Attributes\Session;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Models\User;

new class extends Component {
    use Toast, WithPagination;

    #[Session(key: 'users_per_page')]
    public int $perPage = 10;

    #[Session(key: 'users_name')]
    public string $name = '';

    public int $filterCount = 0;
    public bool $drawer = false;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function mount(): void
    {
        $this->updateFilterCount();
    }

    public function clear(): void
    {
        $this->warning('Filters cleared');
        $this->reset(['name']);
        $this->resetPage();
        $this->updateFilterCount();
    }

    public function delete(User $user): void
    {
        $user->delete();
        $this->warning("User has been deleted");
    }

    public function headers(): array
    {
        return [
            ['key' => 'avatar', 'label' => 'Avatar', 'sortable' => false],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }

    public function users(): LengthAwarePaginator
    {
        return User::query()
        ->orderBy(...array_values($this->sortBy))
        ->filterLike('name', $this->name)
        ->paginate($this->perPage);
    }

    public function with(): array
    {
        if (!isset($this->satker_id)) {
            $this->satker_id = '';
        }

        return [
            'users' => $this->users(),
            'headers' => $this->headers(),
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
        if (!empty($this->name)) {
            $count++;
        }
        $this->filterCount = $count;
    }
}; ?>

<div>
    {{-- HEADER --}}
    <x-header title="Users" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" badge="{{ $filterCount }}" />
            <x-button label="Create" link="/users/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE --}}
    <x-card>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" with-pagination per-page="perPage" show-empty-text>
            @scope('cell_avatar', $user)
            <x-avatar image="{{ $user->avatar ?? asset('assets/img/default-avatar.png') }}" class="!w-8" />
            @endscope
            @scope('cell_status', $user)
                <x-badge :value="$user->status->value" class="{{ $user->status->color() }}" />
            @endscope
            @scope('actions', $user)
            <div class="flex items-center gap-0">
                <x-button link="users/{{ $user->id }}/edit" icon="o-pencil-square" class="btn-ghost btn-sm text-blue-500" />
                <x-button icon="o-trash" wire:click="delete({{ $user->id }})" wire:confirm="Are you sure?" spinner="delete({{ $user->id }})" class="btn-ghost btn-sm text-red-500" />
            </div>
            @endscope
        </x-table>
    </x-card>

    {{-- FILTER DRAWER --}}
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <div class="grid gap-5">
            <x-input label="Name" wire:model.live.debounce="name" />
        </div>
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
