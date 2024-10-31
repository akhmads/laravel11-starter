<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    public string $uuid;
    public string $colorClass;
    public string $sizeClass;
    public string $insetClass;

    public function __construct(
        public ?string $value = null,
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $solid = false,
        public ?bool $inset = false,
    ) {
        $this->uuid = 'ui' . md5(serialize($this));

        if ($this->solid) {
            $this->colorClass = match($color)
            {
                'red' => ' text-white dark:text-white bg-red-500 dark:bg-red-600',
                'green' => ' text-white dark:text-white bg-green-500 dark:bg-green-600',
                'lime' => ' text-white dark:text-white bg-lime-500 dark:bg-lime-600',
                'blue' => ' text-white dark:text-white bg-blue-500 dark:bg-blue-600',
                'yellow' => ' text-white dark:text-white bg-yellow-500 dark:bg-yellow-600',
                default => ' text-white dark:text-white bg-zinc-500 dark:bg-zinc-600'
            };
        } else {
            $this->colorClass = match($color)
            {
                'red' => ' text-red-800 dark:text-red-200 bg-red-400/20 dark:bg-red-400/40',
                'green' => ' text-green-800 dark:text-green-200 bg-green-400/20 dark:bg-green-400/40',
                'lime' => ' text-lime-800 dark:text-lime-200 bg-lime-400/20 dark:bg-lime-400/40',
                'blue' => ' text-blue-800 dark:text-blue-200 bg-blue-400/20 dark:bg-blue-400/40',
                'yellow' => ' text-yellow-800 dark:text-yellow-200 bg-yellow-400/20 dark:bg-yellow-400/40',
                default => ' text-zinc-800 dark:text-zinc-200 bg-zinc-400/20 dark:bg-zinc-400/40'
            };
        }

        $this->sizeClass = match($size)
        {
            'sm' => ' py-1 text-xs',
            'lg' => ' py-1 text-base',
            default => ' py-1 text-sm'
        };

        $this->insetClass = '';
        if ($this->inset) {
            $this->insetClass = ' -mt-1 -mb-1';
        }
    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
<div {{ $attributes->merge(['class' => 'inline-flex items-center font-medium whitespace-nowrap px-2 rounded-md' . $colorClass . $sizeClass . $insetClass]) }}>
    {{ $value }}
</div>
BLADE;
    }
}
