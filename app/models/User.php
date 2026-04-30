<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findUserByPhone($phone) {
        $this->db->query('SELECT * FROM users WHERE phone = :phone');
        $this->db->bind(':phone', $phone);
        return $this->db->single();
    }

    public function createUser($data) {
        $this->db->query('INSERT INTO users (phone, password, address) VALUES (:phone, :password, :address)');
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':address', $data['address']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function login($phone, $password) {
        $this->db->query('SELECT * FROM users WHERE phone = :phone');
        $this->db->bind(':phone', $phone);
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
}
