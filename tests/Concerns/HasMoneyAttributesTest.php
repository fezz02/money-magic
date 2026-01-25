<?php

use Fezz\MoneyMagic\Concerns\HasMoneyAttributes;
use Fezz\MoneyMagic\Tests\Models\Product;
use Illuminate\Database\Eloquent\Model;

it('initializes money attributes with default configuration', function () {
    $product = new Product([
        'name' => 'Test Product',
        'price_minor' => 1000,
        'currency' => 'EUR',
    ]);

    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('price_money')
        ->and($product->getCasts())->toHaveKey('price_minor')
        ->and($product->getHidden())->toContain('price_money')
        ->and($product->getHidden())->toContain('price_minor');
});

it('creates float accessor when enabled', function () {
    config(['money-magic.float.enabled' => true]);
    config(['money-magic.float.suffix' => '']);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('price');
});

it('creates float accessor with suffix when configured', function () {
    config(['money-magic.float.enabled' => true]);
    config(['money-magic.float.suffix' => '_float']);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('price_float');
});

it('skips float accessor when disabled', function () {
    config(['money-magic.float.enabled' => false]);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->not->toHaveKey('price');
});

it('creates minor accessor when enabled', function () {
    config(['money-magic.minor.enabled' => true]);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('price_minor');
});

it('skips minor accessor when disabled', function () {
    config(['money-magic.minor.enabled' => false]);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->not->toHaveKey('price_minor');
});

it('creates money accessor when enabled', function () {
    config(['money-magic.money.enabled' => true]);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('price_money');
});

it('skips money accessor when disabled', function () {
    config(['money-magic.money.enabled' => false]);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->not->toHaveKey('price_money');
});

it('creates formatted accessor when enabled', function () {
    config(['money-magic.formatted.enabled' => true]);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('price_formatted');
});

it('skips formatted accessor when disabled', function () {
    config(['money-magic.formatted.enabled' => false]);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->not->toHaveKey('price_formatted');
});

it('hides fields based on autohide configuration', function () {
    config(['money-magic.autohide.float' => true]);
    config(['money-magic.autohide.minor' => true]);
    config(['money-magic.autohide.money' => true]);
    config(['money-magic.autohide.formatted' => true]);
    config(['money-magic.float.enabled' => true]);
    config(['money-magic.float.suffix' => '_float']);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getHidden())->toContain('price_float')
        ->and($product->getHidden())->toContain('price_minor')
        ->and($product->getHidden())->toContain('price_money')
        ->and($product->getHidden())->toContain('price_formatted');
});

it('does not hide fields when autohide is disabled', function () {
    config(['money-magic.autohide.float' => false]);
    config(['money-magic.autohide.minor' => false]);
    config(['money-magic.autohide.money' => false]);
    config(['money-magic.autohide.formatted' => false]);
    config(['money-magic.float.enabled' => true]);
    config(['money-magic.float.suffix' => '_float']);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getHidden())->not->toContain('price_float')
        ->and($product->getHidden())->not->toContain('price_minor')
        ->and($product->getHidden())->not->toContain('price_money')
        ->and($product->getHidden())->not->toContain('price_formatted');
});

it('uses custom currency column from config', function () {
    config(['money-magic.currency-column' => 'currency_code']);

    $product = new class extends Model
    {
        use HasMoneyAttributes;

        protected $money = [
            'price' => null, // Should use default from config
        ];
    };

    $product->initializeHasMoneyAttributes();

    $moneyCast = $product->getCasts()['price_money'] ?? null;
    expect($moneyCast)->toContain('currency_code');
});

it('uses custom suffixes from config', function () {
    config(['money-magic.minor.suffix' => '_cents']);
    config(['money-magic.money.suffix' => '_amount']);
    config(['money-magic.formatted.suffix' => '_display']);

    $product = new Product;
    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('price_cents')
        ->and($product->getCasts())->toHaveKey('price_amount')
        ->and($product->getCasts())->toHaveKey('price_display');
});

it('handles multiple money fields', function () {
    $product = new class extends Model
    {
        use HasMoneyAttributes;

        protected $money = [
            'price' => 'currency',
            'discount' => 'currency',
        ];
    };

    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('price_money')
        ->and($product->getCasts())->toHaveKey('discount_money')
        ->and($product->getCasts())->toHaveKey('price_minor')
        ->and($product->getCasts())->toHaveKey('discount_minor');
});

it('uses field-specific currency column when provided', function () {
    $product = new class extends Model
    {
        use HasMoneyAttributes;

        protected $money = [
            'price' => 'price_currency',
            'discount' => 'discount_currency',
        ];
    };

    $product->initializeHasMoneyAttributes();

    $priceCast = $product->getCasts()['price_money'] ?? null;
    $discountCast = $product->getCasts()['discount_money'] ?? null;

    expect($priceCast)->toContain('price_currency')
        ->and($discountCast)->toContain('discount_currency');
});

it('merges with existing casts', function () {
    $product = new class extends Model
    {
        use HasMoneyAttributes;

        protected $casts = [
            'name' => 'string',
        ];

        protected $money = [
            'price' => 'currency',
        ];
    };

    $product->initializeHasMoneyAttributes();

    expect($product->getCasts())->toHaveKey('name')
        ->and($product->getCasts())->toHaveKey('price_money');
});

it('merges with existing hidden attributes', function () {
    $product = new class extends Model
    {
        use HasMoneyAttributes;

        protected $hidden = ['secret'];

        protected $money = [
            'price' => 'currency',
        ];
    };

    $product->initializeHasMoneyAttributes();

    expect($product->getHidden())->toContain('secret')
        ->and($product->getHidden())->toContain('price_money');
});

it('removes duplicates from hidden array', function () {
    $product = new class extends Model
    {
        use HasMoneyAttributes;

        protected $hidden = ['price_money']; // Already hidden

        protected $money = [
            'price' => 'currency',
        ];
    };

    $product->initializeHasMoneyAttributes();

    $hidden = $product->getHidden();
    $count = array_count_values($hidden)['price_money'] ?? 0;

    expect($count)->toBe(1); // Should only appear once
});
