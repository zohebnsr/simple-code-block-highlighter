<?php
/**
 * Plugin Name: Simple Code Block Highlighter
 * Plugin URI: https://www.techgrapple.com/simple-code-block-highlighter-free-plugin-for-wordpress/
 * Description: Enhances the default WordPress code block with line numbers, copy functionality, and theme options.
 * Version: 1.1.0
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Author: TechGrapple
 * Author URI: https://www.zoheb.org/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-code-block-highlighter
 *
 * @package SimpleCodeBlockHighlighter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SIMPLE_CODE_BLOCK_HIGHLIGHTER_VERSION', '1.1.0' );
define( 'SIMPLE_CODE_BLOCK_HIGHLIGHTER_OPTION', 'simple_code_block_highlighter_options' );

/**
 * Returns the default plugin options.
 *
 * @return array<string, string>
 */
function simple_code_block_highlighter_default_options() {
	return array(
		'line_numbers' => 'on',
		'theme'        => 'light',
	);
}

/**
 * Returns sanitized plugin options merged with defaults.
 *
 * @return array<string, string>
 */
function simple_code_block_highlighter_get_options() {
	$options = get_option( SIMPLE_CODE_BLOCK_HIGHLIGHTER_OPTION, array() );

	if ( ! is_array( $options ) ) {
		$options = array();
	}

	return simple_code_block_highlighter_sanitize_options( $options );
}

/**
 * Enqueue frontend assets.
 *
 * @return void
 */
function simple_code_block_highlighter_assets() {
	$options      = simple_code_block_highlighter_get_options();
	$style_path   = plugin_dir_path( __FILE__ ) . 'style.css';
	$script_path  = plugin_dir_path( __FILE__ ) . 'script.js';
	$style_url    = plugin_dir_url( __FILE__ ) . 'style.css';
	$script_url   = plugin_dir_url( __FILE__ ) . 'script.js';
	$style_ver    = file_exists( $style_path ) ? (string) filemtime( $style_path ) : SIMPLE_CODE_BLOCK_HIGHLIGHTER_VERSION;
	$script_ver   = file_exists( $script_path ) ? (string) filemtime( $script_path ) : SIMPLE_CODE_BLOCK_HIGHLIGHTER_VERSION;
	$dependencies = array();

	wp_enqueue_style(
		'simple-code-block-highlighter-style',
		$style_url,
		array(),
		$style_ver
	);

	wp_enqueue_script(
		'simple-code-block-highlighter-script',
		$script_url,
		$dependencies,
		$script_ver,
		true
	);

	wp_localize_script(
		'simple-code-block-highlighter-script',
		'codeBlockSettings',
		array(
			'line_numbers'     => $options['line_numbers'],
			'theme'            => $options['theme'],
			'copy_text'        => esc_html__( 'Copy', 'simple-code-block-highlighter' ),
			'copied_text'      => esc_html__( 'Copied', 'simple-code-block-highlighter' ),
			'copy_failed_text' => esc_html__( 'Copy failed', 'simple-code-block-highlighter' ),
			'copy_label'       => esc_html__( 'Copy code to clipboard', 'simple-code-block-highlighter' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'simple_code_block_highlighter_assets' );

/**
 * Add default options when the plugin is activated.
 *
 * @return void
 */
function simple_code_block_highlighter_activate() {
	$existing_options = get_option( SIMPLE_CODE_BLOCK_HIGHLIGHTER_OPTION, null );

	if ( null === $existing_options ) {
		add_option( SIMPLE_CODE_BLOCK_HIGHLIGHTER_OPTION, simple_code_block_highlighter_default_options() );
		return;
	}

	update_option( SIMPLE_CODE_BLOCK_HIGHLIGHTER_OPTION, simple_code_block_highlighter_sanitize_options( $existing_options ) );
}
register_activation_hook( __FILE__, 'simple_code_block_highlighter_activate' );

/**
 * Register the settings page under Settings.
 *
 * @return void
 */
function simple_code_block_highlighter_menu() {
	add_options_page(
		esc_html__( 'Simple Code Block Highlighter Settings', 'simple-code-block-highlighter' ),
		esc_html__( 'Simple Code Block Highlighter', 'simple-code-block-highlighter' ),
		'manage_options',
		'simple-code-block-highlighter',
		'simple_code_block_highlighter_settings_page'
	);
}
add_action( 'admin_menu', 'simple_code_block_highlighter_menu' );

/**
 * Render the settings page.
 *
 * @return void
 */
function simple_code_block_highlighter_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'simple-code-block-highlighter' ) );
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Simple Code Block Highlighter Settings', 'simple-code-block-highlighter' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'simple_code_block_highlighter_options' );
			do_settings_sections( 'simple-code-block-highlighter' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Register settings, sections, and fields.
 *
 * @return void
 */
function simple_code_block_highlighter_register_settings() {
	register_setting(
		'simple_code_block_highlighter_options',
		SIMPLE_CODE_BLOCK_HIGHLIGHTER_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'simple_code_block_highlighter_sanitize_options',
			'default'           => simple_code_block_highlighter_default_options(),
		)
	);

	add_settings_section(
		'main_section',
		esc_html__( 'Display Settings', 'simple-code-block-highlighter' ),
		'simple_code_block_highlighter_section_text',
		'simple-code-block-highlighter'
	);

	add_settings_field(
		'line_numbers',
		esc_html__( 'Enable Line Numbers', 'simple-code-block-highlighter' ),
		'simple_code_block_highlighter_setting_line_numbers',
		'simple-code-block-highlighter',
		'main_section'
	);

	add_settings_field(
		'theme',
		esc_html__( 'Theme', 'simple-code-block-highlighter' ),
		'simple_code_block_highlighter_setting_theme',
		'simple-code-block-highlighter',
		'main_section'
	);
}
add_action( 'admin_init', 'simple_code_block_highlighter_register_settings' );

/**
 * Render the settings section description.
 *
 * @return void
 */
function simple_code_block_highlighter_section_text() {
	echo '<p>' . esc_html__( 'Choose how WordPress code blocks are displayed on the front end.', 'simple-code-block-highlighter' ) . '</p>';
}

/**
 * Render the line number setting.
 *
 * @return void
 */
function simple_code_block_highlighter_setting_line_numbers() {
	$options = simple_code_block_highlighter_get_options();
	?>
	<label for="line_numbers">
		<input
			id="line_numbers"
			name="<?php echo esc_attr( SIMPLE_CODE_BLOCK_HIGHLIGHTER_OPTION ); ?>[line_numbers]"
			type="checkbox"
			value="on"
			<?php checked( 'on', $options['line_numbers'] ); ?>
		/>
		<?php echo esc_html__( 'Show line numbers beside each code block.', 'simple-code-block-highlighter' ); ?>
	</label>
	<?php
}

/**
 * Render the theme setting.
 *
 * @return void
 */
function simple_code_block_highlighter_setting_theme() {
	$options = simple_code_block_highlighter_get_options();
	?>
	<select id="theme" name="<?php echo esc_attr( SIMPLE_CODE_BLOCK_HIGHLIGHTER_OPTION ); ?>[theme]">
		<option value="light" <?php selected( 'light', $options['theme'] ); ?>>
			<?php echo esc_html__( 'Light', 'simple-code-block-highlighter' ); ?>
		</option>
		<option value="dark" <?php selected( 'dark', $options['theme'] ); ?>>
			<?php echo esc_html__( 'Dark', 'simple-code-block-highlighter' ); ?>
		</option>
	</select>
	<?php
}

/**
 * Sanitize saved settings.
 *
 * @param mixed $input Raw options.
 * @return array<string, string>
 */
function simple_code_block_highlighter_sanitize_options( $input ) {
	$defaults = simple_code_block_highlighter_default_options();

	if ( ! is_array( $input ) ) {
		return $defaults;
	}

	$line_numbers = isset( $input['line_numbers'] ) && is_scalar( $input['line_numbers'] )
		? sanitize_key( wp_unslash( (string) $input['line_numbers'] ) )
		: 'off';
	$theme        = isset( $input['theme'] ) && is_scalar( $input['theme'] )
		? sanitize_key( wp_unslash( (string) $input['theme'] ) )
		: $defaults['theme'];

	return array(
		'line_numbers' => 'on' === $line_numbers ? 'on' : 'off',
		'theme'        => in_array( $theme, array( 'light', 'dark' ), true ) ? $theme : $defaults['theme'],
	);
}
