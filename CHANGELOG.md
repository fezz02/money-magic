# Changelog

All notable changes to `money-magic` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Support for Laravel 13.x (`illuminate/contracts` constraint widened to `^11.0||^12.0||^13.0`)
- CI matrix now tests against Laravel 11, 12, and 13 with Testbench 9, 10, and 11 respectively

## [0.1.0] - 2025-01-25

### Added
- Initial release
- `HasMoneyAttributes` trait for automatic money attribute setup
- `MoneyCast` for casting to/from `Brick\Money\Money` instances
- `MoneyFloatCast` for float/major units accessor
- `MoneyFormattedCast` for formatted string accessor
- Configurable suffixes for all accessors
- Auto-hide functionality for internal fields in JSON output
- Configuration file with comprehensive options
- Full test coverage (100%)
- PHPStan level 5 compliance

[Unreleased]: https://github.com/fezz02/money-magic/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/fezz02/money-magic/releases/tag/v0.1.0
