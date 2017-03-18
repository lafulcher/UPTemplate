<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller {

	public function get_colleges()
	{
		$colleges = array();

		$this->db->select('college_name');
		$this->db->like('college_name', $this->input->get('q'));
		$this->db->where('campus_id', $this->input->get('campus_id'));
		$this->db->order_by('college_name');
		$this->db->limit(10);
		$rows = $this->db->get('college')->result_array();
		
		foreach ($rows as $row)
		{
			$colleges[] = trim($row['college_name']);
		}

		echo json_encode($colleges);
	}

	public function get_courses()
	{
		$courses = array();

		$this->db->select('course_name');
		$this->db->like('course_name', $this->input->get('q'));
		$this->db->where('campus_id', $this->input->get('campus_id'));
		if ($this->input->get('college'))
		{
			$this->db->where('college_name', $this->input->get('college'));
		}
		$this->db->order_by('course_name');
		$this->db->limit(10);
		$this->db->join('college', 'college.college_id = course.college_id');
		$rows = $this->db->get('course')->result_array();
		
		foreach ($rows as $row)
		{
			$courses[] = trim($row['course_name']);
		}

		echo json_encode($courses);
	}
}