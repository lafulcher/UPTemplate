<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['full_tag_open'] = '<ul class="pagination pagination-sm" style="margin-top: 0; margin-bottom: 0;">';
$config['full_tag_close'] = '</ul>';
$config['first_link'] = 'First';
$config['last_link'] = 'Last';
$config['first_tag_open'] = $config['last_tag_open'] = $config['num_tag_open'] = '<li>';
$config['next_tag_open'] = '<li>';
$config['prev_tag_open'] = '<li>';
$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="active"><span>';
$config['cur_tag_close'] = '</span></li>';
$config['num_links'] = 5;
$config['per_page'] = PER_PAGE;
$config['query_string_segment'] = 'offset';
$config['page_query_string'] = TRUE;
$config['reuse_query_string'] = TRUE;