<?php
/**
 *
 * Main Plugin files loader.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'PEG_LOADER' ) ) {

	/**
	 *
	 * PEG_LOADER class
	 */
	class PEG_LOADER {

		/**
		 *
		 * Cunstruct function
		 */
		public function __construct() {
			$this->includes();
			add_action( 'admin_enqueue_scripts', array( $this, 'register_backend_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_scripts' ) );
			add_action( 'plugins_loaded', array( $this, 'PEG_load_plugin_textdomain' ) );
		}

		/**
		 *
		 * Includes function.
		 */
		public function includes() {

			if ( wp_doing_ajax() ) {

				// inluding all files those will only use in ajax request.
				require_once PEG_PLUGIN_URL . '/class-peg-ajax.php';
			}

			// require_once PEG_PLUGIN_URL . '/class-peg-ajax.php';
		}

		/**
		 *
		 * Register frontend scripts function.
		 */
		public function register_frontend_scripts() {

				// enqueue plugin styles and scripts.
				wp_enqueue_script( 'peg-script', PEG_ASSETS_DIR_URL . '/front/js/main.js', array( 'jquery' ), true );
				wp_enqueue_style( 'peg-style', PEG_ASSETS_DIR_URL . '/front/css/style.css', true, 'all' );

				// localize script.
				wp_localize_script(
					'peg-script',
					'PEG_ajax',
					array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
					)
				);
		}

		/**
		 *
		 * Register backend scripts function.
		 */
		public function register_backend_scripts() {

			// enqueue plugin styles and scripts.
			wp_enqueue_script( 'select2-script', PEG_ASSETS_DIR_URL . '/admin/select2.min.js', array(), true );
			wp_enqueue_script( 'peg-script', PEG_ASSETS_DIR_URL . '/admin/admin.js', array( 'jquery' ), true );
			wp_enqueue_style( 'peg-style', PEG_ASSETS_DIR_URL . '/admin/style.css', true, 'all' );

			// localize script.
			wp_localize_script(
				'peg-script',
				'PEG_ajax',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);

		}

		/**
		 * Languages loaded.
		 */
		public function PEG_load_plugin_textdomain() {
			load_plugin_textdomain( 'peg', false, basename( PEG_ABSPATH ) . '/languages/' );
		}

	}
	new PEG_LOADER();
}


