<?php
class Delivery extends Controller {
    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    public function index() {
        if (!isset($_SESSION['staff_id'])) {
            header('location: ' . URLROOT . '/staff/login');
            exit;
        }

        $orders = $this->orderModel->getOrdersByStatus(['confirmed', 'ready']);
        
        foreach($orders as $order) {
            $order->items = $this->orderModel->getOrderItems($order->id);
        }

        $data = [
            'title' => 'FreshMart — Premium Logistics',
            'orders' => $orders
        ];
        $this->view('delivery/index', $data);
    }
}
