<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public Collection $contents;
    public ?string $blockIndex = '';
    public ?string $blockEditor = '';
    public ?string $blockBody = '';
    public ?array $blockList = [];
    public bool $drawer = false;

    public function mount(): void
    {
        $this->blockList = [
            ['id' => 'playground.text', 'name' => 'Text Component'],
        ];

        $this->contents = collect([]);
    }

    public function with(): array
    {
        return [];
    }

    public function save(): void
    {
        $data = $this->validate([
            'blockEditor' => 'required',
        ]);

        $this->drawer = true;

        $this->success('Form submitted.');
    }

    public function addContent($editor, $body): void
    {
        $this->contents->push([
            'editor' => $editor,
            'body' => json_decode(base64_decode($body)),
        ]);
    }

    public function editContent($index, $editor, $body): void
    {
        $this->blockIndex = $index;
        $this->blockEditor = $editor;
        $this->blockBody = $body;
        $this->drawer = true;
    }

    #[On('block-updated')]
    public function updateBlock($index, $body)
    {
        $this->contents->transform(function ($data, $key) use ($index, $body) {
            if ($key == $index) {
                $data['body'] = json_decode(base64_decode($body));
            }
            return $data;
        });

        $this->success('Block updated.');
    }

    #[On('block-deleted')]
    public function deleteBlock($index)
    {
        $this->contents->forget($index);
        $this->drawer = false;
        $this->success('Block has been deleted.');
    }

    public function saveContent(int $new = 0)
    {
        $data = $this->validate([
            'editor' => 'required',
            'body' => 'required',
        ]);

        // if ($this->mode == 'add')
        // {
        //     $this->contents->push([
        //         'name' => 'Text',
        //         'body' => json_encode(['body' => 'This is body']),
        //     ]);
        // }

        // if ($this->mode == 'edit')
        // {
        //     $this->items->transform(function ($data, $key) {
        //         if ($key == $this->selected) {
        //             $data->body = $this->body;
        //         }
        //         return $data;
        //     });
        // }

        $this->drawer = false;
        $this->success('Block has been updated.');
    }
}; ?>
<div>
    <x-header title="Form" separator />

    <div class="lg:max-w-screen-md space-y-5">
        <x-card title="View Component" separator progress-indicator>
            <div class="space-y-5">
                <div class="grid grid-cols-4 gap-2">
                    <div wire:click="addContent('playground.text', '{{ base64_encode(json_encode(['text' => 'The text'])) }}')" class="py-2 px-4 rounded-lg cursor-pointer bg-slate-100 hover:bg-slate-200">
                        Text
                    </div>
                </div>

                @forelse ($contents as $key => $content)
                <div wire:click="editContent('{{ $key }}','{{ $content['editor'] }}','{{ base64_encode(json_encode($content['body'])) }}')" class="p-5 rounded-lg cursor-pointer bg-slate-100 hover:bg-slate-200">
                    {{-- {{ $content['editor'] }}<br>
                    {{ json_encode($content['body']) }} --}}
                     {{ $content['body']->text ?? '' }}
                </div>
                @endforeach
            </div>
        </x-card>
    </div>

    {{-- FILTER DRAWER --}}
    <x-drawer wire:model="drawer" right separator with-close-button class="lg:w-1/2">
        @if (!empty($blockEditor))
        @livewire($blockEditor, ['index' => $blockIndex, 'body' => $blockBody], key($blockEditor . $blockIndex))
        @endif
    </x-drawer>
</div>
