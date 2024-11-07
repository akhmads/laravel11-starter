<?php

namespace App\Enums;

enum Role: string
{
    case admin = 'admin';
    case member = 'member';

    public function color(): string
    {
        return match($this)
        {
            self::admin => 'bg-green-500 text-white font-semibold',
            self::member => 'bg-blue-500 text-white font-semibold',
        };
    }

    public static function toSelect($placeholder = false): array
    {
        $array = [];
        $index = 0;
        if ($placeholder) {
            $array[$index]['id'] = '';
            $array[$index]['name'] = '--';
            $index++;
        }
        foreach (self::cases() as $key => $case) {
            $array[$index]['id'] = $case->value;
            $array[$index]['name'] = $case->value;
            $index++;
        }
        return $array;
    }
}
