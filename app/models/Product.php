<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all products
    public function getProducts() {
        $this->db->query('SELECT * FROM products ORDER BY category, name');
        return $this->db->resultSet();
    }

    // Get product by id
    public function getProductById($id) {
        $this->db->query('SELECT * FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
