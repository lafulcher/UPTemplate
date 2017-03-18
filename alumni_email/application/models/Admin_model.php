<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function _login()
	{
		$first_name = 'JR';
		$last_name = 'Respino';
		$username = 'jurespino@up.edu.ph';

		$this->db->select('
            admin_id,
            username,
            role,
            first_login,
            last_login
        ');
		$this->db->from('admin');
		$this->db->where('username', $username);
		$user = $this->db->get()->row_array();

		$user['first_name'] = $first_name;
		$user['last_name'] = $last_name;
		$user['is_logged_in'] = TRUE;

		$current_datetime = date('Y-m-d H:i:s');
		if ($user['first_login'] == '0000-00-00 00:00:00')
		{
			$user['first_login'] = $current_datetime;
			$user['last_login'] = $current_datetime;

			$this->db->set('first_login', $current_datetime);
			$this->db->set('last_login', $current_datetime);
			$this->db->where('admin_id', $user['admin_id']);
			$this->db->update('admin');
		}
		else
		{
			$user['last_login'] = $current_datetime;

			$this->db->set('last_login', $current_datetime);
			$this->db->where('admin_id', $user['admin_id']);
			$this->db->update('admin');
		}

		$this->session->set_userdata($user);

		$this->log_model->log('success');

		return array('status' => TRUE);
	}

	public function login()
	{
		$provider = new League\OAuth2\Client\Provider\Google(array(
			'clientId'     => '763687919485-qtf1foti2a1mkjjodilh49o8qbr3jcko.apps.googleusercontent.com',
			'clientSecret' => 'HY77HT7P_9Td7fLyfvMSyb-l',
			'redirectUri'  => site_url('login'),
			'hostedDomain' => site_url(),
		));

		if ( ! empty($_GET['error']))
		{
			$this->log_model->log(
				array(
					'error' => 'Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'),
					'description' => 'Got an error, probably user denied access'
				),
				array(
					'username' => 'unknown',
					'action' => 'login_failed'
				)
			);

			return array(
				'status' => FALSE,
				'message' => '<strong>Log-in failed.</strong> Please contact us at <a class="alert-link" href="mailto:helpdesk@up.edu.ph">helpdesk@up.edu.ph</a> if the problem persists.'
			);
		}
		elseif (empty($_GET['code']))
		{
			// If we don't have an authorization code then get one
			$authUrl = $provider->getAuthorizationUrl();
			$_SESSION['oauth2state'] = $provider->getState();
			header('Location: ' . $authUrl);
			exit;
		}
		elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state']))
		{
			unset($_SESSION['oauth2state']);

			$this->log_model->log(
				array(
					'error' => 'Invalid state',
					'description' => 'State is invalid, possible CSRF attack in progress'
				),
				array(
					'username' => 'unknown',
					'action' => 'login_failed'
				)
			);

			return array(
				'status' => FALSE,
				'message' => '<strong>Log-in failed.</strong> Please contact us at <a class="alert-link" href="mailto:helpdesk@up.edu.ph">helpdesk@up.edu.ph</a> if the problem persists.'
			);
		}
		else
		{
			// Try to get an access token (using the authorization code grant)
			$token = $provider->getAccessToken('authorization_code', array(
				'code' => $_GET['code']
			));

			// Optional: Now you have a token you can look up a users profile data
			try
			{
				// We got an access token, let's now get the owner details
				$ownerDetails = $provider->getResourceOwner($token);

				$username = $ownerDetails->getEmail();
				$first_name = $ownerDetails->getFirstName();
				$last_name = $ownerDetails->getLastName();

				// make sure they're using @up.edu.ph
				if ( ! strpos($username, '@up.edu.ph'))
				{
					$this->log_model->log(
						array(
							'first_name' => $first_name,
							'last_name' => $last_name,
						),
						array(
							'username' => $username,
							'action' => 'wrong_domain'
						)
					);

					return array(
						'status' => FALSE,
						'message' => '<strong>Log-in failed.</strong> Please use your <strong>@up.edu.ph</strong> account to log in. If you\'re logged in to other Google-based accounts, open <a href="https://accounts.google.com/AccountChooser" class="alert-link">Account Chooser</a> and click on <strong>Add account</strong> to add your <strong>@up.edu.ph</strong> account to the list.'
					);
				}

				$this->db->select('
		            admin_id,
		            username,
		            role,
		            first_login,
		            last_login
		        ');
				$this->db->from('admin');
				$this->db->where('username', $username);
				$admin = $this->db->get()->row_array();

				if ($admin)
				{
					$admin['first_name'] = $first_name;
					$admin['last_name'] = $last_name;
					$admin['is_logged_in'] = TRUE;

					$current_datetime = date('Y-m-d H:i:s');
					if ($admin['first_login'] == '0000-00-00 00:00:00')
					{
						$admin['first_login'] = $current_datetime;
						$admin['last_login'] = $current_datetime;

						$this->db->set('first_login', $current_datetime);
						$this->db->set('last_login', $current_datetime);
						$this->db->where('admin_id', $admin['admin_id']);
						$this->db->update('admin');
					}
					else
					{
						$admin['last_login'] = $current_datetime;

						$this->db->set('last_login', $current_datetime);
						$this->db->where('admin_id', $admin['admin_id']);
						$this->db->update('admin');
					}

					$this->session->set_userdata($admin);

					$this->log_model->log('success');

					return array('status' => TRUE);
				}
				else
				{
					// Valid account but not registered
					$this->log_model->log(
						array(
							'first_name' => $first_name,
							'last_name' => $last_name,
						),
						array(
							'username' => $username,
							'action' => 'not_registered'
						)
					);

					return array(
						'status' => FALSE,
						'message' => '<strong>Your @up.edu.ph account is not registered as an administrator.</strong> All failed attempts are logged for reference.'
					);
				}
			}
			catch (Exception $e)
			{
				$this->log_model->log(
					array(
						'error' => 'Something went wrong:' . $e->getMessage(),
						'description' => 'Failed to get user details'
					),
					array(
						'username' => 'unknown',
						'action' => 'login_failed'
					)
				);

				return array(
					'status' => FALSE,
					'message' => '<strong>Log-in failed.</strong> Please contact us at <a class="alert-link" href="mailto:helpdesk@up.edu.ph">helpdesk@up.edu.ph</a> if the problem persists.'
				);
			}
		}
	}

	public function logout()
	{
		$this->log_model->log('success');
		$this->session->sess_destroy();
	}
}