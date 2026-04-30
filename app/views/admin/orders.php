<?php require APPROOT . '/views/admin/layout/header.php'; ?>

<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Order Management</h1>
        <p class="text-slate-500 mt-1">Track and manage all customer orders in real-time.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Active:</span>
            <span class="text-sm font-bold text-brand"><?php echo count($data['orders']); ?></span>
        </div>
    </div>
</div>

<!-- Order Filters -->
<div class="flex flex-wrap gap-3 mb-6">
    <button class="px-6 py-2.5 bg-brand text-white rounded-xl text-sm font-bold shadow-lg shadow-brand/10">All Orders</button>
    <button class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:border-brand_orange transition-all">Pending</button>
    <button class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:border-brand_orange transition-all">Confirmed</button>
    <button class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:border-brand_orange transition-all">Delivered</button>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Order</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Customer</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Total</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach($data['orders'] as $order): ?>
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="px-8 py-5">
                        <span class="font-bold text-slate-900">#<?php echo $order->order_no; ?></span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-700"><?php echo $order->phone; ?></span>
                            <span class="text-[10px] text-slate-400 truncate max-w-[200px]"><?php echo $order->address; ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-sm text-slate-600"><?php echo date('M d, H:i', strtotime($order->created_at)); ?></span>
                    </td>
                    <td class="px-8 py-5 font-extrabold text-slate-900">৳<?php echo number_format($order->total, 2); ?></td>
                    <td class="px-8 py-5">
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
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <select onchange="updateStatus(<?php echo $order->id; ?>, this.value)" 
                                class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold outline-none focus:border-brand_orange transition-all cursor-pointer">
                                <option value="pending" <?php echo $order->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $order->status == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="ready" <?php echo $order->status == 'ready' ? 'selected' : ''; ?>>Ready</option>
                                <option value="delivered" <?php echo $order->status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $order->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button class="p-2.5 text-slate-400 hover:text-brand hover:bg-slate-100 rounded-xl transition-all">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function updateStatus(orderId, status) {
    const formData = new FormData();
    formData.append('order_id', orderId);
    formData.append('status', status);

    fetch('<?php echo URLROOT; ?>/admin/update_order_status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Optional: Show a small toast notification
            location.reload(); // Quick way to update the UI (badges etc)
        } else {
            alert('Failed to update status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating status.');
    });
}
</script>

<?php require APPROOT . '/views/admin/layout/footer.php'; ?>
