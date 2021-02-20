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

			add_action( 'wp_ajax_return_form_field', array( $this, 'return_form_field' ) );
			add_action( 'wp_ajax_nopriv_return_form_field', array( $this, 'return_form_field' ) );
		}

		public function return_form_field(){

			$form = GFAPI::get_form( $_REQUEST['id'] );
			$html = '';

			foreach ( $form['fields'] as $field ) {
				echo "<option value=".$field['id'].">".$field->label."</option>";
			}
			
			wp_die(wp_json_encode($html));
		}
	}

	new PEG_AJAX();
}


