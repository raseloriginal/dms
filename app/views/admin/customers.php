<?php require APPROOT . '/views/admin/layout/header.php'; ?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Customer Insights</h1>
    <p class="text-slate-500 mt-1">View your most loyal customers and their ordering habits.</p>
</div>

<!-- Customer Grid/Table -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Customer Contact</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Total Spent</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Last Active</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach($data['customers'] as $customer): ?>
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-brand/5 flex items-center justify-center text-brand font-bold text-sm">
                                <?php echo substr($customer->phone, -2); ?>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900"><?php echo $customer->phone; ?></span>
                                <span class="text-[10px] text-slate-400 truncate max-w-[250px]"><?php echo $customer->address; ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold">
                            <?php echo $customer->order_count; ?> Orders
                        </span>
                    </td>
                    <td class="px-8 py-5 font-extrabold text-slate-900">$<?php echo number_format($customer->total_spent, 2); ?></td>
                    <td class="px-8 py-5 text-sm text-slate-500">
                        <?php echo date('M d, Y', strtotime($customer->last_order)); ?>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <button class="text-brand_orange text-sm font-bold hover:underline">View History</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/admin/layout/footer.php'; ?>
