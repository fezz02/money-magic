<?php

declare(strict_types=1);

namespace App\Casts;

use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final readonly class MoneyFloatCast implements CastsAttributes
{
    public function __construct(private string $currencyColumn = 'currency') {}

    public function get(Model $model, string $key, mixed $value, array $attributes): ?float
    {
        $base = $this->baseField($key);

        $moneyKey = $base . (string) config('money-magic.money.suffix', '_money');

        /** @var Money|null $money */
        $money = $model->getAttribute($moneyKey);

        return $money?->getAmount()->toFloat();
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! is_float($value)) {
            // don't set anything if not float
            return [];
        }

        $currency = $attributes[$this->currencyColumn]
            ?? $model->getAttribute($this->currencyColumn)
            ?? 'EUR';

        $money = Money::of($value, $currency);

        $base = $this->baseField($key);

        return [
            $base . (string) config('money-magic.minor.suffix', '_minor') => (int) (string) $money->getMinorAmount(),
            $this->currencyColumn => $money->getCurrency()->getCurrencyCode(),
        ];
    }

    private function baseField(string $key): string
    {
        $floatSuffix = (string) config('money-magic.float.suffix', '');

        if ($floatSuffix === '') {
            return $key;
        }

        return str_ends_with($key, $floatSuffix)
            ? substr($key, 0, -strlen($floatSuffix))
            : $key;
    }
}
