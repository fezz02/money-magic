<?php

declare(strict_types=1);

use Brick\Money\Money;
use Fezz\MoneyMagic\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;

beforeEach(function () {
    $this->model = new class extends Model
    {
        protected $casts = [];
    };
});

it('can get money from attributes', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 1000,
        'currency' => 'EUR',
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class)
        ->and($result->getMinorAmount()->toInt())->toBe(1000)
        ->and($result->getCurrency()->getCurrencyCode())->toBe('EUR');
});

it('returns null when minor is null', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => null,
        'currency' => 'EUR',
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeNull();
});

it('returns null when currency is null', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 1000,
        'currency' => null,
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeNull();
});

it('throws exception when minor key is missing', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'currency' => 'EUR',
    ];

    expect(fn () => $cast->get($this->model, 'price_money', null, $attributes))
        ->toThrow(InvalidArgumentException::class);
});

it('can set money to attributes', function () {
    $cast = new MoneyCast('currency');
    $money = Money::ofMinor(1000, 'EUR');

    $result = $cast->set($this->model, 'price_money', $money, []);

    expect($result)->toBeArray()
        ->and($result['price_minor'])->toBe(1000)
        ->and($result['currency'])->toBe('EUR');
});

it('throws exception when setting non-money value', function () {
    $cast = new MoneyCast('currency');

    expect(fn () => $cast->set($this->model, 'price_money', 'invalid', []))
        ->toThrow(InvalidArgumentException::class);
});

it('uses custom currency column from config', function () {
    config(['money-magic.currency-column' => 'currency_code']);

    $cast = new MoneyCast;
    $attributes = [
        'price_minor' => 1000,
        'currency_code' => 'USD',
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class)
        ->and($result->getCurrency()->getCurrencyCode())->toBe('USD');
});

it('uses custom money suffix from config', function () {
    config(['money-magic.money.suffix' => '_amount']);

    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 1000,
        'currency' => 'EUR',
    ];

    $result = $cast->get($this->model, 'price_amount', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class);
});

it('gets currency from model attribute when not in attributes', function () {
    $model = new class extends Model
    {
        public function getAttribute($key)
        {
            return match ($key) {
                'currency' => 'USD',
                default => parent::getAttribute($key),
            };
        }
    };

    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 1000,
    ];

    $result = $cast->get($model, 'price_money', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class)
        ->and($result->getCurrency()->getCurrencyCode())->toBe('USD');
});

it('handles base field extraction correctly', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 1000,
        'currency' => 'EUR',
    ];

    // Test with suffix
    $result1 = $cast->get($this->model, 'price_money', null, $attributes);
    expect($result1)->toBeInstanceOf(Money::class);

    // Test without suffix (should still work)
    $result2 = $cast->get($this->model, 'price', null, $attributes);
    expect($result2)->toBeInstanceOf(Money::class);
});

it('handles numeric string minor value', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => '1000',
        'currency' => 'EUR',
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class)
        ->and($result->getMinorAmount()->toInt())->toBe(1000);
});

it('handles non-numeric minor value', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 'invalid',
        'currency' => 'EUR',
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class)
        ->and($result->getMinorAmount()->toInt())->toBe(0);
});

it('handles scalar currency value', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 1000,
        'currency' => 'USD', // string currency
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class)
        ->and($result->getCurrency()->getCurrencyCode())->toBe('USD');
});

it('returns null when currency is non-scalar', function () {
    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 1000,
        'currency' => [], // non-scalar
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeNull();
});

it('handles non-string money suffix from config', function () {
    config(['money-magic.money.suffix' => 123]);

    $cast = new MoneyCast('currency');
    $attributes = [
        'price_minor' => 1000,
        'currency' => 'EUR',
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class);
});

it('handles non-string currency column from config', function () {
    config(['money-magic.currency-column' => 123]);

    $cast = new MoneyCast;
    $attributes = [
        'price_minor' => 1000,
        'currency' => 'EUR',
    ];

    $result = $cast->get($this->model, 'price_money', null, $attributes);

    expect($result)->toBeInstanceOf(Money::class);
});
