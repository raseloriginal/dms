<?php
class Orders extends Controller {
    private $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/home');
            exit;
        }

        $orders = $this->orderModel->getOrdersByPhone($_SESSION['user_phone']);
        
        // Add items to each order
        foreach($orders as $order) {
            $order->items = $this->orderModel->getOrderItems($order->id);
        }

        $data = [
            'title' => 'FreshMart — Your Orders',
            'orders' => $orders
        ];
        $this->view('orders/index', $data);
    }
}
