<?php

namespace App\Enums;

trait Arrayable
{
    /**
     * @return array<string, mixed>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
