<?php

use Brick\Money\Money;
use Fezz\MoneyMagic\Casts\MoneyFloatCast;
use Illuminate\Database\Eloquent\Model;

beforeEach(function () {
    $this->model = new class extends Model
    {
        protected $casts = [];
    };
});

it('can get float from money attribute', function () {
    $cast = new MoneyFloatCast('currency');
    $money = Money::ofMinor(1000, 'EUR'); // 10.00 EUR

    $this->model->setAttribute('price_money', $money);

    $result = $cast->get($this->model, 'price', null, []);

    expect($result)->toBeFloat()
        ->and($result)->toBe(10.0);
});

it('returns null when money is null', function () {
    $cast = new MoneyFloatCast('currency');

    $result = $cast->get($this->model, 'price', null, []);

    expect($result)->toBeNull();
});

it('can set float to attributes', function () {
    $cast = new MoneyFloatCast('currency');
    $attributes = [
        'currency' => 'EUR',
    ];

    $result = $cast->set($this->model, 'price', 12.34, $attributes);

    expect($result)->toBeArray()
        ->and($result['price_minor'])->toBe(1234)
        ->and($result['currency'])->toBe('EUR');
});

it('returns empty array when setting non-float value', function () {
    $cast = new MoneyFloatCast('currency');

    $result = $cast->set($this->model, 'price', 'invalid', []);

    expect($result)->toBeArray()
        ->and($result)->toBeEmpty();
});

it('uses default currency when not provided', function () {
    $cast = new MoneyFloatCast('currency');

    $result = $cast->set($this->model, 'price', 10.50, []);

    expect($result)->toBeArray()
        ->and($result['currency'])->toBe('EUR');
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

    $cast = new MoneyFloatCast('currency');

    $result = $cast->set($model, 'price', 10.50, []);

    expect($result)->toBeArray()
        ->and($result['currency'])->toBe('USD');
});

it('handles custom float suffix', function () {
    config(['money-magic.float.suffix' => '_float']);

    $cast = new MoneyFloatCast('currency');
    $money = Money::ofMinor(1000, 'EUR');

    $this->model->setAttribute('price_money', $money);

    $result = $cast->get($this->model, 'price_float', null, []);

    expect($result)->toBeFloat();
});

it('handles empty float suffix', function () {
    config(['money-magic.float.suffix' => '']);

    $cast = new MoneyFloatCast('currency');
    $money = Money::ofMinor(1000, 'EUR');

    $this->model->setAttribute('price_money', $money);

    $result = $cast->get($this->model, 'price', null, []);

    expect($result)->toBeFloat();
});

it('uses custom minor suffix from config', function () {
    config(['money-magic.minor.suffix' => '_cents']);

    $cast = new MoneyFloatCast('currency');

    $result = $cast->set($this->model, 'price', 12.34, ['currency' => 'EUR']);

    expect($result)->toBeArray()
        ->and($result['price_cents'])->toBe(1234);
});

it('uses custom money suffix from config', function () {
    config(['money-magic.money.suffix' => '_amount']);

    $cast = new MoneyFloatCast('currency');
    $money = Money::ofMinor(1000, 'EUR');

    $this->model->setAttribute('price_amount', $money);

    $result = $cast->get($this->model, 'price', null, []);

    expect($result)->toBeFloat();
});
