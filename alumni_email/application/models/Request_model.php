<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_model extends CI_Model {

	public function submit_request()
	{
		$this->db->trans_start();

		$this->load->helper('string');

		$blacklist = array('666','999');

		$request_no = '';

		$not_available = TRUE;
		while ($not_available)
		{
			$request_no = date('Y') . random_string('numeric', 6);

			foreach ($blacklist as $b)
			{
				if (strpos($request_no, $b) !== FALSE)
				{
					continue;
				}
			}

			$this->db->where('request_no', $request_no);
			if ($this->db->count_all_results('request') == 0)
			{
				$not_available = FALSE;
			}
		}

		$code = random_string('alnum',40);

		$this->db->insert('request', array(
			'request_no' => $request_no,
			'email' => $this->input->post('email'),
			'date_requested' => date('Y-m-d H:i:s'),
			'status' => REQUEST_STATUS_NEW,
			'waiver_1' => $this->input->post('waiver_1') ? YES : NO,
			'waiver_2' => $this->input->post('waiver_2') ? YES : NO,
			'waiver_3' => $this->input->post('waiver_3') ? YES : NO,
			'confirmation_code' => $code
		));

		$request_id = $this->db->insert_id();

		$fields = $this->db->list_fields('personal');
		$values = array('request_id' => $request_id);
		foreach ($fields as $field)
		{
			if ($field == 'request_id')
			{
				continue;
			}
			else if ($field == 'title_simple')
			{
				$values[$field] = $data['personal_titles'][$this->input->post($field)];
			}
			else if ($field == 'sex')
			{
				$values[$field] = $this->input->post('title_simple') == PERSONAL_TITLE_MR ? 'M' : 'F';
			}
			else if ($field == 'birthdate')
			{
				$date = str_pad($this->input->post('birth_month'),2,'0',STR_PAD_LEFT)
					. '/' . str_pad($this->input->post('birth_day'),2,'0',STR_PAD_LEFT)
					. '/' . str_pad($this->input->post('birth_year'),4,'0',STR_PAD_LEFT);

				$values[$field] = date('Y-m-d', strtotime($date));
			}
			else
			{
				$values[$field] = $this->input->post($field);
			}
		}
		$this->db->insert('personal', $values);

		$tables = array('academic','contact','work','exam');
		foreach ($tables as $table)
		{
			$fields = $this->db->list_fields($table);
			$values = array('request_id' => $request_id);
			foreach ($fields as $field)
			{
				if ($field == 'request_id')
				{
					continue;
				}
				else
				{
					$values[$field] = $this->input->post($field);
				}
			}
			$this->db->insert($table, $values);
		}

		$fields = $this->db->list_fields('membership');
		$categories = array(
			MEMBERSHIP_CAT_ALUMNI,
			MEMBERSHIP_CAT_OTHERS
		);
		foreach ($categories as $cat)
		{
			for ($row = 1; $row <= 3; $row++)
			{
				if (implode($this->input->post('membership['. $cat . '][' . $row . ']')) == '')
				{
					continue;
				}

				$values = array(
					'request_id' => $request_id,
					'category' => $cat
				);
				foreach ($fields as $field)
				{
					if (
						$field == 'membership_id'
						OR $field == 'request_id'
						OR $field == 'category'
					)
					{
						continue;
					}
					else
					{
						$values[$field] = $this->input->post('membership['. $cat . '][' . $row . '][' . $field . ']');
					}
				}
				$this->db->insert('membership', $values);
			}
		}

		$tables = array('award','family');
		foreach ($tables as $table)
		{
			$fields = $this->db->list_fields($table);
			for ($row = 1; $row <= 3; $row++)
			{
				if (implode($this->input->post($table . '[' . $row . ']')) == '')
				{
					continue;
				}

				$values = array('request_id' => $request_id);
				foreach ($fields as $field)
				{
					if (
						$field == $table . '_id'
						OR $field == 'request_id'
					)
					{
						continue;
					}
					else
					{
						$values[$field] = $this->input->post($table . '[' . $row . '][' . $field . ']');
					}
				}
				$this->db->insert($table, $values);
			}
		}

		$confirmation_link = site_url('confirm?request_no='.$request_no.'&code='.$code);

		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			// send email
			$this->load->library('email');

			$email_subject = '[UP Alumni Email] Please confirm Request No. ' . $request_no;
			$email_body = '<!DOCTYPE html>
			<html lang="en">
			<head>
			<title>' . $email_subject . '</title>
			<meta charset="utf-8" />
			</head>
			<body>
			<strong style="color: #7B1113">Hello, '.html_escape($this->input->post('first_name') . ' ' . $this->input->post('last_name')).'!</strong>
			<br/><br/>
			Thank you for your interest in getting your very own @alum.up.edu.ph account!
			<br/><br/>
			To confirm your request, click on the link below (or copy and paste the URL into your browser):
			<br/><br/>
			<a href="'.$confirmation_link.'" target="_blank">'.$confirmation_link.'</a>
			<br/><br/>
			Contact us at helpdesk@up.edu.ph if you did not make this request or if you need any other related assistance.
			<br/><br/>
			<strong style="color: #7B1113">UP Alumni Email Team</strong><br/>
			Office of Alumni Relations<br/>
			University of the Philippines
			<br/><br/>
			<small>Do not reply to this email because we are not monitoring this inbox. Contact us at helpdesk@up.edu.ph instead.</small>
			</body>
			</html>';

			$this->email->clear(TRUE);

			$this->email->from('adcp-mailer@up.edu.ph', 'UP Alumni Email Team');
			$this->email->reply_to('jurespino@up.edu.ph');
			$this->email->to($this->input->post('email'));
			$this->email->subject($email_subject);
			$this->email->message($email_body);
			$this->email->send();

			return 'trans_ok';
		}
		else
		{
			return 'trans_failed';
		}
	}
}