<?php
class Delivery extends Controller {
    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    public function index() {
        $orders = $this->orderModel->getOrdersByStatus(['confirmed', 'ready']);
        
        $data = [
            'title' => 'FreshMart — Premium Logistics',
            'orders' => $orders
        ];
        $this->view('delivery/index', $data);
    }
}
