<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Select as FilamentSelect;

class Select extends FilamentSelect
{
    protected function setUp(): void
    {
        parent::setUp();

        // Override the transformOptionsForJsUsing method to handle null labels
        $this->transformOptionsForJsUsing(static function (Select $component, array $options): array {
            return collect($options)
                ->map(fn ($label, $value): array => is_array($label)
                    ? ['label' => $value, 'choices' => $component->transformOptionsForJs($label)]
                    : ['label' => $label ?? (string)$value, 'value' => strval($value), 'disabled' => $component->isOptionDisabled($value, $label ?? (string)$value)])
                ->values()
                ->all();
        });
    }
}