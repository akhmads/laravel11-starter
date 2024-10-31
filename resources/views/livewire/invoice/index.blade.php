<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\Attributes\Session;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Models\Invoice;

new class extends Component {
    use Toast, WithPagination;

    #[Session(key: 'invoice_per_page')]
    public int $perPage = 10;

    #[Session(key: 'invoice_number')]
    public string $number = '';

    #[Session(key: 'invoice_note')]
    public string $note = '';

    #[Session(key: 'invoice_date')]
    public string $date = '';

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

    public function delete(Invoice $invoice): void
    {
        $invoice->items()->delete();
        $invoice->delete();
        $this->warning("Invoice has been deleted");
    }

    public function headers(): array
    {
        return [
            ['key' => 'number', 'label' => 'Number'],
            ['key' => 'note', 'label' => 'Note', 'sortable' => false],
            ['key' => 'total', 'label' => 'Total', 'class' => 'text-right'],
            ['key' => 'date', 'label' => 'Date', 'class' => "lg:w-[150px]"],
            // ['key' => 'status', 'label' => 'Status', 'class' => "lg:w-[160px]"],
        ];
    }

    public function invoices(): LengthAwarePaginator
    {
        return Invoice::query()
        ->orderBy(...array_values($this->sortBy))
        ->filterLike('number', $this->number)
        ->filterLike('note', $this->note)
        ->paginate($this->perPage);
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'invoices' => $this->invoices(),
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
        if (!empty($this->number)) {
            $count++;
        }
        if (!empty($this->note)) {
            $count++;
        }
        $this->filterCount = $count;
    }
}; ?>
<div>
    {{-- HEADER --}}
    <x-header title="Invoice" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" badge="{{ $filterCount }}" />
            <x-button label="Create" link="/invoice/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE  --}}
    <x-card>
        <x-table :headers="$headers" :rows="$invoices" :sort-by="$sortBy" with-pagination per-page="perPage" show-empty-text link="invoice/{id}/edit">
            @scope('cell_total', $invoice)
            {{ \App\Helpers\Cast::money($invoice->total, 2) }}
            @endscope
            @scope('cell_date', $invoice)
            {{ \App\Helpers\Cast::date($invoice->date) }}
            @endscope
            @scope('actions', $invoice)
            <x-button icon="o-trash" wire:click="delete('{{ $invoice->id }}')" spinner="delete('{{ $invoice->id }}')" wire:confirm="Are you sure?" class="btn-ghost btn-sm text-red-500" />
            @endscope
        </x-table>
    </x-card>

    {{-- FILTER DRAWER --}}
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <div class="grid gap-5">
            <x-input label="Number" wire:model.live.debounce="number" />
            <x-input label="Note" wire:model.live.debounce="note" />
            {{-- <x-select label="Status" wire:model.live="status" :options="\App\Enums\InvoiceStatus::toSelect(true)" /> --}}
        </div>
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner="clear" />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
