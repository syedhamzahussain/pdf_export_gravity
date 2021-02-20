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


// require_once PEG_ABSPATH . '/constants.php';
 //require_once PEG_ABSPATH . '/includes/class-peg-loader.php';

$pdf = new PDF_HTML();

if( isset($_POST['generate_posts_pdf'])){
    output_pdf();
}

add_action( 'admin_menu', 'as_fpdf_create_admin_menu' );
function as_fpdf_create_admin_menu() {
    $hook = add_submenu_page(
        'tools.php',
        'Gforms PDF Generator',
        'Gforms PDF Generator',
        'manage_options',
        'peg',
        'as_fpdf_create_admin_page'
    );
}

function output_pdf() {
	$form_id = $_POST['g_form'];
	$selected_fields = $_POST['all_g_fields'];
	$all_images_fields = $_POST['all_images_fields'];

    $posts = get_posts( 'posts_per_page=5' );

    if( ! empty( $posts ) ) {
        global $pdf;
        $title_line_height = 10;
        $content_line_height = 10;

		$entry = GFAPI::get_entries( $form_id );


        foreach( $entry as $post ) {

            $pdf->AddPage();
            $pdf->SetFont( 'Arial', '', 15 );
           
           $image_gyp = 0;
           foreach ($selected_fields as $key => $value) {

           		if ( !in_array( $value, $all_images_fields ) ) {
           			$pdf->Write($content_line_height, $post[$value]);
           			$pdf->Ln(5);
           		}
           		else{
           			$pdf->Image( $post[$value], 100, $image_gyp , 50 );
           			$image_gyp+=80;
           		}
           		
           }
        }
    }

    $pdf->Output('D','atomic_smash_fpdf_tutorial.pdf');
    exit;
}


function as_fpdf_create_admin_page() {
	// $form_id = '1';
	// 	$form = GFAPI::get_form( $form_id );
	// 	$entry = GFAPI::get_entries( $form_id );
	// 	echo "<pre>";
	// 	print_r($form);
	// 	echo "<pre>";
	// 	echo "<pre>";
	// 	print_r($entry);
	// 	echo "<pre>";
	$all_forms = GFAPI::get_forms();
?>
<div class="wrap">
   
	<form method="post" id="as-fdpf-form">
	<p>
		<label>Select A Form</label>
		<select id="g_form" name="g_form">
			<?php
			foreach ($all_forms as $form) {
				echo "<option value=".$form['id'].">".$form['title']."</option>";
			}
			?>
		</select>
	</p>
	<p>
		<label>Select Fields</label>
		<select id="all_g_fields" name="all_g_fields[]" multiple="multiple">
			<?php
			foreach ( $form['fields'] as $field ) {
				echo "<option value=".$field['id'].">".$field->label."</option>";
			}

			?>
		</select>
	</p>
	<p>
		<label>Select Images Field</label>
		<select id="all_images_fields" name="all_images_fields[]" multiple="multiple">
			<?php
			foreach ( $form['fields'] as $field ) {
				echo "<option value=".$field['id'].">".$field->label."</option>";
			}

			?>
		</select>
	</p>
	<p>
		<label>Date Range</label>
		<input type="date" name="">
		-
		<input type="date" name="">
	</p>
        <button class="button button-primary" type="submit" name="generate_posts_pdf" value="generate">Generate PDF</button>
    </form>
</div>
<?php
// echo "<pre>";
// 		print_r($all_forms);
// 		echo "<pre>";
}