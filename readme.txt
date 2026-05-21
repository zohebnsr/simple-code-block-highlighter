=== Simple Code Block Highlighter ===
Contributors: zohebtg
Tags: code, highlighter, syntax, code block, copy button
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 6.5
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Enhances WordPress code blocks with line numbers, copy functionality, and light or dark themes.

== Description ==

Simple Code Block Highlighter enhances the default WordPress code and preformatted blocks. It can show line numbers, add a copy-to-clipboard button, and switch between light and dark display themes.

The plugin uses the WordPress Settings API, validates and sanitizes saved options, escapes admin output, and avoids inline JavaScript-generated HTML for line numbers.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/simple-code-block-highlighter`.
2. Activate the plugin from the WordPress Plugins screen.
3. Go to Settings > Simple Code Block Highlighter to choose display options.

== Frequently Asked Questions ==

= Does this plugin store visitor data? =

No. The plugin only stores administrator-selected display settings in the WordPress options table.

= Does this plugin change the original post content? =

No. Code blocks are enhanced in the browser after the page loads. The saved post content is not modified.

== Changelog ==

= 1.1.0 =
* Hardened settings validation, sanitization, escaping, and capability checks.
* Removed unsafe JavaScript `innerHTML` line-number rendering.
* Added cache-busted asset versions and cleaner copy-button behavior.
* Added GitHub-ready security, CI, ignore, and documentation files.

= 1.0 =
* Initial release.
