<?php

declare(strict_types=1);

namespace Fezz\MoneyMagic\Casts;

use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final readonly class MoneyCast implements CastsAttributes
{
    public function __construct(private ?string $currencyColumn = null) {}

    public function get(Model $model, string $key, mixed $value, array $attributes): ?Money
    {
        $base = $this->baseField($key);
        $minorKey = $base . '_minor';

        throw_unless(
            array_key_exists($minorKey, $attributes),
            InvalidArgumentException::class,
            __('The :minorKey field is required in the database for the Money cast.', [
                'minorKey' => $minorKey,
            ])
        );

        $minor = $attributes[$minorKey] ?? null;

        $currencyColumn = $this->currencyColumn();
        $currency = $attributes[$currencyColumn]
            ?? $model->getAttribute($currencyColumn)
            ?? null;

        if ($minor === null || $currency === null) {
            return null;
        }

        return Money::ofMinor((int) $minor, (string) $currency);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof Money) {
            throw new InvalidArgumentException(
                __('Value for :key must be an instance of :class.', [
                    'key' => $key,
                    'class' => Money::class,
                ])
            );
        }

        $base = $this->baseField($key);
        $minorKey = $base . '_minor';
        $currencyColumn = $this->currencyColumn();

        return [
            $minorKey => (int) (string) $value->getMinorAmount(),
            $currencyColumn => $value->getCurrency()->getCurrencyCode(),
        ];
    }

    private function baseField(string $key): string
    {
        // Default: cast is applied to "{base}_money"
        $moneySuffix = (string) config('money-magic.money.suffix', '_money');

        return str_ends_with($key, $moneySuffix)
            ? substr($key, 0, -strlen($moneySuffix))
            : $key;
    }

    private function currencyColumn(): string
    {
        return $this->currencyColumn
            ?? (string) config('money-magic.currency-column', 'currency');
    }
}
