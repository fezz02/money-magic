<?php

namespace Fezz\MoneyMagic\Tests\Models;

use Fezz\MoneyMagic\Concerns\HasMoneyAttributes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasMoneyAttributes;

    protected $fillable = [
        'name',
        'price_minor',
        'currency',
    ];

    protected $money = [
        'price' => 'currency',
    ];
}
