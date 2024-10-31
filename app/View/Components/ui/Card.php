<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $image = null,
        public ?string $title = null,
        public ?string $slot = null,
    ) {
        $this->uuid = 'ui' . md5(serialize($this));

    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
        <div class="flex flex-col overflow-hidden bg-white dark:bg-base-200 rounded-lg shadow-md dark:border border-gray-700 ">
            <img class="w-full h-48 object-cover object-top" src="{{ $image }}" alt="{{ $title }}">
            <div class="p-4 flex grow flex-col justify-between gap-3">
                <div class="space-y-3">
                    <h3 class="text-gray-900 dark:text-gray-100 text-base font-semibold leading-5">{{ $title }}</h3>
                    @if ($slot->isNotEmpty())
                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-5 m-0 p-0">
                        {{ $slot }}
                    </p>
                    @endif
                </div>
                {{--<div class="flex items-center justify-between gap-5">
                    <div class="text-sm text-gray-500 dark:text-gray-400">0 like</div>
                    <div><a href="#" class="text-blue-500 hover:bg-blue-400/20 text-sm px-2 py-1 rounded-lg transition ease-in-out duration-300">More &raquo;</a></div>
                </div>--}}
            </div>
        </div>
BLADE;
    }
}
