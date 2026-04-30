<?php
class Staff extends Controller {
    public function __construct() {
        $this->staffModel = $this->model('StaffModel');
    }

    private function isLoggedIn() {
        return isset($_SESSION['staff_id']);
    }

    public function index() {
        if ($this->isLoggedIn()) {
            header('location: ' . URLROOT . '/collect');
        } else {
            header('location: ' . URLROOT . '/staff/login');
        }
        exit;
    }

    public function login() {
        if ($this->isLoggedIn()) {
            header('location: ' . URLROOT . '/collect');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $loggedInStaff = $this->staffModel->login($username, $password);

            if ($loggedInStaff) {
                $_SESSION['staff_id'] = $loggedInStaff->id;
                $_SESSION['staff_username'] = $loggedInStaff->username;
                header('location: ' . URLROOT . '/collect');
            } else {
                $data = [
                    'title' => 'স্টাফ লগইন',
                    'error' => 'ভুল ইউজারনেম বা পাসওয়ার্ড'
                ];
                $this->view('staff/login', $data);
            }
        } else {
            $data = [
                'title' => 'স্টাফ লগইন'
            ];
            $this->view('staff/login', $data);
        }
    }

    public function logout() {
        unset($_SESSION['staff_id']);
        unset($_SESSION['staff_username']);
        header('location: ' . URLROOT . '/staff/login');
    }
}
