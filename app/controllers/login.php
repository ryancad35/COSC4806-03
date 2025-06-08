<?php

class Login extends Controller {

    public function index() {		
	    $this->view('login/index');
    }
    
	public function verify()
		{
			// 1) Make sure session is started
			if (session_status() !== PHP_SESSION_ACTIVE) {
				session_start();
			}
	
			$username = trim($_POST['username']  ?? '');
			$password = trim($_POST['password']  ?? '');
	
			$userObj = $this->model('User');
	
			// 4) Treat empty fields as failed attempt
			if ($userObj->notEmptyAccount($username, $password) === false) {
				if (isset($_SESSION['failedAttempts'])) {
					$_SESSION['failedAttempts']++;
				} else {
					$_SESSION['failedAttempts'] = 1;
				}
				header('Location: /login');
				exit;
			}
	
			// 5) Verify username/password
			$user = $userObj->processLogin($username, $password);
	
			if ($user !== null) {
				// Login successful
				$_SESSION['authenticated'] = true;
				$_SESSION['username']      = $user['username'];
	
				if (isset($_SESSION['loginSuccess'])) {
					$_SESSION['loginSuccess']++;
				} else {
					$_SESSION['loginSuccess'] = 1;
				}
	
				header('Location: /home');
				exit;
			} else {
				// Login failed
				if (isset($_SESSION['failedAttempts'])) {
					$_SESSION['failedAttempts']++;
				} else {
					$_SESSION['failedAttempts'] = 1;
				}
	
				header('Location: /login');
				exit;
			}
		}
	}