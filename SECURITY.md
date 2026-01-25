# Security Policy

## Supported Versions

We actively support the following versions of MoneyMagic:

| Version | Supported          |
| ------- | ------------------ |
| 0.1.x   | :white_check_mark: |
| < 0.1.0 | :x:                |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability, please report it via one of the following methods:

1. **GitHub Security Advisory**: [Create a security advisory](https://github.com/fezz02/money-magic/security/advisories/new)
2. **Email**: Contact the maintainer at info@fezz.it

Please include as much of the following information as possible:

- Type of issue (e.g., buffer overflow, SQL injection, cross-site scripting, etc.)
- Full paths of source file(s) related to the manifestation of the issue
- The location of the affected source code (tag/branch/commit or direct URL)
- Any special configuration required to reproduce the issue
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue, including how an attacker might exploit the issue

This information will help us triage your report more quickly.

## Security Best Practices

When using MoneyMagic in your application:

1. **Keep dependencies updated**: Regularly run `composer update` to ensure you're using the latest secure versions
2. **Run security audits**: Use `composer audit` to check for known vulnerabilities in dependencies
3. **Don't store secrets**: This package doesn't handle secrets, but ensure you never store sensitive data in money fields
4. **Validate input**: Always validate and sanitize user input before storing monetary values
5. **Use HTTPS**: Always use HTTPS in production to protect data in transit

## Scope

This security policy applies to:

- Vulnerabilities in MoneyMagic package code
- Vulnerabilities in MoneyMagic dependencies
- Security issues related to how MoneyMagic handles monetary data

## Response Timeline

We aim to:

- Acknowledge receipt of your vulnerability report within 48 hours
- Provide an initial assessment within 7 days
- Keep you informed of our progress throughout the resolution process

Thank you for helping keep MoneyMagic and its users safe!
