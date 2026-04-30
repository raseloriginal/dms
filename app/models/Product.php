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

    // Add product
    public function addProduct($data) {
        $this->db->query('INSERT INTO products (name, price, buying_price, category, icon, image) VALUES (:name, :price, :buying_price, :category, :icon, :image)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':buying_price', $data['buying_price']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':image', $data['image']);
        return $this->db->execute();
    }

    // Update product
    public function updateProduct($data) {
        if ($data['image']) {
            $this->db->query('UPDATE products SET name = :name, price = :price, buying_price = :buying_price, category = :category, icon = :icon, image = :image WHERE id = :id');
            $this->db->bind(':image', $data['image']);
        } else {
            $this->db->query('UPDATE products SET name = :name, price = :price, buying_price = :buying_price, category = :category, icon = :icon WHERE id = :id');
        }
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':buying_price', $data['buying_price']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':icon', $data['icon']);
        return $this->db->execute();
    }

    // Delete product
    public function deleteProduct($id) {
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
