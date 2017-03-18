<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function custom_array_keys($array)
{
	return implode(',', array_keys($array));
}

function custom_cast_int($arr, $keys)
{
	foreach ($keys as $key)
	{
		$arr[$key] = isset($arr[$key]) ? (int) $arr[$key] : NULL;
	}

	return $arr;
}

function custom_date($str, $format = 'm/d/Y')
{
	return ($str && $str != '0000-00-00') ? date($format, strtotime($str)) : '';
}

function custom_datetime($str, $format = 'm/d/Y h:i A')
{
	return ($str && $str != '0000-00-00 00:00:00') ? date($format, strtotime($str)) : '';
}

function custom_from_unixtime($unixtime, $format = 'm/d/Y h:i A')
{
	return date($format, $unixtime);
}

function custom_get_alert()
{
	$CI =& get_instance();
	
	if ($CI->input->get('alert'))
	{
		$alert_file = './alerts/' . $CI->input->get('alert') . '.json';
		if (file_exists($alert_file))
		{
			$alert = json_decode(read_file($alert_file), TRUE);
			unlink($alert_file);
			return $alert;
		}		
	}

	return FALSE;
}

function custom_json_response($response)
{
	// clear the old headers
	header_remove();
	// set the actual code
	http_response_code(200);
	// treat this as json
	header('Content-Type: application/json; charset=utf-8');
	header('Status: 200 OK');
	// return the encoded json
	return json_encode($response);
}

function custom_mysql_date($str)
{
	return $str ? date('Y-m-d', strtotime($str)) : '0000-00-00';
}

function custom_mysql_datetime($str)
{
	return $str ? date('Y-m-d H:i:s', strtotime($str)) : '0000-00-00 00:00:00';
}

// http://subinsb.com/php-convert-seconds-hours-minutes-seconds
function custom_prettify_seconds($seconds)
{
  $hours = floor($seconds / 3600);
  $minutes = floor(($seconds / 60) % 60);
  $seconds = $seconds % 60;

  $output = array();
  if ($hours > 0) $output[] = $hours . 'h';
  if ($minutes > 0) $output[] = $minutes . 'm';
  if ($seconds > 0) $output[] = $seconds . 's';
  
  return implode(' ', $output);
}

function custom_print_alert($alert, $print = TRUE)
{
	$output = '';

	if ($alert)
	{
		$output .= '<div class="alert alert-' . $alert['type'] . ' alert-dismissible" role="alert">';
		$output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$output .= $alert['message'];
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

function custom_print_actions($actions)
{
	echo '<div class="btn-row">';
	foreach ($actions as $action)
	{
		echo '<a role="button" class="btn btn-default' . (isset($action['class']) ? ' ' . $action['class'] : '') . '" href="' . $action['url'] . '">' . (isset($action['icon']) ? '<i class="fa fa-' . ($action['icon']) . '"></i> ' : '') . $action['label'] . '</a> ';
	}
	echo '</div>';
}

function custom_print_breadcrumbs($crumbs)
{
	echo '<ol class="breadcrumb">';
  	$count = count($crumbs);
  	$i = 1;
  	foreach ($crumbs as $crumb)
  	{
  		echo '<li' . ($i == $count ? ' class="active"' : '') . '>';
  		echo $i == $count ? '' : '<a href="' . $crumb['url'] . '">';
  		echo $crumb['label'];
  		echo $i == $count ? '' : '</a>';
  		echo '</li>' . "\n";
  		$i++;
  	}
	echo '</ol>';
}

function custom_print_data($data)
{
	echo '<table class="table table-hover table-bordered">';
	echo '<tbody>';
	foreach ($data as $row) {
		echo '<tr>';
		echo '<th class="col-xs-2">' . $row['label'] . '</th>';
		echo '<td style="word-break: break-all;' . (isset($row['style']) ? ' ' . $row['style'] : '') . '" ' . (isset($row['extra']) ? ' ' . $row['extra'] : '' ) . '>' . $row['value'] . '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}

function custom_print_header($header, $subheader = FALSE, $extra = FALSE, $nav = FALSE)
{
	if ($nav)
	{
		echo '<div>';
		echo $nav;
		echo '</div>';
	}
	echo '<h2 '.($extra ? $extra : '').'><span>' . $header . '</span>' . ($subheader ? ' <span class="text-muted">(' . (is_int($subheader) ? number_format($subheader) : $subheader) . ')</span>' : '') . '</h2>';
	echo '<hr/>';
}

function custom_print_subheader($subheader)
{
	echo '<h4 class="text-muted">' . $subheader . '</h4>';
	echo '<hr style="margin-top: 15px; margin-bottom: 15px"/>';
}

function custom_print_table($data)
{
	$table = '';

	$table .= (isset($data['responsive']) && $data['responsive'] === FALSE) ? '' : '<div class="table-responsive">';
	$table .= '<table class="table' . (isset($data['table_class']) ? ' ' . $data['table_class'] : '') . '"' . '>';
	if (isset($data['header']))
	{
		$table .= '<thead>';
		$table .= '<tr>';
		foreach ($data['header'] as $h)
		{
			$extra = '';
			$label = '';

			if (is_array($h))
			{
				$extra = $h['extra'];
				$label = $h['label'];
			}
			else
			{
				$label = $h;
			}

			$table .= '<th ' . $extra . '>' . $label . '</th>';
		}
		$table .= '</tr>';
		$table .= '</thead>';
	}
	$table .= '<tbody>';
	foreach ($data['rows'] as $row) {
		$table .= '<tr ' . (isset($row['extra']) ? ' ' . $row['extra'] : '') . '>';
		$i = 0;
		foreach ($row['cells'] as $cell)
		{
			$extra = '';
			$value = '';

			if (is_array($cell))
			{
				$extra = $cell['extra'];
				$value = $cell['value'];
			}
			else
			{
				$value = $cell;
			}

			$table .= '<td ' . $extra . '>' . (($value OR $value == '0') ? $value : '') . '</td>';
			$i++;
		}
		$table .= '</tr>';
	}
	$table .= '</tbody>';
	$table .= '</table>';
	$table .= (isset($data['responsive']) && $data['responsive'] === FALSE) ? '' : '</div>';

	if (isset($data['return']))
	{
		return $table;
	}
	else
	{
		echo $table;
	}
}

// http://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
function custom_validate_date($date)
{
    $d = DateTime::createFromFormat('m/d/Y', $date);
    return $d && $d->format('m/d/Y') === $date;
}

function custom_validate_datetime($datetime)
{
    $d = DateTime::createFromFormat('m/d/Y h:i A', $datetime);
    return $d && $d->format('m/d/Y h:i A') === $datetime;
}

function custom_write_alert($alert)
{
	$CI =& get_instance();

	$alert_name =  implode('-', array(
		$CI->router->method,
		$CI->session->userdata('user_id'),
		time()
	));

	write_file(
		'./alerts/' . $alert_name . '.json',
		json_encode($alert)
	);

	return $alert_name;
}