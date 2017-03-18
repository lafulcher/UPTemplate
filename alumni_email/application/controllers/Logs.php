<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller {

	public function index()
	{
		$params = array(
			array(
				'key' => 'search',
				'default' => ''
			),
			array(
				'key' => 'category',
				'default' => 'username',
				'options' => array(
					'username' => 'Username',
					'ip_address' => 'IP Address',
					'module' => 'Module',
					'action' => 'Action',
					'details' => 'Details'
				)
			),
			array(
				'key' => 'request_no',
				'default' => ''
			),
			array(
				'key' => 'start',
				'default' => date('m/01/Y') . ' 12:00 AM',
				'validate' => 'datetime'
			),
			array(
				'key' => 'end',
				'default' => date('m/t/Y') . ' 11:59 PM',
				'validate' => 'datetime'
			),
		);

		$data = custom_prep_search($params, $this->input->get());

		$data['offset'] = (int) ($this->input->get('offset') ? $this->input->get('offset') : '');

		$config['base_url'] = site_url('logs');
		$config['total_rows'] = $data['total_rows'] = $this->log_model->search_logs($data);

		$this->pagination->initialize($config);

		$data['logs'] = $this->log_model->search_logs($data, TRUE);

		$body = $this->load->view('logs/index', $data, TRUE);
		$this->load->view('template', array(
			'title' => 'System Logs',
			'body' => $body
		));
	}
}