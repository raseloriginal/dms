<?php
class AdminModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Login admin
    public function login($username, $password) {
        $this->db->query('SELECT * FROM admins WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Get Dashboard Stats
    public function getStats() {
        $stats = [];

        // Total Orders
        $this->db->query('SELECT COUNT(*) as total FROM orders');
        $stats['total_orders'] = $this->db->single()->total;

        // Pending Orders
        $this->db->query('SELECT COUNT(*) as total FROM orders WHERE status = "pending"');
        $stats['pending_orders'] = $this->db->single()->total;

        // Total Sales
        $this->db->query('SELECT SUM(total) as total FROM orders WHERE status = "delivered"');
        $stats['total_sales'] = $this->db->single()->total ?? 0;

        // Total Products
        $this->db->query('SELECT COUNT(*) as total FROM products');
        $stats['total_products'] = $this->db->single()->total;

        // Total Customers (Unique phone numbers)
        $this->db->query('SELECT COUNT(DISTINCT phone) as total FROM orders');
        $stats['total_customers'] = $this->db->single()->total;

        return $stats;
    }

    // Get unique customers
    public function getCustomers() {
        $this->db->query('SELECT phone, address, COUNT(*) as order_count, SUM(total) as total_spent, MAX(created_at) as last_order 
                         FROM orders 
                         GROUP BY phone 
                         ORDER BY last_order DESC');
        return $this->db->resultSet();
    }
}
