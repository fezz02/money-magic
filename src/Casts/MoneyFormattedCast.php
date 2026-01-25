<?php

declare(strict_types=1);

namespace Fezz\MoneyMagic\Casts;

use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<string, array<string, mixed>>
 */
final readonly class MoneyFormattedCast implements CastsAttributes
{
    public function __construct(
        private string $base,
    ) {}

    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        $moneySuffixConfig = config('money-magic.money.suffix', '_money');
        $moneySuffix = is_string($moneySuffixConfig) ? $moneySuffixConfig : '_money';
        $moneyKey = "{$this->base}".$moneySuffix;

        /** @var Money|null $money */
        $money = $model->getAttribute($moneyKey);

        if ($money === null) {
            return null;
        }

        $formatConfig = config('money-magic.formatted.format', 'it-IT');
        $format = is_string($formatConfig) ? $formatConfig : 'it-IT';

        return $money->formatToLocale($format);
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        return [];
    }
}
