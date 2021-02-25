<?php
/**
 * Plugin Name: PDF Export Gform
 * Description:  Allows you to select Gravity Form Entries by date range and export to PDF.
 * Author: Chris Whitcoe
 * Version: 1.1.25
 * Text Domain: peg
 * Domain Path: /languages
 * Plugin URI: http://azmobileapps.com
 * Author URI: https://www.linkedin.com/in/whitcoe
 * WC tested up to: 5.0.0
 */

$message = ' test';

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!defined('PEG_PLUGIN_FILE')) {
    define('PEG_PLUGIN_FILE', __FILE__);
}

if (!defined('PEG_PLUGIN_URL')) {
    define('PEG_PLUGIN_URL', plugin_dir_url(PEG_PLUGIN_FILE));
}

if (!defined('PEG_ABSPATH')) {
    define('PEG_ABSPATH', dirname(__FILE__));
}

if (!defined('PEG_TEMP_DIR')) {
    define('PEG_TEMP_DIR', PEG_ABSPATH . '/templates');
}

if (!defined('PEG_ASSETS_DIR_URL')) {
    define('PEG_ASSETS_DIR_URL', PEG_PLUGIN_URL . 'assets');
}

require_once PEG_ABSPATH . '/helpers.php';
require_once PEG_ABSPATH . '/constants.php';
require_once PEG_ABSPATH . '/includes/class-peg-loader.php';

$pdf = new PDF_HTML();

if (isset($_POST['generate_posts_pdf'])) {
    output_pdf();
}

add_action('admin_menu', 'as_fpdf_create_admin_menu');
function as_fpdf_create_admin_menu()
{
    $hook = add_submenu_page(
        'tools.php',
        'PDF Export Gform',
        'PDF Export Gform',
        'manage_options',
        'peg',
        'as_fpdf_create_admin_page'
    );
}

function output_pdf()
{
    $images_type = ['jpeg', 'jpg', 'gif', 'png'];

    $form_id = 0;
    if (isset($_POST['g_form'])) {
        $form_id = $_POST['g_form'];
    }

    $selected_fields = array();
    if (isset($_POST['all_g_fields'])) {
        $selected_fields = $_POST['all_g_fields'];
    }

    $all_images_fields = array();
    if (isset($_POST['all_images_fields'])) {
        $all_images_fields = $_POST['all_images_fields'];
    }

    $date_from = '';
    if (isset($_POST['date_from'])) {
        $date_from = $_POST['date_from'];
    }

    $date_to = '';
    if (isset($_POST['date_to'])) {
        $date_to = $_POST['date_to'];
    }

    global $pdf;
    $title_line_height = 10;
    $content_line_height = 10;
    $d = 0;

    $entries = GFAPI::get_entries($form_id);
    if ($entries) {
        foreach ($entries as $gfentry) {

            if ($_POST['condition_star'] == 'Unstarred' && $gfentry['is_starred'] == true) {
                continue;
            }

            if ($_POST['condition_star'] == 'Starred' && $gfentry['is_starred'] == false) {
                continue;
            }

            if (!empty($date_from)) {
                if (date('Y-m-d', strtotime($gfentry['date_created'])) < $date_from) {
                    continue;
                }
            }
            if (!empty($date_to)) {
                if (date('Y-m-d', strtotime($gfentry['date_created'])) > $date_to) {
                    continue;
                }
            }

            $pdf->AddPage('P', 'Letter'); //P=Portrait, L=Landscape
            $pdf->SetFont('Arial', '', 13);

            $image_gyp = 0; // WHAT DOES "gyp" mean??
            
            $pdf->Write($content_line_height,'is_starred = ' . $gfentry['is_starred']);
            $pdf->Ln(5);
            
            foreach ($selected_fields as $key => $value) {
                // get the field
                $field = GFFormsModel::get_field($form_id, $value);

                // get the label
                $label = $field->label;

                if ($gfentry[$value] != "") { //DON't print empty values'
                    $pdf->Write($content_line_height, $label . ' = ' . $gfentry[$value]);
                    $pdf->Ln(5);
                    $d++;
                }

               
            }

            //GET THE IMAGES
            foreach ($all_images_fields as $key => $value) {         
                if (in_array(strtolower(pathinfo($gfentry[$value], PATHINFO_EXTENSION)), $images_type)) {
                    //$pdf->Image($gfentry[$value], 200, $image_gyp, 80);
                    //$image_gyp += 30;
                    $pdf->AddPage('P', 'Letter'); //P=Portrait, L=Landscape
                    //$pdf->Image($gfentry[$value], $pdf->GetX(), $pdf->GetY(), -1);
                    $pdf->Image($gfentry[$value],10,10,200);
                    $d++;
                } else {
                    continue;
                }
                
            }
        }

    } else {
        //TO DO: ADD MESSAGE ="No Entries for that criteria
        $message = "No Entries for that criteria.";
    }

    if ($d > 1) {
        $pdf->Output('D', 'gform_data.pdf');
        exit;
    }
}

function as_fpdf_create_admin_page()
{
    $all_forms = GFAPI::get_forms();
    ?>
<div class="wrap">

	<form method="post" id="as-fdpf-form">
	<p>
		<label>Select A Form</label>
		<select id="g_form" name="g_form">
			<option>Select</option>
			<?php
foreach ($all_forms as $form) {
        echo '<option value=' . $form['id'] . '>' . $form['title'] . '</option>';
    }
    ?>
		</select>
	</p>
	<p>
		<label>Select Data Fields</label>
		<select id="all_g_fields" name="all_g_fields[]" multiple="multiple" disabled required>

		</select>
	</p>
	<p>
		<label>Select Images Fields</label>
		<select id="all_images_fields" name="all_images_fields[]" multiple="multiple" disabled >
		</select>
	</p>
	<p>
		<label>Conditional ( Select Starred, Unstarred, or Both)</label>
		<select id="condition_star" name="condition_star" required>
			<option value="Both">Both</option>
			<option value="Starred" selected>Starred</option>
			<option value="Unstarred">Unstarred</option>
		</select>
	</p>
	<p>
		<label>Date Range</label>
		<input type="date" name="date_from">
		-
		<input type="date" name="date_to">
	</p>
		<button class="button button-primary" type="submit" name="generate_posts_pdf" value="generate">Generate PDF</button>
      <p>
     message:    <?php

        echo $message;

        ?>
        </p>

	</form>
  
</div>
	<?php
}
