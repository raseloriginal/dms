<?php
class Users extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $phone = trim($_POST['phone']);
            $password = trim($_POST['password']);

            $loggedInUser = $this->userModel->login($phone, $password);

            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser->id;
                $_SESSION['user_phone'] = $loggedInUser->phone;
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ফোন নম্বর বা পাসওয়ার্ড ভুল']);
            }
        }
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/home');
            exit;
        }

        $user = $this->userModel->findUserByPhone($_SESSION['user_phone']);

        $data = [
            'title' => 'প্রোফাইল',
            'user' => $user
        ];

        $this->view('users/profile', $data);
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_phone']);
        header('location: ' . URLROOT . '/home');
    }
}
