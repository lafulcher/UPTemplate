<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function index()
	{
		$body = $this->load->view('dashboard/index', '', TRUE);
		$this->load->view('template', array(
			'title' => 'Dashboard',
			'body' => $body
		));
	}
}