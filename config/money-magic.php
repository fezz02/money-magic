<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Currency column
    |--------------------------------------------------------------------------
    |
    | The database column that stores the ISO currency code (e.g. "EUR", "USD").
    | This column is used as a fallback when building Brick\Money instances.
    |
    */

    'currency-column' => env('MONEY_MAGIC_CURRENCY_COLUMN', 'currency'),

    /*
    |--------------------------------------------------------------------------
    | Float accessor (major units)
    |--------------------------------------------------------------------------
    |
    | Controls the "float" (major units) attribute naming.
    |
    | By default, an empty suffix means the float accessor uses the base field:
    | - price (float/major units)
    |
    | If you set a suffix, the float accessor becomes a derived field:
    | - price_float
    |
    | NOTE: If you accept user input, values may arrive as strings ("12.34"),
    | so your cast should handle numeric strings, not only float types.
    |
    */

    'float' => [
        'enabled' => true,
        'suffix' => env('MONEY_MAGIC_FLOAT_SUFFIX', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Minor units (integer)
    |--------------------------------------------------------------------------
    |
    | Controls the suffix used for the minor-units integer column.
    |
    | Example:
    | - price_minor (int)
    |
    */

    'minor' => [
        'suffix' => env('MONEY_MAGIC_MINOR_SUFFIX', '_minor'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Money accessor (Brick\Money)
    |--------------------------------------------------------------------------
    |
    | Controls the suffix used for the Brick\Money accessor.
    |
    | Example:
    | - price_money (Money|null)
    |
    */

    'money' => [
        'enabled' => true,
        'suffix' => env('MONEY_MAGIC_MONEY_SUFFIX', '_money'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Formatted accessor (string)
    |--------------------------------------------------------------------------
    |
    | Controls the suffix used for the formatted string accessor.
    |
    | Example:
    | - price_formatted (string|null)
    |
    | This accessor should be read-only and should format using your application's
    | preferred formatter/config (e.g. a Money format config key).
    |
    */

    'formatted' => [
        'enabled' => true,
        'suffix' => env('MONEY_MAGIC_FORMATTED_SUFFIX', '_formatted'),
        'format' => env('MONEY_MAGIC_FORMATTED_FORMAT', 'it-IT'),
    ],

];
