# Simple Code Block Highlighter

Simple Code Block Highlighter is a WordPress plugin that enhances core code and preformatted blocks with optional line numbers, a copy button, syntax highlighting, and light or dark themes.

Download from WordPress.org: https://wordpress.org/plugins/simple-code-block-highlighter/

## Security-focused implementation

- Uses the WordPress Settings API for option persistence and nonce handling.
- Requires the `manage_options` capability before rendering the settings page.
- Sanitizes and validates all saved settings before use.
- Escapes admin output with WordPress escaping helpers.
- Uses bundled Highlight.js assets instead of loading a third-party CDN on visitor pages.
- Keeps plugin data on deactivation and removes it only through `uninstall.php`.

## Syntax highlighting

The plugin supports language classes such as `language-html`, `language-css`, `language-javascript`, `language-python`, `language-php`, `language-json`, `language-bash`, and `language-sql`. When no language class is available, Highlight.js auto-detection is used.

Bundled third-party asset:

- Highlight.js `11.11.1`, BSD-3-Clause license, in `assets/vendor/highlightjs/`.

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
