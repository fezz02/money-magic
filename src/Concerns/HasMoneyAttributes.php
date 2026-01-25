<?php

declare(strict_types=1);

namespace Fezz\MoneyMagic\Concerns;

use Fezz\MoneyMagic\Casts\MoneyCast;
use Fezz\MoneyMagic\Casts\MoneyFloatCast;
use Fezz\MoneyMagic\Casts\MoneyFormattedCast;

trait HasMoneyAttributes
{
    public function initializeHasMoneyAttributes(): void
    {
        $casts = [];
        $toHide = [];

        $defaultCurrencyColumn = (string) config('money-magic.currency-column', 'currency');

        $floatEnabled = (bool) config('money-magic.float.enabled', true);
        $minorEnabled = (bool) config('money-magic.minor.enabled', true);
        $moneyEnabled = (bool) config('money-magic.money.enabled', true);
        $formattedEnabled = (bool) config('money-magic.formatted.enabled', true);

        $floatSuffix = (string) config('money-magic.float.suffix', '');
        $minorSuffix = (string) config('money-magic.minor.suffix', '_minor');
        $moneySuffix = (string) config('money-magic.money.suffix', '_money');
        $formattedSuffix = (string) config('money-magic.formatted.suffix', '_formatted');

        $hideFloat = (bool) config('money-magic.autohide.float', false);
        $hideMinor = (bool) config('money-magic.autohide.minor', true);
        $hideMoney = (bool) config('money-magic.autohide.money', true);
        $hideFormatted = (bool) config('money-magic.autohide.formatted', false);

        foreach ($this->money as $field => $currencyColumn) {
            $currencyColumn = $currencyColumn ?: $defaultCurrencyColumn;

            if ($floatEnabled) {
                $floatKey = $field.$floatSuffix;
                $casts[$floatKey] = MoneyFloatCast::class.':'.$currencyColumn;

                if ($hideFloat) {
                    $toHide[] = $floatKey;
                }
            }

            if ($minorEnabled) {
                $minorKey = $field.$minorSuffix;
                $casts[$minorKey] = 'integer';

                if ($hideMinor) {
                    $toHide[] = $minorKey;
                }
            }

            if ($moneyEnabled) {
                $moneyKey = $field.$moneySuffix;
                $casts[$moneyKey] = MoneyCast::class.':'.$currencyColumn;

                if ($hideMoney) {
                    $toHide[] = $moneyKey;
                }
            }

            if ($formattedEnabled) {
                $formattedKey = $field.$formattedSuffix;
                $casts[$formattedKey] = MoneyFormattedCast::class.':'.$field;

                if ($hideFormatted) {
                    $toHide[] = $formattedKey;
                }
            }
        }

        $this->mergeCasts($casts);

        if ($toHide !== []) {
            $this->hidden = array_values(array_unique(array_merge($this->hidden, $toHide)));
        }
    }
}
