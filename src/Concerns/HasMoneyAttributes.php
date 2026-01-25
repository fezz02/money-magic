<?php

declare(strict_types=1);

namespace Fezz\MoneyMagic\Concerns;

use App\Casts\MoneyFloatCast;
use App\Casts\MoneyFormattedCast;
use Fezz\MoneyMagic\Casts\MoneyCast;

trait HasMoneyAttributes
{
    public function initializeHasMoneyAttributes(): void
    {
        $casts = [];
        $toHide = [];

        foreach ($this->money as $field => $currencyColumn) {
            $currencyColumn = $currencyColumn ?: 'currency';
            // {field}: float (writable via float -> calculates minor)
            $casts[$field] = MoneyFloatCast::class . ':' . $currencyColumn;

            // {field}_minor: int (directly writable)
            $casts["{$field}_minor"] = 'integer';

            // {field}_money: Money (writable via Money) -> uses ONLY MoneyCast
            $casts["{$field}_money"] = MoneyCast::class . ':' . $currencyColumn;

            // {field}_formatted: string (read-only)
            $casts["{$field}_formatted"] = MoneyFormattedCast::class . ":{$field}";

            // auto-hide
            $toHide[] = "{$field}_minor";
            $toHide[] = "{$field}_money";
        }

        $this->mergeCasts($casts);
        $this->hidden = array_values(array_unique(array_merge($this->hidden, $toHide)));
    }
}
