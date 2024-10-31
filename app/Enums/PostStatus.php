<?php

namespace App\Enums;

enum PostStatus: string
{
    case draft = 'draft';
    case published = 'published';

    public function color(): string
    {
        return match($this)
        {
            self::draft => 'bg-indigo-100 text-indigo-700 inline-flex items-center font-medium whitespace-nowrap !py-1 !h-auto border-0',
            self::published => 'bg-green-100 text-green-700 inline-flex items-center font-medium whitespace-nowrap !py-1 !h-auto border-0',
        };
    }

    public static function toSelect($placeholder = false): array
    {
        $array = [];
        $index = 0;
        if ($placeholder) {
            $array[$index]['id'] = '';
            $array[$index]['name'] = '-- Status --';
            $index++;
        }
        foreach (self::cases() as $key => $case) {
            $array[$index]['id'] = $case->value;
            $array[$index]['name'] = $case->name;
            $index++;
        }
        return $array;
    }
}
