<?php
class Collect extends Controller {
    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    public function index() {
        $orders = $this->orderModel->getOrdersByStatus('confirmed');
        
        $grouped = [];
        foreach($orders as $order) {
            $items = $this->orderModel->getOrderItems($order->id);
            foreach($items as $item) {
                if(!isset($grouped[$item->product_id])) {
                    $grouped[$item->product_id] = (object)[
                        'id' => $item->product_id,
                        'name' => $item->name,
                        'icon' => $item->icon,
                        'image' => $item->image,
                        'totalQty' => 0,
                        'orderCount' => 0
                    ];
                }
                $grouped[$item->product_id]->totalQty += $item->qty;
                $grouped[$item->product_id]->orderCount++;
            }
        }

        // Add items to each order for status tracking
        foreach($orders as $order) {
            $order->items = $this->orderModel->getOrderItems($order->id);
        }

        $data = [
            'title' => 'FreshMart — Collect Products',
            'items' => array_values($grouped),
            'orders' => $orders
        ];
        $this->view('collect/index', $data);
    }

    public function complete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orders = $this->orderModel->getOrdersByStatus('confirmed');
            
            foreach($orders as $order) {
                $this->orderModel->updateStatus($order->id, 'ready');
            }

            echo json_encode(['status' => 'success']);
        }
    }
}
