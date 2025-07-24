<?php

namespace App\Enums;

enum LiveCategoryEnum: string
{
    case NORMAL = 'normal';
    case PREMIUM = 'premium';
    case AUDIO = 'audio';

    /**
     * Get all values as an array
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all cases as an array for select options
     *
     * @return array
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = ucfirst($case->value);
        }
        return $options;
    }
}