<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function custom_prep_search($params, $get)
{
	$data = array(
		'options' => array()
	);
	
	foreach ($params as $param)
	{
		$data['query'][$param['key']] = isset($get[$param['key']]) && $get[$param['key']] ? trim($get[$param['key']]) : $param['default'];

		if (isset($param['options']))
		{
			$data['options'][$param['key']] = $param['options'];
			if ( ! in_array($data['query'][$param['key']], array_keys($param['options'])))
			{
				$data['query'][$param['key']] = $param['default'];
			}
		}

		if (isset($param['validate']))
		{
			if (
				($param['validate'] == 'date' && ! custom_validate_date($data['query'][$param['key']]))
				OR ($param['validate'] == 'datetime' && ! custom_validate_datetime($data['query'][$param['key']]))
			)
			{
				$data['query'][$param['key']] = $param['default'];
			}
		}
	}

	return $data;
}

function custom_replace_search($search)
{
	$search = $search;
	if (substr($search, 0, 1) == '*' && substr($search, strlen($search) - 1, 1) == '*')
	{
		$search = substr($search, 1, strlen($search) - 2);
		return '%' . $search . '%';
	}
	elseif (substr($search, 0, 1) == '*' && substr($search, strlen($search) - 1, 1) != '*')
	{
		$search = substr($search, 1);
		return '%' . $search;
	}
	elseif (substr($search, 0, 1) != '*' && substr($search, strlen($search) - 1, 1) == '*')
	{
		$search = substr($search, 0, strlen($search) - 1);
		return $search . '%';
	}
	return $search;
}

function custom_strip_search($search)
{
	if (substr($search, 0, 1) == '*' && substr($search, strlen($search) - 1, 1) == '*')
	{
		return substr($search, 1, strlen($search) - 2);
	}
	elseif (substr($search, 0, 1) == '*' && substr($search, strlen($search) - 1, 1) != '*')
	{
		return substr($search, 1);
	}
	elseif (substr($search, 0, 1) != '*' && substr($search, strlen($search) - 1, 1) == '*')
	{
		return substr($search, 0, strlen($search) - 1);
	}
	return $search;
}

function custom_wild_search($search)
{
	if (substr($search, 0, 1) == '*' && substr($search, strlen($search) - 1, 1) == '*')
	{
		return 'both';
	}
	elseif (substr($search, 0, 1) == '*' && substr($search, strlen($search) - 1, 1) != '*')
	{
		return 'before';
	}
	elseif (substr($search, 0, 1) != '*' && substr($search, strlen($search) - 1, 1) == '*')
	{
		return 'after';
	}
	return 'none';
}