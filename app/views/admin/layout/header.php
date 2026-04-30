<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?> | Admin Panel</title>
    <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/assets/img/favicon.png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#0a1d37',
                        'brand-dark': '#061324',
                        brand_orange: '#ff781f',
                        dark: '#0f172a'
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-link.active {
            background-color: #ff781f;
            color: white;
            box-shadow: 0 4px 15px rgba(255, 120, 31, 0.3);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

<div class="flex min-h-screen relative">
    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden opacity-0 transition-opacity duration-300"></div>

    <!-- Sidebar -->
    <aside id="adminSidebar" class="w-64 bg-brand text-white flex-shrink-0 fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:relative lg:translate-x-0 transition-transform duration-300 flex flex-col h-screen lg:h-auto overflow-y-auto">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <a href="<?php echo URLROOT; ?>/admin" class="flex items-center gap-3">
                <img src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="Logo" class="h-10 bg-white p-1 rounded">
                <span class="font-bold text-xl tracking-tight">Admin <span class="text-brand_orange">DMS</span></span>
            </a>
            <!-- Mobile Close Button -->
            <button id="closeSidebarBtn" class="lg:hidden text-white/50 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg bg-white/5">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 mt-4">
            <a href="<?php echo URLROOT; ?>/admin" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-white/10 <?php echo ($data['title'] == 'Dashboard | Admin') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="<?php echo URLROOT; ?>/admin/products" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-white/10 <?php echo ($data['title'] == 'Product Management') ? 'active' : ''; ?>">
                <i class="fa-solid fa-box w-5"></i>
                <span class="font-medium">Products</span>
            </a>
            
            <a href="<?php echo URLROOT; ?>/admin/orders" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-white/10 <?php echo ($data['title'] == 'Order Management') ? 'active' : ''; ?>">
                <i class="fa-solid fa-shopping-cart w-5"></i>
                <span class="font-medium">Orders</span>
            </a>
            
            <a href="<?php echo URLROOT; ?>/admin/customers" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-white/10 <?php echo ($data['title'] == 'Customer Management') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users w-5"></i>
                <span class="font-medium">Customers</span>
            </a>
            
            <div class="pt-10 pb-4 px-4 text-xs font-semibold text-white/40 uppercase tracking-wider">Settings</div>
            
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-white/10">
                <i class="fa-solid fa-gear w-5"></i>
                <span class="font-medium">Settings</span>
            </a>
            
            <a href="<?php echo URLROOT; ?>/admin/logout" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-red-500/20 text-red-400 mt-auto">
                <i class="fa-solid fa-right-from-bracket w-5"></i>
                <span class="font-medium">Logout</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Header -->
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 z-10 sticky top-0">
            <div class="lg:hidden">
                <button id="mobileMenuBtn" class="p-2 text-brand hover:bg-slate-50 rounded-lg transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
            
            <div class="flex-1 flex justify-end items-center gap-6">
                <div class="relative">
                    <button class="p-2 text-slate-400 hover:text-brand transition-colors">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-brand_orange rounded-full"></span>
                    </button>
                </div>
                
                <div class="flex items-center gap-3 pl-6 border-l border-slate-200">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-900"><?php echo $_SESSION['admin_username']; ?></p>
                        <p class="text-[10px] font-medium text-slate-400 uppercase">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-brand flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-8">
