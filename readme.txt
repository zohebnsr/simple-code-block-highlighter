=== Simple Code Block Highlighter ===
Contributors: zohebtg
Tags: code, highlighter, syntax, code block, copy button
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 6.5
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Enhances WordPress code blocks with line numbers, copy functionality, syntax highlighting, and light or dark themes.

== Description ==

Simple Code Block Highlighter enhances the default WordPress code and preformatted blocks. It can show line numbers, add a copy-to-clipboard button, switch between light and dark display themes, and color code by language with bundled Highlight.js syntax highlighting.

The plugin uses the WordPress Settings API, validates and sanitizes saved options, escapes admin output, and avoids inline JavaScript-generated HTML for line numbers. Syntax highlighting is served from the plugin files and does not load a third-party CDN on visitor pages.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/simple-code-block-highlighter`.
2. Activate the plugin from the WordPress Plugins screen.
3. Go to Settings > Simple Code Block Highlighter to choose display options.

== Frequently Asked Questions ==

= Does this plugin store visitor data? =

No. The plugin only stores administrator-selected display settings in the WordPress options table.

= Does this plugin change the original post content? =

No. Code blocks are enhanced in the browser after the page loads. The saved post content is not modified.

= How do I set a code language? =

Add a class such as `language-html`, `language-css`, `language-javascript`, `language-python`, `language-php`, or `language-json` to a WordPress code block. If no language class is found, the plugin tries to auto-detect the language.

== Third-party Libraries ==

This plugin bundles Highlight.js 11.11.1 for syntax highlighting.

Highlight.js
Source: https://github.com/highlightjs/highlight.js
License: BSD 3-Clause
License file included at: assets/vendor/highlightjs/LICENSE

== Screenshots ==

1. Frontend code block with syntax highlighting, line numbers, and copy button.
2. Plugin settings page with display options.

== Changelog ==

= 1.2.0 =
* Added bundled Highlight.js syntax highlighting for common languages.
* Added automatic language detection with support for `language-*` and `lang-*` classes.
* Added a setting to enable or disable syntax highlighting.

= 1.1.0 =
* Hardened settings validation, sanitization, escaping, and capability checks.
* Removed unsafe JavaScript `innerHTML` line-number rendering.
* Added cache-busted asset versions and cleaner copy-button behavior.
* Added GitHub-ready security, CI, ignore, and documentation files.

= 1.0 =
* Initial release.
