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
		}

		/**
		 *
		 * Includes function.
		 */
		public function includes() {

			if ( wp_doing_ajax() ) {

				// inluding all files those will only use in ajax request.
				require_once PEG_ABSPATH . '/includes/class-peg-ajax.php';
			}

		}

		/**
		 *
		 * Register backend scripts function.
		 */
		public function register_backend_scripts() {

			wp_enqueue_script( 'peg-script', PEG_ASSETS_DIR_URL . '/admin/admin.js', array( 'jquery' ), true );
			wp_enqueue_style( 'peg-style', PEG_ASSETS_DIR_URL . '/admin/style.css', true, 'all' );

			// localize script.
			wp_localize_script(
				'peg-script',
				'peg_ajax',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);

		}

	}
	new PEG_LOADER();
}


