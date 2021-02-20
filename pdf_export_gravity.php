<?php
/**
 * Plugin Name: PDF Export Gform
 * Description: Give you feature to export gravity forms data.
 * Author: Syed Hamza Hussain
 * Version: 1.1.0.0
 * Text Domain: peg
 * Domain Path: /languages
 * Plugin URI: #
 * Author URI: #
 * WC tested up to: 5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'PEG_PLUGIN_FILE' ) ) {
	define( 'PEG_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'PEG_PLUGIN_URL' ) ) {
	define( 'PEG_PLUGIN_URL', plugin_dir_url( PEG_PLUGIN_FILE ) );
}

if ( ! defined( 'PEG_ABSPATH' ) ) {
	define( 'PEG_ABSPATH', dirname( __FILE__ ) );
}

if ( ! defined( 'PEG_TEMP_DIR' ) ) {
	define( 'PEG_TEMP_DIR', PEG_ABSPATH . '/templates' );
}

if ( ! defined( 'PEG_ASSETS_DIR_URL' ) ) {
	define( 'PEG_ASSETS_DIR_URL', PEG_PLUGIN_URL . 'assets' );
}

require_once PEG_ABSPATH . '/helpers.php';
require_once PEG_ABSPATH . '/constants.php';
require_once PEG_ABSPATH . '/includes/class-peg-loader.php';

