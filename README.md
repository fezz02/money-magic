# Money Magic

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fezz/money-magic.svg?style=flat-square)](https://packagist.org/packages/fezz/money-magic)
[![Total Downloads](https://img.shields.io/packagist/dt/fezz/money-magic.svg?style=flat-square)](https://packagist.org/packages/fezz/money-magic)
[![License](https://img.shields.io/packagist/l/fezz/money-magic.svg?style=flat-square)](https://packagist.org/packages/fezz/money-magic)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/fezz02/money-magic/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/fezz02/money-magic/actions?query=workflow%3Arun-tests+branch%3Amain)

Generate consistent `Brick\Money` accessors for Eloquent "money" fields stored safely as **INTEGER minor units** (e.g., cents). Define money mappings once, avoid floats, keep currency handling centralized, and get clean, type-safe monetary domain code.

## Why Money Magic?

Storing money as floats in databases is a common mistake that leads to precision errors. Money Magic solves this by:

- **Storing money as integers** (minor units like cents) in your database
- **Never storing floats** - all float accessors are derived from the integer storage
- **Providing type-safe accessors** using `Brick\Money` for calculations
- **Auto-hiding internal fields** in JSON output (configurable)
- **Centralized currency handling** through a simple configuration

## Requirements

- PHP ^8.3
- Laravel ^11.0 or ^12.0

## Installation

You can install the package via composer:

```bash
composer require fezz/money-magic
```

## Publishing Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --tag="money-magic-config"
```

This will publish the configuration file to `config/money-magic.php` where you can customize suffixes, auto-hide behavior, and enabled toggles.

## Usage

### Basic Example

Use the `HasMoneyAttributes` trait in your model and define a `$money` mapping:

```php
use Fezz\MoneyMagic\Concerns\HasMoneyAttributes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasMoneyAttributes;

    protected array $money = [
        'price' => 'currency', // or null to use default currency-column
    ];
}
```

### Available Attributes

Based on the default configuration, the following attributes are automatically available:

- `$product->price` - Float accessor (major units) if float is enabled and suffix is empty
- `$product->price_minor` - Integer accessor (minor units, e.g., cents)
- `$product->price_money` - `Brick\Money\Money` instance (nullable)
- `$product->price_formatted` - Formatted string accessor (read-only)

### Auto-Hide Behavior

By default, internal fields (`price_minor` and `price_money`) are automatically hidden from JSON output. This behavior is configurable via the `autohide` configuration.

### Example Usage

```php
// Setting a price (stores as minor units)
$product->price = 12.99; // Stores 1299 in price_minor column

// Accessing the price
$product->price; // 12.99 (float)
$product->price_minor; // 1299 (int)
$product->price_money; // Brick\Money\Money instance
$product->price_formatted; // "€12,99" (formatted string)

// Working with Money object
$product->price_money->multiply(2); // Returns new Money instance
$product->price_money->getAmount(); // Decimal instance
```

## Configuration

The configuration file (`config/money-magic.php`) allows you to customize:

- **`currency-column`** - The database column name that stores the ISO currency code (default: `'currency'`)
- **`float.enabled`** - Enable/disable the float accessor (default: `true`)
- **`float.suffix`** - Suffix for float accessor (empty string uses base field name, default: `''`)
- **`minor.suffix`** - Suffix for minor units column (default: `'_minor'`)
- **`money.enabled`** - Enable/disable the Money accessor (default: `true`)
- **`money.suffix`** - Suffix for Money accessor (default: `'_money'`)
- **`formatted.enabled`** - Enable/disable the formatted accessor (default: `true`)
- **`formatted.suffix`** - Suffix for formatted accessor (default: `'_formatted'`)
- **`formatted.format`** - Locale for formatting (default: `'it-IT'`)
- **`autohide`** - Array controlling which fields are hidden in JSON output:
  - `minor` (default: `true`)
  - `money` (default: `true`)
  - `formatted` (default: `false`)
  - `float` (default: `false`)

## Testing

Run the test suite:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](SECURITY.md) on how to report security vulnerabilities.

## Credits

- [fezz02](https://github.com/fezz02)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
