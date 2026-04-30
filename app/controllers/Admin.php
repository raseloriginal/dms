<?php
class Admin extends Controller {
    public function __construct() {
        $this->adminModel = $this->model('AdminModel');
        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
    }

    private function isLoggedIn() {
        return isset($_SESSION['admin_id']);
    }

    public function index() {
        if (!$this->isLoggedIn()) {
            header('location: ' . URLROOT . '/admin/login');
            exit;
        }

        $stats = $this->adminModel->getStats();
        $recent_orders = $this->orderModel->getOrders(); // Limit this later if needed

        $data = [
            'title' => 'Dashboard | Admin',
            'stats' => $stats,
            'recent_orders' => array_slice($recent_orders, 0, 5)
        ];

        $this->view('admin/index', $data);
    }

    public function login() {
        if ($this->isLoggedIn()) {
            header('location: ' . URLROOT . '/admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $loggedInAdmin = $this->adminModel->login($username, $password);

            if ($loggedInAdmin) {
                // Create Session
                $_SESSION['admin_id'] = $loggedInAdmin->id;
                $_SESSION['admin_username'] = $loggedInAdmin->username;
                header('location: ' . URLROOT . '/admin');
            } else {
                $data = [
                    'title' => 'Admin Login',
                    'error' => 'Invalid username or password'
                ];
                $this->view('admin/login', $data);
            }
        } else {
            $data = [
                'title' => 'Admin Login'
            ];
            $this->view('admin/login', $data);
        }
    }

    public function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        session_destroy();
        header('location: ' . URLROOT . '/admin/login');
    }

    // Product Management
    public function products() {
        if (!$this->isLoggedIn()) {
            header('location: ' . URLROOT . '/admin/login');
            exit;
        }

        $products = $this->productModel->getProducts();
        $data = [
            'title' => 'Product Management',
            'products' => $products
        ];
        $this->view('admin/products', $data);
    }

    public function save_product() {
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image_name = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image_name = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/uploads/' . $image_name);
            }

            $category = trim($_POST['category']);
            if ($category == 'new' && !empty($_POST['new_category'])) {
                $category = trim($_POST['new_category']);
            }

            $data = [
                'id' => $_POST['id'] ?? null,
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'buying_price' => trim($_POST['buying_price']),
                'category' => $category,
                'icon' => trim($_POST['icon'] ?? 'fa-box'),
                'image' => $image_name
            ];

            if ($data['id']) {
                if ($this->productModel->updateProduct($data)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Update failed']);
                }
            } else {
                if ($this->productModel->addProduct($data)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Add failed']);
                }
            }
        }
    }

    public function delete_product($id) {
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($this->productModel->deleteProduct($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
    }

    // Order Management
    public function orders() {
        if (!$this->isLoggedIn()) {
            header('location: ' . URLROOT . '/admin/login');
            exit;
        }

        $orders = $this->orderModel->getOrders();
        $data = [
            'title' => 'Order Management',
            'orders' => $orders
        ];
        $this->view('admin/orders', $data);
    }

    public function update_order_status() {
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];

            if ($this->orderModel->updateStatus($id, $status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error']);
            }
        }
    }

    // Customer Management
    public function customers() {
        if (!$this->isLoggedIn()) {
            header('location: ' . URLROOT . '/admin/login');
            exit;
        }

        $customers = $this->adminModel->getCustomers();
        $data = [
            'title' => 'Customer Management',
            'customers' => $customers
        ];
        $this->view('admin/customers', $data);
    }
}
