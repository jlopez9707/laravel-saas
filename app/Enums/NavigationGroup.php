<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum NavigationGroup implements HasLabel, HasIcon
{
    case Product;

    case Sale;

    case Settings;

    public function getLabel(): string
    {
        return match ($this) {
            self::Product => __('Products'),
            self::Sale => __('Sales'),
            self::Settings => __('Settings'),
        };
    }

    public function getIcon(): string|Heroicon|null
    {
        return match ($this) {
            self::Product => 'polaris-product-filled-icon',
            self::Sale => Heroicon::ShoppingCart,
            self::Settings => Heroicon::Cog6Tooth,
        };
    }
}

