# Simple Code Block Highlighter

Simple Code Block Highlighter is a WordPress plugin that enhances core code and preformatted blocks with optional line numbers, a copy button, and light or dark themes.

## Security-focused implementation

- Uses the WordPress Settings API for option persistence and nonce handling.
- Requires the `manage_options` capability before rendering the settings page.
- Sanitizes and validates all saved settings before use.
- Escapes admin output with WordPress escaping helpers.
- Avoids JavaScript `innerHTML` rendering for code-derived UI.
- Keeps plugin data on deactivation and removes it only through `uninstall.php`.

## Installation

1. Upload this folder to `wp-content/plugins/simple-code-block-highlighter`.
2. Activate the plugin in WordPress.
3. Open Settings > Simple Code Block Highlighter.

## Development checks

Run the lightweight local checks before committing:

```bash
composer install
composer phpcs
php -l simple-code-block-highlighter.php
php -l uninstall.php
node --check script.js
```

GitHub Actions runs PHP syntax checks, WordPress Coding Standards, PHP compatibility checks, and JavaScript syntax checks on pushes and pull requests.

## Security reports

Please follow the reporting process in [SECURITY.md](SECURITY.md).

## License

GPLv2 or later. See [LICENSE](LICENSE).
