<?php

declare(strict_types=1);

namespace App\Casts;

use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final readonly class MoneyFormattedCast implements CastsAttributes
{
    public function __construct(
        private string $base,
    ) {}

    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        /** @var Money|null $money */
        $money = $model->getAttribute("{$this->base}" . (string) config('money-magic.money.suffix', '_money'));

        if ($money === null) {
            return null;
        }

        return $money->formatTo(config('money-magic.formatted.format', 'it-IT'));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        return [];
    }
}
