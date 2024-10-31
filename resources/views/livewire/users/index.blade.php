<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Traits\TableHelper;
use App\Models\User;

new class extends Component {
    use Toast, WithPagination, TableHelper;

    public int $perPage = 10;
    public string $search = '';
    public bool $drawer = false;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public string $role = '';
    public string $status = '';

    // Clear filters
    public function clear(): void
    {
        $this->warning('Filters cleared');
        $this->reset(['search','role','status']);
        $this->resetPage();
    }

    // Delete action
    public function delete(User $user): void
    {
        $user->delete();
        $this->warning("User has been deleted");
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'avatar', 'label' => 'Avatar', 'sortable' => false],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }

    public function users(): LengthAwarePaginator
    {
        return User::query()
        ->orderBy(...array_values($this->sortBy))
        ->filterLike('name', $this->search)
        ->filterWhere('role', $this->role)
        ->filterWhere('status', $this->status)
        ->paginate($this->perPage);
    }

    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers(),
            'pageList' => $this->pageList(),
        ];
    }

    public function updated($property): void
    {
        if (! is_array($property) && $property != "") {
            $this->resetPage();
        }
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Users" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <div class="flex gap-4 items-center">
                <x-select wire:model.live="perPage" :options="$pageList" />
                <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
            </div>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
            <x-button label="Create" link="/cp/users/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" link="/cp/users/{id}/edit" with-pagination>
            @scope('cell_avatar', $user)
            <x-avatar image="{{ $user->avatar ?? asset('assets/img/default-avatar.png') }}" class="!w-8" />
            @endscope
            @scope('cell_status', $user)
                <x-badge :value="$user->status->value" class="{{ $user->status->color() }}" />
            @endscope
            @scope('actions', $user)
            <div class="flex items-center gap-0">
                <x-button link="/cp/users/{{$user['id']}}/edit" icon="o-pencil-square" class="btn-ghost btn-sm text-blue-500" />
                <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner="delete({{ $user['id'] }})" class="btn-ghost btn-sm text-red-500" />
            </div>
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <div class="grid gap-5">
            <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />
            <x-select label="Role" wire:model.live="role" :options="\App\Enums\Role::toSelect(true)" />
            <x-select label="Status" wire:model.live="status" :options="\App\Enums\ActiveStatus::toSelect(true)" />
        </div>

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
