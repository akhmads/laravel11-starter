<?php

use Livewire\Volt\Component;

new class extends Component {

    public function mount(): void
    {

    }
}; ?>
<div>
    {{-- HEADER --}}
    <x-header title="Card" separator />

    <x-card>
        <div class="sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5 gap-6 space-y-5 sm:space-y-0">

            <x-ui.card title="Nanami Yokomiya" image="{{ asset('assets/actors/nanami-yokomiya.jpg') }}" />
            <x-ui.card title="Hinano Kuno" image="{{ asset('assets/actors/hinano-kuno.jpg') }}" />
            <x-ui.card title="Momoka Nishina" image="{{ asset('assets/actors/momoka-nishina.jpg') }}" />
            <x-ui.card title="Yagura Fuuko" image="{{ asset('assets/actors/yagura-fuuko.jpg') }}" />
            <x-ui.card title="Sayuri Hayama" image="{{ asset('assets/actors/sayuri-hayama.jpg') }}" />
            <x-ui.card title="Hayasaka Hime" image="{{ asset('assets/actors/hayasaka-hime.jpg') }}" />
            <x-ui.card title="Kokomi Naruse" image="{{ asset('assets/actors/kokomi-naruse.jpg') }}" />

            {{-- <div class="overflow-hidden bg-white rounded-lg shadow-md">
                <div class="relative">
                    <img class="w-full h-48 object-cover" src="{{ asset('assets/actors/nanami-yokomiya.jpg') }}" alt="Image">
                    <div class="absolute top-0 right-0">
                        <div class="w-32 h-8 absolute top-4 -right-8">
                            <div class="h-full w-full bg-red-500 text-white text-center leading-8 font-semibold transform rotate-45">
                                NEW
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">Nanami Yokomiya</h3>
                    <p class="text-gray-600 text-sm leading-5">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </p>
                </div>
            </div> --}}

        </div>
    </x-card>
</div>
