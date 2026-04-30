<?php
class Orders extends Controller {
    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    public function index() {
        $orders = $this->orderModel->getOrders();
        
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
