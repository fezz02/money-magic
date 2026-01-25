<?php

use Brick\Money\Money;
use Fezz\MoneyMagic\Casts\MoneyFormattedCast;
use Illuminate\Database\Eloquent\Model;

beforeEach(function () {
    $this->model = new class extends Model
    {
        protected $casts = [];
    };
});

it('can get formatted string from money attribute', function () {
    $cast = new MoneyFormattedCast('price');
    $money = Money::ofMinor(1000, 'EUR'); // 10.00 EUR

    $this->model->setAttribute('price_money', $money);

    $result = $cast->get($this->model, 'price_formatted', null, []);

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('returns null when money is null', function () {
    $cast = new MoneyFormattedCast('price');

    $result = $cast->get($this->model, 'price_formatted', null, []);

    expect($result)->toBeNull();
});

it('uses custom format from config', function () {
    config(['money-magic.formatted.format' => 'en-US']);

    $cast = new MoneyFormattedCast('price');
    $money = Money::ofMinor(1000, 'USD');

    $this->model->setAttribute('price_money', $money);

    $result = $cast->get($this->model, 'price_formatted', null, []);

    expect($result)->toBeString()
        ->and($result)->not->toBeEmpty();
});

it('uses custom money suffix from config', function () {
    config(['money-magic.money.suffix' => '_amount']);

    $cast = new MoneyFormattedCast('price');
    $money = Money::ofMinor(1000, 'EUR');

    $this->model->setAttribute('price_amount', $money);

    $result = $cast->get($this->model, 'price_formatted', null, []);

    expect($result)->toBeString();
});

it('returns empty array when setting value', function () {
    $cast = new MoneyFormattedCast('price');

    $result = $cast->set($this->model, 'price_formatted', 'any value', []);

    expect($result)->toBeArray()
        ->and($result)->toBeEmpty();
});
