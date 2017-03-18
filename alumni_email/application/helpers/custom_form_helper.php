<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Prints a date selector
 *
 * @param array $data required: name, value; optional: disabled, form_group_class, has_error, help_block, label
 */
function custom_print_date_selector($data)
{
	$has_error = (form_error($data['name']) OR (isset($data['has_error']) && $data['has_error']));

	$form_group_class = isset($data['form_group_class']) ? ' ' . $data['form_group_class'] : '';

	echo '<div class="form-group' . ($has_error ? ' has-error' : '') . $form_group_class . '">';

	if (isset($data['label']))
	{
		echo '<label class="control-label" for="' . $data['name'] . '">' . $data['label'] . '</label>';
	}

	echo '<div class="input-group date" id="' . $data['name'] . '">';

	$input = array(
		'name' => $data['name'],
		'value' => $data['value'],
		'type' => 'text',
		'class' => 'form-control',
	);

	if (isset($data['disabled']))
	{
		$input['disabled'] = 'disabled';
	}

	echo form_input($input);

	echo '<span class="input-group-addon">';
	echo '<span class="glyphicon glyphicon-calendar"></span>';
	echo '</span>';

	echo '</div>';

	if (isset($data['help_block']))
	{
		echo '<span class="help-block">' . $data['help_block'] . '</span>';
	}

	echo '</div>';

	echo '<script type="text/javascript">';
	echo '$(document).ready(function(){';
	echo '$("#'.$data['name'].'").datetimepicker({';
  	echo 'useStrict: true,';
  	echo 'format: "MM/DD/YYYY' . (isset($data['include_time']) ? ' hh:mm A' : '') . '"';
	echo '});';
	echo '});';
	echo '</script>';
}

/**
 * Prints a dropdown
 *
 * @param array $data required: name, options, selected; optional: form_group_class, has_error, help_block, id, label
 */
function custom_print_dropdown($data)
{
	$has_error = (form_error($data['name']) OR (isset($data['has_error']) && $data['has_error']));

	$form_group_class = isset($data['form_group_class']) ? ' ' . $data['form_group_class'] : '';

	echo '<div class="form-group' . ($has_error ? ' has-error' : '') . $form_group_class . '">';

	if (isset($data['label']))
	{
		echo '<label class="control-label" for="' . $data['name'] . '">' . $data['label'] . '</label>';
	}

	echo form_dropdown($data['name'], $data['options'], $data['selected'], 'class="form-control" id="' . (isset($data['id']) ? $data['id'] : $data['name']) . '"' . (isset($data['extra']) ? ' ' . $data['extra'] : ''));

	if (isset($data['help_block']))
	{
		echo '<span class="help-block">' . $data['help_block'] . '</span>';
	}

	echo '</div>';
}

function custom_print_errors($print = TRUE)
{
	$output = '';

	if (validation_errors())
	{
		$output .= '<div class="alert alert-danger alert-dismissible aligned-list" role="alert">';
		$output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$output .= '<ul class="list-unstyled" style="margin-bottom: 0">';
		$output .= validation_errors('<li>', '</li>');
		$output .= '</ul>';
		$output .= '</div>';
	}

	if ($print)
	{
		echo $output;
	}
	else
	{
		return $output;
	}
}

/**
 * Prints an input field
 *
 * @param array $data required: input[name, value]; optional: form_group_class, has_error, help_block, id, label
 */
function custom_print_input($data)
{
	$has_error = (form_error($data['input']['name']) OR (isset($data['has_error']) && $data['has_error']));

	$form_group_class = isset($data['form_group_class']) ? ' ' . $data['form_group_class'] : '';

	echo '<div class="form-group' . ($has_error ? ' has-error' : '') . $form_group_class . '">';

	if (isset($data['label']))
	{
		echo '<label class="control-label" for="' . $data['input']['name'] . '">' . $data['label'] . '</label>';
	}

	$data['input']['class'] = 'form-control' . (isset($data['input']['class']) ? ' ' . $data['input']['class'] : '');
	
	if ( ! isset($data['input']['id']))
	{
		$data['input']['id'] = $data['input']['name'];
	}

	if ( ! isset($data['input']['type']))
	{
		$data['input']['type'] = 'text';
	}

	echo form_input($data['input']);

	if (isset($data['help_block']))
	{
		echo '<span class="help-block">' . $data['help_block'] . '</span>';
	}

	echo '</div>';
}

function custom_print_prompt(
	$message,
	$title = FALSE,
	$icon = FALSE
)
{
	echo ($title ? '<h4 style="margin-top: 0" class="text-danger">' . ($icon ? '<i class="fa fa-' . $icon . '"></i> ' : '') . $title . '</h4>' : '') . '<strong class="text-danger">' . $message . '</strong>';
}

/**
 * Prints a radio group
 *
 * @param array $data required: name, choices; optional: label, has_error
 */
function custom_print_radio_group($data)
{
	$has_error = (form_error($data['name']) OR (isset($data['has_error']) && $data['has_error']));

	echo '<div class="form-group' . ($has_error ? ' has-error' : '') . '">';
	
	if (isset($data['label']))
	{
		echo '<label class="control-label">' . $data['label'] . '</label>';
	}

	$first = TRUE;
	foreach ($data['choices'] as $choice)
	{
		if ($first && $data['label'])
  		{
  			$first = FALSE;
  			echo '<div class="radio no-top-margin">';
  		}
  		else
  		{
  			echo '<div class="radio">';
  		}

  		echo '<label>';
  		echo form_radio($data['name'], $choice['value'], isset($choice['checked']) ? $choice['checked'] : FALSE, $choice['extra']);
    	echo $choice['label'];
  		echo '</label>';
		echo '</div>';
	}
	echo '</div>';
}

/**
 * Prints a textarea
 *
 * @param array $data required: input[name, value]; optional: form_group_class, has_error, help_block, id, label
 */
function custom_print_textarea($data)
{
	$has_error = (form_error($data['textarea']['name']) OR (isset($data['has_error']) && $data['has_error']));

	$form_group_class = isset($data['form_group_class']) ? ' ' . $data['form_group_class'] : '';

	echo '<div class="form-group' . ($has_error ? ' has-error' : '') . $form_group_class . '">';

	if (isset($data['label']))
	{
		echo '<label class="control-label" for="' . $data['textarea']['name'] . '">' . $data['label'] . '</label>';
	}

	$data['textarea']['class'] = 'form-control' . (isset($data['textarea']['class']) ? ' ' . $data['textarea']['class'] : '');
	
	if ( ! isset($data['textarea']['id']))
	{
		$data['textarea']['id'] = $data['textarea']['name'];
	}

	$data['textarea']['style'] = 'resize:none';

	echo form_textarea($data['textarea']);

	if (isset($data['textarea']['maxlength']))
	{
		echo '<div><small class="text-muted"><span id="' . $data['textarea']['id'] . '_count" >' . $data['textarea']['maxlength'] . '</span> characters remaining</small></div>';

		echo "<script type='text/javascript'>
		$(document).ready(function() {
			var text_max = " . $data['textarea']['maxlength'] . ";
			var init_length = $('#" . $data['textarea']['id'] . "').val().length;
			$('#" . $data['textarea']['id'] . "_count').html(text_max - init_length);

			$('#" . $data['textarea']['id'] . "').keyup(function() {
				var text_length = $('#" . $data['textarea']['id'] . "').val().length;
				$('#" . $data['textarea']['id'] . "_count').html(text_max - text_length);
			});
		});
		</script>";
	}

	if (isset($data['help_block']))
	{
		echo '<span class="help-block">' . $data['help_block'] . '</span>';
	}

	echo '</div>';
}

/**
 * Prints an upload inout
 *
 * @param array $data required: name, upload_failed; optional: label, help_block
 */
function custom_print_upload($data)
{
	echo '<script src="' . base_url('public/jasny-bootstrap/js/jasny-bootstrap.min.js') . '"></script>';
	echo '<link href="' . base_url("public/jasny-bootstrap/css/jasny-bootstrap.min.css") . '" rel="stylesheet">';

	echo '<div class="form-group' . ($data['upload_failed'] ? ' has-error' : '') . '">';
	
	if (isset($data['label']))
	{
		echo '<label class="control-label">' . $data['label'] . '</label>';
	}

	echo '<div class="fileinput fileinput-new input-group" data-provides="fileinput" ' . (isset($data['help_block']) ? 'style="margin-bottom: 0"' : 'style="margin-bottom: 15px"') . '>';
	echo '<div class="form-control" data-trigger="fileinput" ' . ($data['upload_failed'] ? 'style="border-color: #A94442"' : '') . '>';
	echo '<i class="glyphicon glyphicon-file fileinput-exists"></i><span class="fileinput-filename"></span>';
	echo '</div>';
	echo '<span class="input-group-addon btn btn-default btn-file" ' . ($data['upload_failed'] ? 'style="border-color: #A94442"' : '') . '><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span>';
	echo '<input type="file" name="' . $data['name'] . '" id="' . (isset($data['id']) ? $data['id'] : $data['name']) . '"' . (isset($data['extra']) ? ' ' . $data['extra'] : '') . '/>';
	echo '</span>';
	echo '<a href="#" class="input-group-addon btn btn-default fileinput-exists" ' . ($data['upload_failed'] ? 'style="border-color: #A94442"' : '') . ' data-dismiss="fileinput">Remove</a>';

	echo '</div>';

	if (isset($data['help_block']))
	{
		echo '<span class="help-block">' . $data['help_block'] . '</span>';
	}

	echo '</div>';
}

function custom_print_upload_errors($upload)
{
	if ($upload['failed'])
	{
		echo '<div class="alert alert-danger alert-dismissible aligned-list" role="alert">';
		echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		echo $upload['error'];
		echo '</div>';
	}
}