# Contributing to MoneyMagic

Thank you for considering contributing to MoneyMagic! This document provides guidelines and instructions for contributing.

## How to Contribute

Contributions are made via pull requests. Here's how to get started:

1. Fork the repository
2. Create a new branch for your changes (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Ensure tests pass and add tests for new functionality
5. Commit your changes (`git commit -m 'Add some amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

## Package Scope

MoneyMagic is focused on providing Eloquent casts and a model trait for managing money fields stored as integer minor units. The package scope includes:

### In Scope

- Eloquent casts for money attributes
- Model trait for automatic money attribute setup
- Configuration for suffixes, auto-hide behavior, and enabled toggles
- Integration with `Brick\Money` library
- Type-safe monetary domain code

### Out of Scope

The following are explicitly **out of scope** for this package:

- Storing money in database float/double columns
- Currency conversion engine or exchange rate handling
- Payment provider integrations (Stripe, PayPal, etc.)
- Large framework-specific features outside Laravel/Eloquent casting
- Frontend/UI components for displaying money
- Multi-currency wallet management systems

If you're unsure whether a feature fits within the scope, please open an issue to discuss it before submitting a pull request.

## Development Setup

1. Clone your fork: `git clone https://github.com/fezz02/money-magic.git`
2. Install dependencies: `composer install`
3. Run tests: `composer test`

## Quality Standards

### Code Quality

- **Run tests**: All tests must pass (`composer test`)
- **Code style**: Code must follow PSR-12 standards (enforced by Laravel Pint)
- **Type safety**: Maintain strict types and proper type hints
- **PHPStan**: All PHPStan checks must pass (level 5)

### Testing

- **Add tests**: New features must include tests
- **Update tests**: Bug fixes should include regression tests
- **Coverage**: Maintain 100% code coverage
- **Test types**: Include unit tests, integration tests where appropriate

### Pull Request Guidelines

- **Keep changes focused**: One feature or bug fix per pull request
- **Update documentation**: Update README.md if adding new features or changing behavior
- **Update CHANGELOG**: Add an entry to CHANGELOG.md for user-facing changes
- **Write clear commit messages**: Follow conventional commit format when possible
- **Reference issues**: Link to related issues in your pull request description

## Code Style

We use [Laravel Pint](https://laravel.com/docs/pint) for code style enforcement. Run:

```bash
composer lint
```

Or use Pint directly:

```bash
vendor/bin/pint
```

## Testing

Run the full test suite:

```bash
composer test
```

This runs:
- Typo checking
- Code style checks
- PHPStan static analysis
- Type coverage checks
- Unit tests with coverage

## Questions?

If you have questions about contributing, please open an issue for discussion.

Thank you for contributing to MoneyMagic!
