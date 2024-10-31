<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\Attributes\Session;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Models\Post;

new class extends Component {
    use Toast, WithPagination;

    #[Session(key: 'post_per_page')]
    public int $perPage = 10;

    #[Session(key: 'post_title')]
    public string $title = '';

    #[Session(key: 'post_body')]
    public string $body = '';

    #[Session(key: 'post_status')]
    public string $status = '';

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

    public function delete(Post $post): void
    {
        $post->delete();
        $this->warning("Post has been deleted");
    }

    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'body', 'label' => 'Body', 'sortable' => false],
            ['key' => 'date', 'label' => 'Date', 'class' => "lg:w-[160px]"],
            ['key' => 'status', 'label' => 'Status', 'class' => "lg:w-[160px]"],
        ];
    }

    public function posts(): LengthAwarePaginator
    {
        return Post::query()
        ->orderBy(...array_values($this->sortBy))
        ->filterLike('title', $this->title)
        ->filterWhere('status', $this->status)
        ->paginate($this->perPage);
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'posts' => $this->posts(),
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
        if (!empty($this->title)) {
            $count++;
        }
        if (!empty($this->status)) {
            $count++;
        }
        $this->filterCount = $count;
    }

    public function fake()
    {
        Post::factory(3)->create();
    }
}; ?>
<div>
    {{-- HEADER --}}
    <x-header title="Post" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" badge="{{ $filterCount }}" />
            <x-button label="Fake" wire:click="fake" spinner="fake" responsive icon="o-plus" />
            <x-button label="Create" link="/crud-example/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE  --}}
    <x-card>
        <x-table :headers="$headers" :rows="$posts" :sort-by="$sortBy" with-pagination per-page="perPage" show-empty-text link="crud-example/{id}/edit">
            @scope('cell_body', $post)
            {{ Str::limit($post->body, 100, preserveWords: true) }}
            @endscope
            @scope('cell_status', $post)
            <x-badge :value="$post->status->value" class="text-xs uppercase {{ $post->status->color() }}" />
            @endscope
            @scope('cell_date', $post)
            {{ \App\Helpers\Cast::date($post->date) }}
            @endscope
            @scope('actions', $post)
            <x-button icon="o-trash" wire:click="delete({{ $post->id }})" spinner="delete({{ $post->id }})" wire:confirm="Are you sure?" class="btn-ghost btn-sm text-red-500" />
            @endscope
        </x-table>
    </x-card>

    {{-- FILTER DRAWER --}}
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <div class="grid gap-5">
            <x-input label="Title" wire:model.live.debounce="title" />
            <x-select label="Status" wire:model.live="status" :options="\App\Enums\PostStatus::toSelect(true)" />
        </div>
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
