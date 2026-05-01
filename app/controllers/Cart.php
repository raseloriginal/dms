<?php
class Cart extends Controller {
    private $orderModel;
    private $userModel;
    private $db;

    public function __construct() {
        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
    }

    // Handle checkout AJAX
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!$data) {
                echo json_encode(['status' => 'error', 'message' => 'No data received']);
                return;
            }

            $this->db = new Database();
            $this->db->query("SELECT COUNT(*) as count FROM orders");
            $count = $this->db->single()->count;
            $order_no = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

            // Auto create account if doesn't exist
            $existingUser = $this->userModel->findUserByPhone($data['phone']);
            if (!$existingUser) {
                $userData = [
                    'phone' => $data['phone'],
                    'password' => $data['phone'],
                    'address' => $data['address']
                ];
                $userId = $this->userModel->createUser($userData);
                if ($userId && !isset($_SESSION['user_id'])) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_phone'] = $data['phone'];
                }
            } else {
                if (!isset($_SESSION['user_id'])) {
                    $_SESSION['user_id'] = $existingUser->id;
                    $_SESSION['user_phone'] = $existingUser->phone;
                }
            }

            $orderData = [
                'order_no' => $order_no,
                'phone' => $data['phone'],
                'address' => $data['address'],
                'subtotal' => $data['subtotal'],
                'total' => $data['total'],
                'items' => $data['items']
            ];

            $orderId = $this->orderModel->addOrder($orderData);

            if ($orderId) {
                echo json_encode(['status' => 'success', 'order_no' => $order_no]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Could not save order']);
            }
        }
    }

    // Handle status update AJAX
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $status = $_POST['status'];

            if ($this->orderModel->updateStatus($id, $status)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }
}
