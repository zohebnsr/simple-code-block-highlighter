<?php
/**
 * Plugin Name: Simple Code Block Highlighter
 * Plugin URI: https://www.techgrapple.com/simple-code-block-highlighter-free-plugin-for-wordpress/
 * Description: Enhances the default WordPress code block with line numbers, copy functionality, and theme options.
 * Version: 1.0
 * Author: TechGrapple
 * Author URI: https://www.zoheb.org/
 */

defined('ABSPATH') or die('No script kiddies please!');

function simple_code_block_highlighter_assets() {
    $options = get_option('simple_code_block_highlighter_options');
    ?>
    <style>
        .custom-code-block {
            position: relative;
            background-color: <?= $options['theme'] === 'dark' ? '#333' : '#f4f4f4'; ?>;
            color: <?= $options['theme'] === 'dark' ? '#fff' : '#333'; ?>;
            padding: 10px;
            overflow: auto;
            font-family: monospace; /* Ensures consistent spacing */
        }
        .line-numbers-rows {
            position: absolute;
            left: 0;
            top: 0;
            padding-right: 10px;
            text-align: right;
            border-right: 1px solid #ddd;
            padding-right: 10px;
            user-select: none; /* Prevents line numbers from being selected */
        }
        .custom-code-content {
            display: block; /* Ensures it doesn't inherit 'pre' formatting */
            white-space: pre-wrap; /* Maintains formatting */
            margin-left: 4em; /* Offset for line numbers */
        }
        button.copy-button {
            position: absolute;
            right: 10px;
            top: 10px;
        }
    </style>
    <?php
    wp_enqueue_script('simple-code-block-highlighter-script', plugins_url('script.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('simple-code-block-highlighter-script', 'codeBlockSettings', array(
        'line_numbers' => $options['line_numbers'],
        'theme' => $options['theme']
    ));
}

add_action('wp_enqueue_scripts', 'simple_code_block_highlighter_assets');

function simple_code_block_highlighter_activate() {
    $default_options = ['line_numbers' => 'on', 'theme' => 'light'];
    add_option('simple_code_block_highlighter_options', $default_options);
}

register_activation_hook(__FILE__, 'simple_code_block_highlighter_activate');

function simple_code_block_highlighter_deactivate() {
    delete_option('simple_code_block_highlighter_options');
}

register_deactivation_hook(__FILE__, 'simple_code_block_highlighter_deactivate');

function simple_code_block_highlighter_menu() {
    add_options_page('Simple Code Block Highlighter Settings', 'Simple Code Block Highlighter', 'manage_options', 'simple-code-block-highlighter', 'simple_code_block_highlighter_settings_page');
}

add_action('admin_menu', 'simple_code_block_highlighter_menu');

function simple_code_block_highlighter_settings_page() {
    ?>
    <div class="wrap">
        <h2>Simple Code Block Highlighter Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('simple_code_block_highlighter_options'); ?>
            <?php do_settings_sections('simple-code-block-highlighter'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function simple_code_block_highlighter_register_settings() {
    register_setting('simple_code_block_highlighter_options', 'simple_code_block_highlighter_options', 'simple_code_block_highlighter_options_validate');
    add_settings_section('main_section', 'Main Settings', 'simple_code_block_highlighter_section_text', 'simple-code-block-highlighter');
    add_settings_field('line_numbers', 'Enable Line Numbers', 'simple_code_block_highlighter_setting_line_numbers', 'simple-code-block-highlighter', 'main_section');
    add_settings_field('theme', 'Select Theme', 'simple_code_block_highlighter_setting_theme', 'simple-code-block-highlighter', 'main_section');
}

function simple_code_block_highlighter_section_text() {
    echo '<p>Main description of this section here.</p>';
}

function simple_code_block_highlighter_setting_line_numbers() {
    $options = get_option('simple_code_block_highlighter_options');
    echo "<input id='line_numbers' name='simple_code_block_highlighter_options[line_numbers]' type='checkbox' " . checked('on', $options['line_numbers'], false) . " />";
}

function simple_code_block_highlighter_setting_theme() {
    $options = get_option('simple_code_block_highlighter_options');
    echo "<select id='theme' name='simple_code_block_highlighter_options[theme]'>";
    echo "<option value='light'" . selected('light', $options['theme'], false) . ">Light</option>";
    echo "<option value='dark'" . selected('dark', $options['theme'], false) . ">Dark</option>";
    echo "</select>";
}

function simple_code_block_highlighter_options_validate($input) {
    $newinput['line_numbers'] = trim($input['line_numbers']) === 'on' ? 'on' : 'off';
    $newinput['theme'] = in_array($input['theme'], ['light', 'dark']) ? $input['theme'] : 'light';
    return $newinput;
}

add_action('admin_init', 'simple_code_block_highlighter_register_settings');
