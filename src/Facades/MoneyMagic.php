<?php

namespace Fezz\MoneyMagic\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fezz\MoneyMagic\MoneyMagic
 */
class MoneyMagic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Fezz\MoneyMagic\MoneyMagic::class;
    }
}
