<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utils extends CI_Controller {

	public function fix_courses()
	{
		$campuses = $this->db->get('campus')->result_array();

		foreach ($campuses as $campus)
		{
			$this->db->where('campus_id', $campus['campus_id']);
			$colleges = $this->db->get('college')->result_array();

			foreach ($colleges as $college)
			{
				$this->db->select('course_name');
				$this->db->where('college_id', $college['college_id']);
				$this->db->group_by('course_name');
				$this->db->having('count(*) > 1');
				$courses = $this->db->get('course')->result_array();

				foreach ($courses as $course)
				{
					echo $campus['campus_name'];
					echo PHP_EOL;
					echo $college['college_name'];
					echo PHP_EOL;
					echo $course['course_name'];
					echo PHP_EOL;
					echo PHP_EOL;
				}
			}
		}
	}

	public function import_courses()
	{
		$this->benchmark->mark('code_start');
		
		$parser = new Akeneo\Component\SpreadsheetParser\SpreadsheetParser;

		$workbook = $parser::open('db/courses.xlsx');

		for ($sheet = 0; $sheet < 8; $sheet++)
		{
			$campus = array();
			$college = array();

			foreach ($workbook->createRowIterator($sheet) as $index => $values)
			{
				if ($index == 1) continue;

				$columns = array(
					'campus_name',
					'campus_code',
					'college_name',
					'college_code',
					'degree',
					'major',
					'course_name'
				);

				$lengths = array(
					45,
					45,
					100,
					45,
					45,
					45,
					100
				);

				$row = array();

				for ($i = 0; $i < 7; $i++)
				{
					$value = isset($values[$i]) ? trim($values[$i]) : '';

					if (strlen($value) <= $lengths[$i])
					{
						$row[$columns[$i]] = $value;	
					}
					else
					{
						echo 'x[' . $index . ']';
						continue;
					}
				}

				if ($row['campus_name'] && $row['campus_code'])
				{
					$campus = array(
						'campus_name' => $row['campus_name'],
						'campus_code' => $row['campus_code'],
					);

					$this->db->insert('campus', $campus);

					$campus['campus_id'] = $this->db->insert_id();

					echo $campus['campus_name'] . ' ';
				}
				elseif ($row['college_name'] && $row['college_code'])
				{
					$college = array(
						'college_name' => $row['college_name'],
						'college_code' => $row['college_code'],
						'campus_id' => $campus['campus_id']
					);

					$this->db->insert('college', $college);

					$college['college_id'] = $this->db->insert_id();
					echo '.';
				}
				else
				{
					$course = array(
						'degree' => $row['degree'],
						'major' => $row['major'],
						'course_name' => $row['course_name'] ? $row['course_name'] : trim($row['degree'] . ' ' . $row['major']),
						'college_id' => $college['college_id']
					);

					$this->db->insert('course', $course);

					echo '.';
				}
			}

			echo PHP_EOL;
		}

		$this->benchmark->mark('code_end');

		echo 'finished in ' . $this->benchmark->elapsed_time('code_start', 'code_end') . ' seconds';
		echo PHP_EOL;
	}
}