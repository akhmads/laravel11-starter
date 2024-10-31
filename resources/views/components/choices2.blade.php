@props(['label' => '', 'options' => [], 'search' => 'search(value)', 'placeholder' => '-- Select --', 'disabled' => false])
@php
$live = '';
if ($attributes->has('wire:model.live')) $live = '.live';
$name = $attributes->whereStartsWith('wire:model')->first();
$uuid = md5($name);
$errors = $errors->get($name);
@endphp
<div
    class="{{ $attributes->get('class') }}"
    x-data="{
        open: false,
        selection: @entangle($attributes->wire('model'))
    }"
>
    {{-- LABEL --}}
    @unless(empty($label))
    <label class="block font-medium text-sm text-gray-700 mb-2">
        {{ $label }}
    </label>
    @endunless

    {{-- MAIN --}}
    <div
        x-data="{
            placeholder: '{{ $placeholder }}',
            options: {{ json_encode($options) }},
            nosearch: false,
            hasvalue: false,
            init() {
                let self = this;
                if (this.selection !== null) {
                    this.hasvalue = true;
                    $refs.label.innerHTML = this.options[this.selection];
                }
                $wire.on('clear_{{ $name }}', (event) => {
                    self.clear();
                });
            },
            toggle() {
                this.open =! this.open;
                if (this.open) {
                    $refs.keyword.value = '';
                    this.search('');
                }
            },
            search(value) {
                let self = this;
                if(!this.nosearch) {
                    $wire.{!! $search !!};
                }
                this.nosearch = false;
            },
            select(id, label) {
                $refs.label.innerHTML = label;
                this.open = false
                this.selection = id
                this.hasvalue = true
            },
            isActive(id) {
                return this.selection == id;
            },
            clear() {
                $refs.label.innerHTML = this.placeholder;
                this.selection = '';
                this.hasvalue = false;
            },
        }"
        x-ref="button"
        @keydown.enter="toggle()"
        @click.outside="open = false"
        @keyup.esc = "open = false"
        class="relative"
    >
        <span x-cloak x-show="hasvalue" @click="clear" class="absolute top-4 right-9 flex items-center cursor-pointer text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </span>
        {{-- <span @click="toggle()" class="select-none absolute inset-y-0 right-0 flex items-center cursor-pointer pr-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </span> --}}
        <div x-ref="label" wire:ignore @click="toggle()" tabindex="0" class="w-full bg-white select-none px-3 py-3 text-base truncate cursor-pointer select select-bordered select-primary" :class="open ? 'border-2 border-indigo-500' : ''">
            {!! empty($placeholder) ? '&nbsp;' : $placeholder !!}
        </div>

        <div x-cloak x-show="open" x-trap.noscroll="open" x-anchor.bottom-start.offset.5="$refs.button" class="absolute z-50 w-full py-2 bg-white border border-gray-300 rounded-md shadow-lg">
            <div class="px-3">
                <input
                    x-ref="keyword"
                    @keydown.enter.stop.prevent="nosearch = true"
                    @keydown.enter.stop.prevent="$focus.next()"
                    @keydown.up="nosearch = true"
                    @keydown.down="nosearch = true"
                    @keydown.left="nosearch = true"
                    @keydown.right="nosearch = true"
                    @keydown.tab="nosearch = true"
                    @keydown.debounce.500ms="search($el.value)"
                    type="text"
                    autofocus
                    placeholder="Search"
                    class="w-full border border-gray-300 focus:border-1 focus:border-slate-300 focus:ring-0 focus:outline-0 py-2 px-2 mb-2 rounded-md shadow-sm"
                />
            </div>
            <div
                @keydown.down="$focus.wrap().next()"
                @keydown.up="$focus.wrap().previous()"
                class="max-h-[210px] overflow-y-auto divide-y divide-slate-100 border-y border-y-slate-100"
            >
                @forelse ( $options as $key => $val )
                <button
                    wire:key="choice-item-{{ $uuid }}-{{ $key }}"
                    id="choice-item-{{ $uuid }}-{{ $key }}"
                    type="button"
                    @click="select('{{ $key }}','{{ $val }}')"
                    @keyup.enter="select('{{ $key }}','{{ $val }}')"
                    :class="isActive('{{ $key }}') && 'bg-slate-200 font-bold'"
                    class="block w-full text-left py-2 px-3 cursor-pointer hover:bg-slate-100 focus:bg-slate-100 focus:outline-none"
                >
                    {{ $val }}
                </button>
                @empty
                <div class="p-1">No data found.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ERROR MESSAGE --}}
    @if ($errors)
    <div class="mt-1 text-sm text-red-600">
        @foreach ((array) $errors as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif
</div>
