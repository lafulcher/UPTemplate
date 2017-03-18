<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {

	public function log($details, $extra = array())
	{
		$data = array(
			'username' => isset($extra['username']) ? $extra['username'] : $this->session->userdata('username'),
			'request_no' => isset($extra['request_no']) ? $extra['request_no'] : '',
			'module' => isset($extra['module']) ? $extra['module'] : $this->router->class,
			'action' => isset($extra['action']) ? $extra['action'] : $this->router->method,
			'details' => json_encode(is_array($details) ? $details : array($details)),
			'session_id' => session_id(),
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'user_agent' => $this->input->user_agent()
		);

		$this->db->insert('log', $data);
	}

	public function search_logs($data, $return_rows = FALSE)
	{
		$this->db->select('
			log_id,
			username,
			module,
			action,
			ip_address,
			timestamp,
			request_no
		');
		$this->db->from('log');

		$wild_search = custom_wild_search($data['query']['search']);

		$do_like = TRUE;

		if ($data['query']['category'] == 'username' and $wild_search == 'none')
		{
			$this->db->where('username', $data['query']['search']);
			$do_like = FALSE;
		}

		if ($data['query']['category'] == 'action' and $wild_search == 'none')
		{
			$this->db->where('action', $data['query']['search']);
			$do_like = FALSE;
		}

		if ($data['query']['start'] && $data['query']['end'])
		{
			$this->db->where('timestamp >=', $data['query']['start']);
			$this->db->where('timestamp >=', $data['query']['end']);
		}

		if ($do_like)
		{
			$this->db->like($data['query']['category'], custom_strip_search($data['query']['search']), $wild_search);
		}

		if ($data['query']['request_no'])
		{
			$this->db->where('request_no', $data['query']['request_no']);
		}

		if ($return_rows)
		{
			$this->db->limit(PER_PAGE, $data['offset']);
			$this->db->order_by('timestamp', 'desc');
			$rows = $this->db->get()->result_array();

			return $rows;
		}
		else
		{
			$count = $this->db->count_all_results();

			return $count;
		}
	}
}