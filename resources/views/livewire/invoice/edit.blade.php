<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Helpers\Cast;
use App\Models\Invoice;

new class extends Component {
    use Toast;

    public Invoice $invoice;

    public string $number = '';
    public string $note = '';
    public string $date = '';

    public Collection $items;
    public string $description;
    public string $price;
    public string $qty;

    public string $mode = '';
    public string $selected = '';
    public bool $drawer = false;

    public function mount(): void
    {
        $this->items = collect([]);
        $this->fill($this->invoice);

        foreach ($this->invoice->items as $item){
            $this->items->push((object)[
                'description' => $item->description,
                'price' => $item->price,
                'qty' => $item->qty,
                'subtotal' => $item->subtotal,
            ]);
        }
    }

    public function with(): array
    {
        return [];
    }

    public function save(): void
    {
        $data = $this->validate([
            'number' => 'required|max:50|unique:invoices,number,'.$this->invoice->id,
            'date' => 'required|date',
            'note' => 'nullable',
            'items' => 'array|min:1',
        ]);

        unset($data['items']);

        $this->invoice->items()->delete();
        $this->items->each(function ($item, $key) {
            $this->invoice->items()->create([
                'description' => $item->description,
                'price' => $item->price,
                'qty' => $item->qty,
                'subtotal' => $item->subtotal,
            ]);
        });

        $data['total'] = $this->items->sum('subtotal');
        $this->invoice->update($data);

        $this->success('Invoice has been updated.', redirectTo: '/invoice');
    }

    public function clearForm(): void
    {
        $this->selected = '';
        $this->description = '';
        $this->price = '';
        $this->qty = '';
        $this->resetValidation();
    }

    public function addItem()
    {
        $this->clearForm();
        $this->mode = 'add';
        $this->drawer = true;
    }

    public function editItem(string $id)
    {
        $this->clearForm();

        $this->selected = $id;
        $target = $this->items->get($id);

        $this->description = $target->description;
        $this->price = $target->price;
        $this->qty = $target->qty;

        $this->mode = 'edit';
        $this->drawer = true;
    }

    public function saveItem(int $new = 0)
    {
        $data = $this->validate([
            'description' => 'required|max:200',
            'price' => 'required',
            'qty' => 'required',
        ]);

        if ($this->mode == 'add')
        {
            $this->items->push((object)[
                'description' => $this->description,
                'price' => $this->price,
                'qty' => $this->qty,
                'subtotal' => $this->price * $this->qty,
            ]);
        }

        if ($this->mode == 'edit')
        {
            $this->items->transform(function ($data, $key) {
                if ($key == $this->selected) {
                    $data->description = $this->description;
                    $data->price = $this->price;
                    $data->qty = $this->qty;
                    $data->subtotal = $this->price * $this->qty;
                }
                return $data;
            });
        }

        if ($new == 1)
        {
            $this->clearForm();
        }
        else
        {
            $this->drawer = false;
            $this->success('Item has been created.');
        }
    }

    public function deleteItem(string $id)
    {
        $this->items->forget($id);
        $this->success('Item has been deleted.');
    }
}; ?>

<div>
    <x-header title="Update Invoice" separator>
        <x-slot:actions>
            <x-button label="Back" link="/invoice" icon="o-arrow-uturn-left" />
        </x-slot:actions>
    </x-header>
    <div class="space-y-4">
        <x-form wire:submit="save">
            <x-card separator>
                <div class="space-y-4 lg:space-y-0 lg:grid grid-cols-3 gap-4">
                    <x-input label="Number" wire:model="number" />
                    <x-input label="Date" wire:model="date" type="date" />
                    <x-input label="Note" wire:model="note" />
                </div>
            </x-card>

            <x-card title="Items" separator>
                <x-slot:menu>
                    <x-button label="Add Item" icon="o-plus" wire:click="addItem" spinner="addItem" class="" />
                </x-slot:menu>
                <div class="overflow-x-auto">
                    @error('items')
                    <div class="flex justify-center">
                        <span class="text-red-500 text-sm p-1">{{ $message }}</span>
                    </div>
                    @enderror
                    <table class="table">
                    <thead>
                    <tr>
                        <td>Item Description</td>
                        <td class="text-right lg:w-[10rem]">Price</td>
                        <td class="text-right lg:w-[10rem]">Qty</td>
                        <td class="text-right lg:w-[10rem]">Subtotal</td>
                        <td class="lg:w-[4rem]"></td>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse ($items as $key => $item)
                    <tr wire:loading.class="cursor-wait" class="divide-x divide-gray-200 dark:divide-gray-900 hover:bg-yellow-50 dark:hover:bg-gray-800 cursor-pointer">
                        <td wire:click="editItem('{{ $key }}')">{{ $item->description }}</td>
                        <td wire:click="editItem('{{ $key }}')" class="text-right">{{ Cast::money($item->price) }}</td>
                        <td wire:click="editItem('{{ $key }}')" class="text-right">{{ Cast::money($item->qty) }}</td>
                        <td wire:click="editItem('{{ $key }}')" class="text-right">{{ Cast::money($item->subtotal) }}</td>
                        <td>
                        <div class="flex items-center">
                            <x-button icon="o-x-mark" wire:click="deleteItem('{{ $key }}')" spinner="deleteItem('{{ $key }}')" wire:confirm="Are you sure ?" class="btn-xs btn-ghost text-xs -m-1 text-error" />
                        </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="divide-x divide-gray-200 dark:divide-gray-900 hover:bg-yellow-50 dark:hover:bg-gray-800">
                        <td colspan="10" class="text-center">No record found.</td>
                    </tr>
                    @endforelse

                    <tr>
                        <td class="text-right" colspan="3">Total</td>
                        <td class="text-right">{{ Cast::money($items->sum('subtotal')) }}</td>
                    </tr>
                    </tbody>
                    </table>
                </div>
            </x-card>

            <x-slot:actions>
                <x-button label="Cancel" link="/invoice" />
                <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </div>

    {{-- DRAWER --}}
    <x-drawer wire:model="drawer" title="Create / Update" right separator with-close-button class="lg:w-1/3">
        <x-form wire:submit="saveItem(0)">
            <div class="grid gap-5">
                <x-input label="Description" wire:model="description" />
                <x-input label="Price" wire:model="price" x-mask:dynamic="$money($input,'.','')" />
                <x-input label="Qty" wire:model="qty" x-mask:dynamic="$money($input,'.','')" />
            </div>
            <x-slot:actions>
                <x-button label="Save & create another" wire:click="saveItem(1)" spinner="saveItem(1)" />
                <x-button label="Save" icon="o-paper-airplane" spinner="saveItem(0)" type="submit" class="" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-drawer>
</div>
