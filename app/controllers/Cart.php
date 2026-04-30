<?php
class Cart extends Controller {
    public function __construct() {
        $this->orderModel = $this->model('Order');
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

            // Generate order number
            $this->db = new Database();
            $this->db->query("SELECT COUNT(*) as count FROM orders");
            $count = $this->db->single()->count;
            $order_no = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

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
