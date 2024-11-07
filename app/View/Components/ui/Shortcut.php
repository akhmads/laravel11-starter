<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Shortcut extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $icon = null,
        public ?string $title = null,
    ) {
        $this->uuid = 'ui' . md5(serialize($this));

    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
        <a {{ $attributes->merge(['class' => 'inline-block py-2 px-2 space-y-1 rounded-lg cursor-pointer hover:text-blue-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 hover:border-slate-300 transition duration-150 ease-in']) }}>
            <span class="flex justify-center">
                <x-icon name="{{ $icon }}" class="w-10 h-10" />
            </span>
            <span class="flex justify-center text-xs">{{ $title }}</span>
        </a>
BLADE;
    }
}
