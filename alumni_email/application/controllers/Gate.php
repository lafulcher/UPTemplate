<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('admin_model');

		if ($this->session->userdata('is_logged_in'))
		{
			if ($this->router->method != 'logout')
			{
				redirect('dashboard');
			}
		}
	}

	public function _check_captcha($captcha)
	{
		$this->load->model('captcha_model');
		$this->form_validation->set_message('_check_captcha', 'Incorrect CAPTCHA. Please try again.');
		return $this->captcha_model->check_captcha($captcha);
	}

	public function _check_date($var)
	{
		if (
			trim($this->input->post('birth_month'))
			&& trim($this->input->post('birth_day'))
			&& trim($this->input->post('birth_year'))
		)
		{
			$date = str_pad($this->input->post('birth_month'),2,'0',STR_PAD_LEFT)
				. '/' . str_pad($this->input->post('birth_day'),2,'0',STR_PAD_LEFT)
				. '/' . str_pad($this->input->post('birth_year'),4,'0',STR_PAD_LEFT);

			$this->form_validation->set_message('_check_date', '{field} is invalid.');
			return custom_validate_date($date);
		}

		return TRUE;
	}

	public function _prep_errors()
	{
		$errors = array();

		$field_groups = array(
			'A' => array(
				'title_simple',
				'title_preferred',
				'first_name',
				'middle_name',
				'last_name',
				'maiden_name',
				'birthdate',
				'birth_month',
				'birth_day',
				'birth_year',
				'birth_place',
				'nickname',
			),
			'B' => array(
				'student_no',
				'campus_id',
				'college',
				'course',
				'year_started',
				'year_graduated',
				'honor',
				'other_degrees'
			),
			'C' => array(
				'email',
				'email_conf',
				'address_1',
				'address_2',
				'address_3',
				'address_4',
				'address_zip',
				'tel_no',
				'mobile_no',
				'website',
			),
			'D' => array(
				'company_name',
				'occupation',
				'work_address_1',
				'work_address_2',
				'work_address_3',
				'work_address_4',
				'work_address_zip',
				'work_tel_no',
				'work_mobile_no',
				'fax_no',
				'work_website'
			),
			'E' => array(
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][1][organization]',
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][1][period]',
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][1][position]',
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][2][organization]',
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][2][period]',
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][2][position]',
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][3][organization]',
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][3][period]',
				'membership['.MEMBERSHIP_CAT_ALUMNI.'][3][position]',
			),
			'F' => array(
				'exam_name',
				'exam_place',
				'exam_date',
				'rating'
			),
			'G' => array(
				'membership['.MEMBERSHIP_CAT_OTHERS.'][1][organization]',
				'membership['.MEMBERSHIP_CAT_OTHERS.'][1][period]',
				'membership['.MEMBERSHIP_CAT_OTHERS.'][1][position]',
				'membership['.MEMBERSHIP_CAT_OTHERS.'][2][organization]',
				'membership['.MEMBERSHIP_CAT_OTHERS.'][2][period]',
				'membership['.MEMBERSHIP_CAT_OTHERS.'][2][position]',
				'membership['.MEMBERSHIP_CAT_OTHERS.'][3][organization]',
				'membership['.MEMBERSHIP_CAT_OTHERS.'][3][period]',
				'membership['.MEMBERSHIP_CAT_OTHERS.'][3][position]',
			),
			'H' => array(
				'award[1][award_name]',
				'award[1][award_date]',
				'award[2][award_name]',
				'award[2][award_date]',
				'award[3][award_name]',
				'award[3][award_date]',
			),
			'I' => array(
				'family[1][name]',
				'family[1][relation]',
				'family[1][campus_id]',
				'family[1][college]',
				'family[1][course]',
				'family[1][year_graduated]',
				'family[1][address]',
				'family[2][name]',
				'family[2][relation]',
				'family[2][campus_id]',
				'family[2][college]',
				'family[2][course]',
				'family[2][year_graduated]',
				'family[2][address]',
				'family[3][name]',
				'family[3][relation]',
				'family[3][campus_id]',
				'family[3][college]',
				'family[3][course]',
				'family[3][year_graduated]',
				'family[3][address]',
			)
		);

		foreach ($field_groups as $i => $fields)
		{
			foreach ($fields as $field)
			{
				if (form_error($field))
				{
					$errors[$i][] = form_error($field,'<li>','</li>');
				}
			}
		}

		return $errors;
	}

	public function admin()
	{
		$body = $this->load->view('gate/admin', '', TRUE);
		$this->load->view('template', array(
			'title' => 'Administrator Log-in',
			'body' => $body
		));
	}

	public function confirm()
	{
		$this->db->where('request_no', $this->input->get('request_no'));
		$this->db->where('confirmation_code', $this->input->get('code'));
		$request = $this->db->get('request')->row_array();

		if (empty($request) OR $request['status'] != REQUEST_STATUS_NEW)
		{
			$this->session->set_flashdata('alert', array(
				'type' => 'danger',
				'message' => '<strong>Invalid confirmation link.</strong> Please try again or contact us at helpdesk@up.edu.ph for assistance.'
			));		
			redirect();
		}
		else
		{
			$this->db->set('status', REQUEST_STATUS_CONFIRMED);
			$this->db->where('request_id', $request['request_id']);
			$this->db->update('request');

			$this->session->set_flashdata('alert', array(
				'type' => 'success',
				'message' => '<strong>Request No. '.$request['request_no'].' has been confirmed!</strong> We\'ll keep you posted on the status of your request. Thank you!'
			));		
			redirect();
		}
	}

	public function index()
	{
		$data['alert'] = FALSE;

		$this->load->model('captcha_model');

		$data['captcha'] = $this->captcha_model->generate_captcha();

		$data['personal_titles'] = array(
			PERSONAL_TITLE_MR => 'Mr.',
			PERSONAL_TITLE_MRS => 'Mrs.',
			PERSONAL_TITLE_MISS => 'Miss',
		);

		$this->db->select('campus_id,campus_name');
		$this->db->order_by('campus_id');
		$campuses = $this->db->get('campus')->result_array();

		$data['campuses'][''] = 'Select a campus';
		foreach ($campuses as $campus)
		{
			$data['campuses'][$campus['campus_id']] = $campus['campus_name'];
		}

		$data['colleges'][''] = 'Select a college, school, or institute';
		if ($this->input->post('campus_id'))
		{
			$this->db->select('college_id,college_name');
			$this->db->order_by('college_name');
			$this->db->where('campus_id', $this->input->post('campus_id'));
			$colleges = $this->db->get('college')->result_array();		

			foreach ($colleges as $college)
			{
				$data['colleges'][$college['college_id']] = $college['college_name'];
			}
		}
		
		$data['courses'][''] = 'Select a course';
		if ($this->input->post('college_id'))
		{
			$this->db->select('course_id,course_name');
			$this->db->order_by('course_name');
			$this->db->where('college_id', $this->input->post('college_id'));
			$courses = $this->db->get('course')->result_array();		

			foreach ($courses as $course)
			{
				$data['courses'][$course['course_id']] = $course['course_name'];
			}
		}

		$data['months'] = array(
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December'
		);

		$data['days'] = array();

		for ($i = 1; $i <= 31; $i++)
		{
			$data['days'][$i] = $i;
		}

		$data['birth_years'] = array();

		for ($i = date('Y'); $i >= (date('Y') - 100); $i--)
		{
			$data['birth_years'][$i] = $i;
		}

		$data['acad_years'] = array();

		for ($i = date('Y'); $i >= 1908; $i--)
		{
			$data['acad_years'][$i] = $i;
		}

		$rules = array(
			// A personal
			array(
				'field' => 'title_simple',
				'label' => 'Title',
				'rules' => 'required|in_list[' . custom_array_keys($data['personal_titles']) . ']'
			),
			array(
				'field' => 'title_preferred',
				'label' => 'Preferred Title',
				'rules' => 'trim|max_length[20]'
			),
			array(
				'field' => 'first_name',
				'label' => 'First Name',
				'rules' => 'trim|required|max_length[50]'
			),
			array(
				'field' => 'middle_name',
				'label' => 'Middle Name',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'last_name',
				'label' => 'Last Name',
				'rules' => 'trim|required|max_length[50]'
			),
			array(
				'field' => 'maiden_name',
				'label' => 'Maiden Name',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'nickname',
				'label' => 'Nickname',
				'rules' => 'trim|required|max_length[20]'
			),
			array(
				'field' => 'birthdate',
				'label' => 'Date of Birth',
				'rules' => 'callback__check_date'
			),
			array(
				'field' => 'birth_month',
				'label' => 'Month of Birth',
				'rules' => 'required|in_list[' . custom_array_keys($data['months']) . ']'
			),
			array(
				'field' => 'birth_day',
				'label' => 'Day of Birth',
				'rules' => 'required|in_list[' . custom_array_keys($data['days']) . ']'
			),
			array(
				'field' => 'birth_year',
				'label' => 'Year of Birth',
				'rules' => 'required|in_list[' . custom_array_keys($data['birth_years']) . ']'
			),
			array(
				'field' => 'birth_place',
				'label' => 'Place of Birth',
				'rules' => 'trim|required|max_length[50]'
			),
			// B academic
			array(
				'field' => 'student_no',
				'label' => 'Student No.',
				'rules' => 'trim|exact_length[9]|integer',
				'errors' => array(
					'exact_length' => '{field} is not in the correct format.',
					'integer' => '{field} is not in the correct format.'
				)
			),
			array(
				'field' => 'campus_id',
				'label' => 'Campus',
				'rules' => 'required|in_list[' . custom_array_keys($data['campuses']) . ']'
			),
			array(
				'field' => 'college',
				'label' => 'College, School, or Institute',
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'course',
				'label' => 'Degree/Certificate & Major',
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'year_started',
				'label' => 'Year Started',
				'rules' => 'trim|required|greater_than_equal_to[1908]|less_than_equal_to[' . date('Y') . ']',
				'errors' => array(
					'greater_than_equal_to' => '{field} is invalid.',
					'less_than_equal_to' => '{field} is invalid.',
				)
			),
			array(
				'field' => 'year_graduated',
				'label' => 'Year Graduated',
				'rules' => 'trim|greater_than_equal_to[' . ($this->input->post('year_started') ? $this->input->post('year_started') : '1908') . ']|less_than_equal_to[' . date('Y') . ']',
				'errors' => array(
					'greater_than_equal_to' => '{field} is invalid.',
					'less_than_equal_to' => '{field} is invalid.',
				)
			),
			array(
				'field' => 'honor',
				'label' => 'Honor',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'other_degrees',
				'label' => 'Other UP Degrees/Certificates',
				'rules' => 'trim|max_length[100]'
			),
			// C contact
			array(
				'field' => 'email',
				'label' => 'Personal Email Address',
				'rules' => 'trim|required|max_length[100]|valid_email'
			),
			array(
				'field' => 'email_conf',
				'label' => '',
				'rules' => 'trim|required|matches[email]',
				'errors' => array(
					'required' => 'Please retype your email address in the provided field.',
					'matches' => 'Your email addresses don\'t match.'
				)
			),
			array(
				'field' => 'address_1',
				'label' => 'Number and Street',
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'address_2',
				'label' => 'Subdivision/Village, Barangay, District',
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'address_3',
				'label' => 'Town/City',
				'rules' => 'trim|required|max_length[50]'
			),
			array(
				'field' => 'address_4',
				'label' => 'Province, Region, Country',
				'rules' => 'trim|required|max_length[50]'
			),
			array(
				'field' => 'address_zip',
				'label' => 'Postal/ZIP Code',
				'rules' => 'trim|max_length[15]'
			),
			array(
				'field' => 'tel_no',
				'label' => 'Telephone No.',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'mobile_no',
				'label' => 'Mobile No.',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'website',
				'label' => 'Personal Website',
				'rules' => 'trim|max_length[100]'
			),
			// D work
			array(
				'field' => 'company_name',
				'label' => 'Company Name',
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'occupation',
				'label' => 'Present Position or Occupation',
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'work_address_1',
				'label' => 'Number and Street',
				'rules' => 'trim|max_length[100]'
			),
			array(
				'field' => 'work_address_2',
				'label' => 'Subdivision/Village, Barangay, District',
				'rules' => 'trim|max_length[100]'
			),
			array(
				'field' => 'work_address_3',
				'label' => 'Town/City',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'work_address_4',
				'label' => 'Province, Region, Country',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'work_address_zip',
				'label' => 'Postal/ZIP Code',
				'rules' => 'trim|max_length[15]'
			),
			array(
				'field' => 'work_tel_no',
				'label' => 'Telephone No.',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'work_mobile_no',
				'label' => 'Mobile No.',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'fax_no',
				'label' => 'Fax No.',
				'rules' => 'trim|max_length[50]'
			),
			array(
				'field' => 'work_website',
				'label' => 'Business Website',
				'rules' => 'trim|max_length[100]'
			)
		);

		// E alumni

		for ($row = 1; $row <= 3; $row++)
		{
			if (
				trim($this->input->post('membership['.MEMBERSHIP_CAT_ALUMNI.'][' . $row . '][organization]'))
				OR trim($this->input->post('membership['.MEMBERSHIP_CAT_ALUMNI.'][' . $row . '][period]'))
				OR trim($this->input->post('membership['.MEMBERSHIP_CAT_ALUMNI.'][' . $row . '][position]'))
			)
			{
				$_rules = array(
					array(
						'field' => 'membership['.MEMBERSHIP_CAT_ALUMNI.'][' . $row . '][organization]',
						'label' => '#' . $row . ' Name of Chapter',
						'rules' => 'trim|required|max_length[100]'
					),
					array(
						'field' => 'membership['.MEMBERSHIP_CAT_ALUMNI.'][' . $row . '][period]',
						'label' => '#' . $row . ' Period of Membership',
						'rules' => 'trim|required|max_length[50]'
					),
					array(
						'field' => 'membership['.MEMBERSHIP_CAT_ALUMNI.'][' . $row . '][position]',
						'label' => '#' . $row . ' Highest Position',
						'rules' => 'trim|required|max_length[100]'
					)
				);
				
				$rules = array_merge($rules, $_rules);
			}
		}

		// F exam

		if (
			trim($this->input->post('exam_name'))
			OR trim($this->input->post('exam_place'))
			OR trim($this->input->post('exam_date'))
			OR trim($this->input->post('rating'))
		)
		{
			$_rules = array(
				array(
					'field' => 'exam_name',
					'label' => 'Name of Examination',
					'rules' => 'trim|required|max_length[100]'
				),
				array(
					'field' => 'exam_place',
					'label' => 'Place of Examination',
					'rules' => 'trim|required|max_length[50]'
				),
				array(
					'field' => 'exam_date',
					'label' => 'Date',
					'rules' => 'trim|required|max_length[50]'
				),
				array(
					'field' => 'rating',
					'label' => 'Rating',
					'rules' => 'trim|required|max_length[50]'
				),
			);
			
			$rules = array_merge($rules, $_rules);
		}

		// G other orgs

		for ($row = 1; $row <= 3; $row++)
		{
			if (
				trim($this->input->post('membership['.MEMBERSHIP_CAT_OTHERS.'][' . $row . '][organization]'))
				OR trim($this->input->post('membership['.MEMBERSHIP_CAT_OTHERS.'][' . $row . '][period]'))
				OR trim($this->input->post('membership['.MEMBERSHIP_CAT_OTHERS.'][' . $row . '][position]'))
			)
			{
				$_rules = array(
					array(
						'field' => 'membership['.MEMBERSHIP_CAT_OTHERS.'][' . $row . '][organization]',
						'label' => '#' . $row . ' Name of Organization',
						'rules' => 'trim|required|max_length[100]'
					),
					array(
						'field' => 'membership['.MEMBERSHIP_CAT_OTHERS.'][' . $row . '][period]',
						'label' => '#' . $row . ' Period of Membership',
						'rules' => 'trim|required|max_length[50]'
					),
					array(
						'field' => 'membership['.MEMBERSHIP_CAT_OTHERS.'][' . $row . '][position]',
						'label' => '#' . $row . ' Highest Position',
						'rules' => 'trim|required|max_length[100]'
					)
				);
				
				$rules = array_merge($rules, $_rules);
			}
		}

		// H awards

		for ($row = 1; $row <= 3; $row++)
		{
			if (
				trim($this->input->post('award[' . $row . '][award_name]'))
				OR trim($this->input->post('award[' . $row . '][award_date]'))
			)
			{
				$_rules = array(
					array(
						'field' => 'award[' . $row . '][award_name]',
						'label' => '#' . $row . ' Name of Award/Prize',
						'rules' => 'trim|required|max_length[100]'
					),
					array(
						'field' => 'award[' . $row . '][award_date]',
						'label' => '#' . $row . ' Date Given',
						'rules' => 'trim|required|max_length[50]'
					),
				);
				
				$rules = array_merge($rules, $_rules);
			}
		}

		// I family

		for ($row = 1; $row <= 3; $row++)
		{
			if (
				trim($this->input->post('family[' . $row . '][name]'))
				OR trim($this->input->post('family[' . $row . '][relation]'))
				OR trim($this->input->post('family[' . $row . '][campus_id]'))
				OR trim($this->input->post('family[' . $row . '][college]'))
				OR trim($this->input->post('family[' . $row . '][course]'))
				OR trim($this->input->post('family[' . $row . '][year_graduated]'))
				OR trim($this->input->post('family[' . $row . '][address]'))
			)
			{
				$_rules = array(
					array(
						'field' => 'family[' . $row . '][name]',
						'label' => '#' . $row . ' Name',
						'rules' => 'trim|required|max_length[100]'
					),
					array(
						'field' => 'family[' . $row . '][relation]',
						'label' => '#' . $row . ' Relation',
						'rules' => 'trim|required|max_length[50]'
					),
					array(
						'field' => 'family[' . $row . '][campus_id]',
						'label' => '#' . $row . ' Campus',
						'rules' => 'required|in_list[' . custom_array_keys($data['campuses']) . ']'
					),
					array(
						'field' => 'family[' . $row . '][college]',
						'label' => '#' . $row . ' College, School, or Institute',
						'rules' => 'trim|required|max_length[100]'
					),
					array(
						'field' => 'family[' . $row . '][course]',
						'label' => '#' . $row . ' Degree/Certificate & Major',
						'rules' => 'trim|required|max_length[100]'
					),
					array(
						'field' => 'family[' . $row . '][address]',
						'label' => '#' . $row . ' Address',
						'rules' => 'trim|required|max_length[100]'
					),
					array(
						'field' => 'family[' . $row . '][year_graduated]',
						'label' => '#' . $row . ' Year Graduated',
						'rules' => 'trim|required|greater_than_equal_to[1908]|less_than_equal_to[' . date('Y') . ']',
						'errors' => array(
							'greater_than_equal_to' => '{field} is invalid.',
							'less_than_equal_to' => '{field} is invalid.',
						)
					)
				);
				
				$rules = array_merge($rules, $_rules);
			}
		}

		$rules[] = array(
			'field' => 'captcha',
			'label' => 'CAPTCHA',
			'rules' => 'trim|required|callback__check_captcha'
		);

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run())
		{
			$trans_status = $this->request_model->submit_request();

			if ($trans_status == 'trans_ok')
			{
				$this->session->set_flashdata('alert', array(
					'type' => 'success',
					'message' => '<strong>Your request has been submitted successfully!</strong> Please check your personal email address for instructions on how to confirm your request.'
				));

				redirect();
			}
			else
			{
				$data['alert'] = array(
					'type' => 'warning',
					'message' => '<strong>Something went wrong.</strong> Please try resubmitting the form.'
				);
			}
		}

		$data['errors'] = $this->_prep_errors();
		
		$body = $this->load->view('gate/index', $data, TRUE);
		$this->load->view('template', array('body' => $body));
	}

	public function login()
	{
		$result = $this->admin_model->login();

		if ($result['status'] != 'success')
		{
			$this->session->set_flashdata('alert', array(
				'type' => 'danger',
				'message' => $result['message']
			));

			redirect('admin');
		}
		
		redirect('dashboard');
	}

	public function logout()
	{
		$this->admin_model->logout();
		redirect('admin');
	}
}
