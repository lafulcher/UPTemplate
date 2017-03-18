<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function pr($content, $die = TRUE)
{
	echo '<pre>';
	print_r($content);
	echo '</pre>';

	if ($die)
	{
		die();
	}
}

function vd($content, $die = TRUE)
{
	echo '<pre>';
	var_dump($content);
	echo '</pre>';

	if ($die)
	{
		die();
	}
}