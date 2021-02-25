<?php
/**
 *
 * ALL AJAX OF PLUGIN.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('PEG_AJAX')) {

    /**
     *
     * PEG_AJAX class
     */
    class PEG_AJAX
    {

        /**
         *
         * Cunstruct function
         */
        public function __construct()
        {
        
            add_action('wp_ajax_return_form_fields', array($this, 'return_form_fields'));
            add_action('wp_ajax_nopriv_return_form_fields', array($this, 'return_form_fields'));

            add_action('wp_ajax_return_fileupload_fields', array($this, 'return_fileupload_fields'));
            add_action('wp_ajax_nopriv_return_fileupload_fields', array($this, 'return_fileupload_fields'));
        }

        public function return_form_fields()
        {
            $form = GFAPI::get_form($_REQUEST['id']);
            $html = '';
            foreach ($form['fields'] as $field) {
                if ($field['type'] != "section") {
                    if ($field['type'] != "fileupload") { //NOT EQUAL
                        echo "<option value=" . $field['id'] . "  data-type=" . $field['type'] . " selected >" . $field->label . "</option>";
                    }
                }
            }
            wp_die(wp_json_encode($html));
        }

        public function return_fileupload_fields()
        {
            $form = GFAPI::get_form($_REQUEST['id']);
            $html = '';
            foreach ($form['fields'] as $field) {
                if ($field['type'] != "section") {
                    if ($field['type'] == "fileupload") { // EQUAL
                        echo "<option value=" . $field['id'] . "  data-type=" . $field['type'] . " selected >" . $field->label . "</option>";
                    }
                }
            }
            wp_die(wp_json_encode($html));
        }
    }

    new PEG_AJAX();
}
