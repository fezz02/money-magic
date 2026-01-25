<?php

declare(strict_types=1);

namespace Fezz\MoneyMagic\Casts;

use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<float, float>
 */
final readonly class MoneyFloatCast implements CastsAttributes
{
    public function __construct(private string $currencyColumn = 'currency') {}

    public function get(Model $model, string $key, mixed $value, array $attributes): ?float
    {
        $base = $this->baseField($key);

        $moneySuffixConfig = config('money-magic.money.suffix', '_money');
        $moneySuffix = is_string($moneySuffixConfig) ? $moneySuffixConfig : '_money';
        $moneyKey = $base.$moneySuffix;

        /** @var Money|null $money */
        $money = $model->getAttribute($moneyKey);

        return $money?->getAmount()->toFloat();
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, int|string>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! is_float($value)) {
            // don't set anything if not float
            return [];
        }

        $currencyValue = $attributes[$this->currencyColumn]
            ?? $model->getAttribute($this->currencyColumn)
            ?? 'EUR';

        $currency = is_string($currencyValue) ? $currencyValue : (is_scalar($currencyValue) ? (string) $currencyValue : 'EUR');
        $money = Money::of($value, $currency);

        $base = $this->baseField($key);

        $minorSuffixConfig = config('money-magic.minor.suffix', '_minor');
        $minorSuffix = is_string($minorSuffixConfig) ? $minorSuffixConfig : '_minor';

        return [
            $base.$minorSuffix => (int) (string) $money->getMinorAmount(),
            $this->currencyColumn => $money->getCurrency()->getCurrencyCode(),
        ];
    }

    private function baseField(string $key): string
    {
        $floatSuffixConfig = config('money-magic.float.suffix', '');
        $floatSuffix = is_string($floatSuffixConfig) ? $floatSuffixConfig : '';

        if ($floatSuffix === '') {
            return $key;
        }

        return str_ends_with($key, $floatSuffix)
            ? substr($key, 0, -strlen($floatSuffix))
            : $key;
    }
}
