<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Captcha_model extends CI_Model {

	public function check_captcha($captcha)
	{
		// First, delete old captchas
		$expiration = time() - 7200;
		// Two hour limit
		$this->db->where('captcha_time <', $expiration);
		$this->db->delete('captcha');

		// Then see if a captcha exists:
		$this->db->where(array(
			'word' => $captcha,
			'session_id' => session_id(),
			'captcha_time >' => $expiration
		));

		return $this->db->count_all_results('captcha') > 0;
	}
	
	public function generate_captcha()
	{
		$this->load->helper('captcha');
		$this->load->helper('string');

		$vals = array(
			'word' => random_string('numeric',4),
			'img_path' => './captcha/',
			'img_url' => base_url('captcha'),
			'font_path' => './public/captcha/Roboto-Black.ttf',
			'img_width' => 150,
			'img_height' => 30
		);

		$cap = create_captcha($vals);

		$this->db->insert('captcha', array(
			'captcha_time' => $cap['time'],
			'session_id' => session_id(),
			'word' => $cap['word']
		));

		return $cap['image'];
	}

}