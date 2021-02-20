<?php
/**
 *
 * ALL AJAX OF PLUGIN.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'PEG_AJAX' ) ) {

	/**
	 *
	 * PEG_AJAX class
	 */
	class PEG_AJAX {

		/**
		 *
		 * Cunstruct function
		 */
		public function __construct() {
			// add_action( 'wp_ajax_', array( $this, '' ) );
			// add_action( 'wp_ajax_nopriv_', array( $this, '' ) );
		}
	}

	new PEG_AJAX();
}


