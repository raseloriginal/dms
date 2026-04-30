<?php require APPROOT . '/views/admin/layout/header.php'; ?>

<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
        <p class="text-slate-500 mt-1">Welcome back, <span class="text-brand font-bold"><?php echo $_SESSION['admin_username']; ?></span>. Here's what's happening today.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-calendar text-brand_orange"></i>
            <span class="text-sm font-bold text-slate-700"><?php echo date('M d, Y'); ?></span>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Total Sales -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 transition-all hover:shadow-md">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 text-2xl">
            <i class="fa-solid fa-sack-dollar"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-400">Total Sales</p>
            <h3 class="text-2xl font-extrabold text-slate-900">৳<?php echo number_format($data['stats']['total_sales'], 2); ?></h3>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 transition-all hover:shadow-md">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 text-2xl">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-400">Total Orders</p>
            <h3 class="text-2xl font-extrabold text-slate-900"><?php echo $data['stats']['total_orders']; ?></h3>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 transition-all hover:shadow-md">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 text-2xl">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-400">Pending</p>
            <h3 class="text-2xl font-extrabold text-slate-900"><?php echo $data['stats']['pending_orders']; ?></h3>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 transition-all hover:shadow-md">
        <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500 text-2xl">
            <i class="fa-solid fa-user-group"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-400">Customers</p>
            <h3 class="text-2xl font-extrabold text-slate-900"><?php echo $data['stats']['total_customers']; ?></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Recent Orders</h3>
            <a href="<?php echo URLROOT; ?>/admin/orders" class="text-brand_orange text-sm font-bold hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Order ID</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Customer</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Total</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach($data['recent_orders'] as $order): ?>
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-8 py-4 font-bold text-slate-900">#<?php echo $order->order_no; ?></td>
                        <td class="px-8 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700"><?php echo $order->phone; ?></span>
                                <span class="text-[10px] text-slate-400 truncate max-w-[150px]"><?php echo $order->address; ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-4 font-bold text-slate-900">৳<?php echo number_format($order->total, 2); ?></td>
                        <td class="px-8 py-4">
                            <?php 
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-600',
                                    'confirmed' => 'bg-blue-100 text-blue-600',
                                    'ready' => 'bg-indigo-100 text-indigo-600',
                                    'delivered' => 'bg-emerald-100 text-emerald-600',
                                    'cancelled' => 'bg-red-100 text-red-600'
                                ];
                                $class = $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-600';
                            ?>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide <?php echo $class; ?>">
                                <?php echo $order->status; ?>
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <a href="<?php echo URLROOT; ?>/admin/orders/view/<?php echo $order->id; ?>" class="p-2 text-slate-400 hover:text-brand transition-colors">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="space-y-8">
        <!-- Stats Mini -->
        <div class="bg-brand rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-brand/20">
            <div class="relative z-10">
                <h3 class="text-lg font-bold mb-2">Inventory Overview</h3>
                <p class="text-white/60 text-sm mb-6">You have <?php echo $data['stats']['total_products']; ?> products in your catalog.</p>
                <div class="flex items-center gap-4">
                    <a href="<?php echo URLROOT; ?>/admin/products" class="bg-brand_orange text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-brand_orange/20 hover:scale-105 transition-transform">
                        Manage Products
                    </a>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/5 rounded-full"></div>
            <div class="absolute -top-10 -left-10 w-24 h-24 bg-white/5 rounded-full"></div>
        </div>

        <!-- System Health -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-lg font-bold text-slate-900 mb-6">System Health</h3>
            <div class="space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-400 uppercase">Database Storage</span>
                        <span class="text-xs font-bold text-emerald-500">Normal</span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="w-[15%] h-full bg-emerald-500"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-400 uppercase">Order Processing</span>
                        <span class="text-xs font-bold text-emerald-500">Fast</span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="w-[95%] h-full bg-brand_orange"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/layout/footer.php'; ?>
