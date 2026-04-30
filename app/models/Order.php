<?php
class Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all orders
    public function getOrders() {
        $this->db->query('SELECT * FROM orders ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // Get order by id
    public function getOrderById($id) {
        $this->db->query('SELECT * FROM orders WHERE id = :id');
        $this->db->bind(':id', $id);
        $order = $this->db->single();
        
        if($order) {
            $order->items = $this->getOrderItems($order->id);
        }
        
        return $order;
    }

    // Get items for an order
    public function getOrderItems($orderId) {
        $this->db->query('SELECT * FROM order_items WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    // Place new order
    public function addOrder($data) {
        $this->db->query('INSERT INTO orders (order_no, phone, address, subtotal, total, status) VALUES (:order_no, :phone, :address, :subtotal, :total, :status)');
        $this->db->bind(':order_no', $data['order_no']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':subtotal', $data['subtotal']);
        $this->db->bind(':total', $data['total']);
        $this->db->bind(':status', 'pending');

        if ($this->db->execute()) {
            $orderId = $this->db->lastInsertId();
            foreach ($data['items'] as $item) {
                $this->addOrderItem($orderId, $item);
            }
            return $orderId;
        }
        return false;
    }

    // Add item to order
    public function addOrderItem($orderId, $item) {
        $this->db->query('INSERT INTO order_items (order_id, product_id, name, price, qty, icon, image) VALUES (:order_id, :product_id, :name, :price, :qty, :icon, :image)');
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':product_id', $item['id']);
        $this->db->bind(':name', $item['name']);
        $this->db->bind(':price', $item['price']);
        $this->db->bind(':qty', $item['qty']);
        $this->db->bind(':icon', $item['icon']);
        $this->db->bind(':image', $item['image'] ?? null);
        return $this->db->execute();
    }

    // Update status
    public function updateStatus($id, $status) {
        $this->db->query('UPDATE orders SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Get orders by status
    public function getOrdersByStatus($status) {
        if(is_array($status)) {
            $placeholders = implode(',', array_fill(0, count($status), '?'));
            $this->db->query("SELECT * FROM orders WHERE status IN ($placeholders) ORDER BY created_at DESC");
            foreach($status as $i => $s) {
                $this->db->bind($i + 1, $s);
            }
        } else {
            $this->db->query('SELECT * FROM orders WHERE status = :status ORDER BY created_at DESC');
            $this->db->bind(':status', $status);
        }
        return $this->db->resultSet();
    }
}
