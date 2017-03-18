<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if ( ! $this->session->userdata('is_logged_in'))
		{
			$this->session->set_flashdata('alert', array(
				'type' => 'danger',
				'message' => '<strong>Access denied.</strong> You must be logged in to use the system.'
			));
			redirect('admin');
		}

		$_permissions = array(
			ADMIN_ROLE_SUPER => array(
				'dashboard' => array(
					'index'
				),
				'logs' => array(
					'index'
				)
			),
			ADMIN_ROLE_OAR => array(
				'dashboard' => array(
					'index'
				),
				'logs' => array(
					'index'
				)
			),
			ADMIN_ROLE_ITDC => array(
				'dashboard' => array(
					'index'
				)
			)
		);

		$permissions = $_permissions[$this->session->userdata('role')];

		if ( ! array_key_exists($this->router->class, $permissions))
		{
			$this->session->set_flashdata('alert', array(
				'type' => 'danger',
				'message' => '<strong>Access denied.</strong> You are not allowed to access that page or perform that operation.'
			));

			redirect('dashboard');
		}

		if ( ! in_array($this->router->method, $permissions[$this->router->class]))
		{
			$this->session->set_flashdata('alert', array(
				'type' => 'danger',
				'message' => '<strong>Access denied.</strong> You are not allowed to access that page or perform that operation.'
			));

			redirect('dashboard');
		}
	}

	public function _download($data)
	{
		$writer = Box\Spout\Writer\WriterFactory::create(Box\Spout\Common\Type::XLSX);

		$writer->openToBrowser($data['filename'] . '.xlsx');

		$border = (new Box\Spout\Writer\Style\BorderBuilder())
			->setBorderTop(Box\Spout\Writer\Style\Color::BLACK, Box\Spout\Writer\Style\Border::WIDTH_THIN)
			->setBorderRight(Box\Spout\Writer\Style\Color::BLACK, Box\Spout\Writer\Style\Border::WIDTH_THIN)
			->setBorderBottom(Box\Spout\Writer\Style\Color::BLACK, Box\Spout\Writer\Style\Border::WIDTH_THIN)
			->setBorderLeft(Box\Spout\Writer\Style\Color::BLACK, Box\Spout\Writer\Style\Border::WIDTH_THIN)
			->build();

		$style = (new Box\Spout\Writer\Style\StyleBuilder())
			->setFontBold()
			->setBorder($border)
			->setBackgroundColor(Box\Spout\Writer\Style\Color::YELLOW)
			->build();

		$writer->addRowWithStyle($data['header'], $style);

		$writer->addRows($data['rows']);

		$writer->close();
	}

	public function _invalid_action()
	{
		$this->session->set_flashdata('alert', array(
			'type' => 'danger',
			'message' => lang('alert_invalid_action')
		));
		redirect('dashboard');
	}
}