<?php
/**
 * Uninstall handler for Simple Code Block Highlighter.
 *
 * @package SimpleCodeBlockHighlighter
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'simple_code_block_highlighter_options' );
