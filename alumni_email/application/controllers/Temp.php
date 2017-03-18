<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temp extends CI_Controller {

	public function index()
	{
		$data['requests'] = $this->db->get('request')->result_array();
		$body = $this->load->view('temp/index', $data, TRUE);
		$this->load->view('template', array('body' => $body));
	}

	public function view($request_id)
	{
		$this->db->where('request_id', $request_id);
		$data['request'] = $this->db->get('request')->row_array();

		if (empty($data['request']))
		{
			redirect('temp');
		}

		$tables = array('personal','academic','contact','work','exam');
		foreach ($tables as $table)
		{
			$this->db->where('request_id', $request_id);
			$data[$table] = $this->db->get($table)->row_array();
		}

		$tables = array('family','membership','award');
		foreach ($tables as $table)
		{
			if ($table == 'membership')
			{
				$this->db->where('request_id', $request_id);
				$this->db->where('category', MEMBERSHIP_CAT_ALUMNI);
				$data['membership'][MEMBERSHIP_CAT_ALUMNI] = $this->db->get($table)->result_array();

				$this->db->where('request_id', $request_id);
				$this->db->where('category', MEMBERSHIP_CAT_OTHERS);
				$data['membership'][MEMBERSHIP_CAT_OTHERS] = $this->db->get($table)->result_array();
			}
			else
			{
				$this->db->where('request_id', $request_id);
				$data[$table] = $this->db->get($table)->result_array();
			}
		}	

		$this->db->order_by('campus_id');
		$campuses = $this->db->get('campus')->result_array();

		foreach ($campuses as $campus)
		{
			$data['campuses'][$campus['campus_id']] = $campus['campus_name'];
		}

		$body = $this->load->view('temp/view', $data, TRUE);
		$this->load->view('template', array('body' => $body));
	}
}