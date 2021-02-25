jQuery(document).ready(
	function ($) {


		url = peg_ajax.ajaxurl;

		$(document).on(
			"change",
			"#g_form",
			function () {
				if ($('#g_form').val() != 0) {
					$('#all_g_fields').empty();
					$('#all_images_fields').empty();


					//GET IMAGES
					jQuery.ajax({
						url: url,
						type: "post",
						data: {
							action: "return_fileupload_fields",
							id: $('#g_form').val(),
						},
						success: function (response) {
							$('#all_images_fields').prepend(response);
							$('#all_images_fields').removeAttr('disabled');

							 //$("#all_images_fields option:selected").each(function () {
							 //	$(this).removeAttr('selected');
							 //});

							$('#all_images_fields').select2();

						}
					});


					//GET FIELDS
					jQuery.ajax({
						url: url,
						type: "post",
						data: {
							action: "return_form_fields",
							id: $('#g_form').val(),
						},
						success: function (response) {
							$('#all_g_fields').prepend(response);
							$('#all_g_fields').removeAttr('disabled');

							$('#all_g_fields').select2();

						}
					});


				}
			});
	}
);