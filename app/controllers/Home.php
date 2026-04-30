<?php
class Home extends Controller {
    public function __construct() {
        $this->productModel = $this->model('Product');
    }

    public function index() {
        $products = $this->productModel->getProducts();
        $data = [
            'title' => 'FreshMart — Shop',
            'products' => $products
        ];
        $this->view('home/index', $data);
    }
}
