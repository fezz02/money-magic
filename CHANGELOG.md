# Changelog

All notable changes to `money-magic` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/fezz02/money-magic/compare/v0.1.2...HEAD)

### Added

- Support for Laravel 13.x (`illuminate/contracts` constraint widened to `^11.0||^12.0||^13.0`)
- CI matrix now tests against Laravel 11, 12, and 13 with Testbench 9, 10, and 11 respectively

## [0.1.0](https://github.com/fezz02/money-magic/releases/tag/v0.1.0) - 2025-01-25

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

## [v0.1.2](https://github.com/fezz02/money-magic/compare/v0.1.1...v0.1.2) - 2026-05-01

### Fixed

- v0.1.1 was tagged from a stale commit and does not contain the Laravel 13 support changes. v0.1.2 is the correct release with L13 support. Existing v0.1.0 / v0.1.1 consumers are unaffected — no breaking change, no API change.

### Added (intended for v0.1.1)

- Support for Laravel 13.x (`illuminate/contracts` constraint widened to `^11.0||^12.0||^13.0`)
- CI matrix tests against Laravel 11, 12, and 13 with Testbench 9, 10, and 11 respectively
- CI: skip Aspell install on Windows runner

### Notes

- No API or config changes. Existing Laravel 11 / 12 consumers can upgrade transparently.
- Lower bounds unchanged: PHP `^8.3`, Laravel `^11.0`.

## [v0.1.1](https://github.com/fezz02/money-magic/compare/v0.1.0...v0.1.1) - 2026-05-01

### Added

- Support for Laravel 13.x (`illuminate/contracts` constraint widened to `^11.0||^12.0||^13.0`)
- CI matrix now tests against Laravel 11, 12, and 13 with Testbench 9, 10, and 11 respectively

### Fixed

- CI: skip Aspell install on Windows runner (apt-get unavailable on `windows-latest`)

### Notes

- No API or config changes. Existing Laravel 11 / 12 consumers can upgrade transparently.
- Lower bounds unchanged: PHP `^8.3`, Laravel `^11.0`.
