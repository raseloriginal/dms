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
            <span class="text-sm font-bold text-brand" id="totalActiveOrders"><?php echo count($data['orders']); ?></span>
        </div>
    </div>
</div>

<!-- Order Filters -->
<div class="flex flex-wrap gap-3 mb-6" id="orderFilters">
    <button data-filter="all" class="filter-btn px-6 py-2.5 bg-brand text-white rounded-xl text-sm font-bold shadow-lg shadow-brand/10 transition-all">All Orders</button>
    <button data-filter="pending" class="filter-btn px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:border-brand_orange transition-all">Pending</button>
    <button data-filter="confirmed" class="filter-btn px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:border-brand_orange transition-all">Confirmed</button>
    <button data-filter="ready" class="filter-btn px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:border-brand_orange transition-all">Ready</button>
    <button data-filter="delivered" class="filter-btn px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:border-brand_orange transition-all">Delivered</button>
    <button data-filter="cancelled" class="filter-btn px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:border-brand_orange transition-all">Cancelled</button>
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
            <tbody class="divide-y divide-slate-50" id="ordersTableBody">
                <?php foreach($data['orders'] as $order): ?>
                <tr class="order-row hover:bg-slate-50/30 transition-colors" data-status="<?php echo $order->status; ?>" id="order-row-<?php echo $order->id; ?>">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-slate-900">#<?php echo $order->order_no; ?></span>
                            <a href="tel:<?php echo $order->phone; ?>" class="w-7 h-7 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white shadow-sm transition-colors" title="Call Customer">
                                <i class="fa-solid fa-phone text-[10px]"></i>
                            </a>
                        </div>
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
                        <span id="status-badge-<?php echo $order->id; ?>" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide <?php echo $class; ?>">
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
                            <button onclick="viewOrder(<?php echo $order->id; ?>)" class="p-2.5 text-slate-400 hover:text-brand hover:bg-slate-100 rounded-xl transition-all">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <tr id="noOrdersMessage" class="hidden">
                    <td colspan="6" class="px-8 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-box-open text-2xl text-slate-300"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">No Orders Found</h3>
                            <p class="text-xs text-slate-500">There are no orders matching the selected filter.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl scale-95 opacity-0 transition-all duration-300 transform" id="orderModalContent">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900" id="modalOrderNo">Order Details</h3>
                    <p class="text-xs text-slate-500" id="modalOrderDate"></p>
                </div>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <!-- Loader -->
                <div id="modalLoader" class="flex flex-col items-center justify-center py-10">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-brand mb-3"></i>
                    <p class="text-sm font-bold text-slate-500">Loading order details...</p>
                </div>

                <!-- Content (hidden by default) -->
                <div id="modalContentData" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Customer Info</h4>
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                <p class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-phone text-slate-400 text-xs"></i>
                                    <span id="modalCustomerPhone"></span>
                                </p>
                                <p class="text-sm text-slate-600 mt-2 flex items-start gap-2">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-xs mt-1"></i>
                                    <span id="modalCustomerAddress"></span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Order Summary</h4>
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex flex-col justify-center h-[calc(100%-1.5rem)]">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-slate-600">Status:</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-slate-200 text-slate-700" id="modalOrderStatus"></span>
                                </div>
                                <div class="flex justify-between items-center mb-2 pt-2 border-t border-slate-200/60">
                                    <span class="text-sm text-slate-600">Subtotal:</span>
                                    <span class="font-bold text-slate-900" id="modalSubtotal"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-slate-800">Total:</span>
                                    <span class="font-extrabold text-brand text-lg" id="modalTotal"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Order Items</h4>
                    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-slate-50" id="modalOrderItems">
                                <!-- Items will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter Functionality
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const orderRows = document.querySelectorAll('.order-row');
    const totalActiveEl = document.getElementById('totalActiveOrders');
    const noOrdersMsg = document.getElementById('noOrdersMessage');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button styling
            filterBtns.forEach(b => {
                b.classList.remove('bg-brand', 'text-white', 'shadow-lg', 'shadow-brand/10');
                b.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-600');
            });
            btn.classList.remove('bg-white', 'border', 'border-slate-200', 'text-slate-600');
            btn.classList.add('bg-brand', 'text-white', 'shadow-lg', 'shadow-brand/10');

            const filterValue = btn.getAttribute('data-filter');
            let visibleCount = 0;

            orderRows.forEach(row => {
                if (filterValue === 'all' || row.getAttribute('data-status') === filterValue) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            totalActiveEl.textContent = visibleCount;
            
            if (visibleCount === 0) {
                noOrdersMsg.classList.remove('hidden');
            } else {
                noOrdersMsg.classList.add('hidden');
            }
        });
    });
});

// Update Status Dynamically
const statusClasses = {
    'pending': 'bg-amber-100 text-amber-600',
    'confirmed': 'bg-blue-100 text-blue-600',
    'ready': 'bg-indigo-100 text-indigo-600',
    'delivered': 'bg-emerald-100 text-emerald-600',
    'cancelled': 'bg-red-100 text-red-600'
};

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
            // Update UI dynamically
            const row = document.getElementById('order-row-' + orderId);
            if (row) {
                row.setAttribute('data-status', status);
            }
            
            const badge = document.getElementById('status-badge-' + orderId);
            if (badge) {
                badge.textContent = status.toUpperCase();
                // Remove all previous classes and add the new one
                badge.className = `px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide ${statusClasses[status] || 'bg-slate-100 text-slate-600'}`;
            }

            // Recalculate filters if a specific filter is currently active
            const activeFilterBtn = document.querySelector('.filter-btn.bg-brand');
            if (activeFilterBtn) {
                activeFilterBtn.click(); // Re-apply the current filter
            }

        } else {
            alert('Failed to update status: ' + data.message);
            location.reload(); // Reload to reset the select input if failed
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating status.');
        location.reload();
    });
}

// Modal Functionality
const modal = document.getElementById('orderModal');
const modalContent = document.getElementById('orderModalContent');
const modalLoader = document.getElementById('modalLoader');
const modalContentData = document.getElementById('modalContentData');

function viewOrder(orderId) {
    // Show Modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95', 'opacity-0');
    }, 10);

    // Show Loader, hide data
    modalLoader.classList.remove('hidden');
    modalContentData.classList.add('hidden');

    // Fetch details
    fetch(`<?php echo URLROOT; ?>/admin/get_order_details/${orderId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                populateModal(data.order);
                modalLoader.classList.add('hidden');
                modalContentData.classList.remove('hidden');
            } else {
                alert('Could not fetch order details.');
                closeModal();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error loading order details.');
            closeModal();
        });
}

function populateModal(order) {
    document.getElementById('modalOrderNo').textContent = `Order #${order.order_no}`;
    
    // Format date properly
    const date = new Date(order.created_at);
    document.getElementById('modalOrderDate').textContent = date.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    
    document.getElementById('modalCustomerPhone').textContent = order.phone;
    document.getElementById('modalCustomerAddress').textContent = order.address;
    
    document.getElementById('modalSubtotal').textContent = `৳${parseFloat(order.subtotal).toFixed(2)}`;
    document.getElementById('modalTotal').textContent = `৳${parseFloat(order.total).toFixed(2)}`;
    
    const statusEl = document.getElementById('modalOrderStatus');
    statusEl.textContent = order.status;
    statusEl.className = `px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide ${statusClasses[order.status] || 'bg-slate-100 text-slate-600'}`;

    // Populate items
    const tbody = document.getElementById('modalOrderItems');
    tbody.innerHTML = '';
    
    if (order.items && order.items.length > 0) {
        order.items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0">
                            ${item.image ? `<img src="<?php echo URLROOT; ?>/uploads/${item.image}" class="w-full h-full object-cover rounded-lg">` : `<i class="fa-solid ${item.icon || 'fa-box'} text-brand/50"></i>`}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 line-clamp-1">${item.name}</p>
                            <p class="text-xs text-slate-500">৳${parseFloat(item.price).toFixed(2)} × ${item.qty}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-right">
                    <p class="text-sm font-bold text-slate-900">৳${(parseFloat(item.price) * parseInt(item.qty)).toFixed(2)}</p>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } else {
        tbody.innerHTML = `<tr><td colspan="2" class="px-4 py-6 text-center text-sm text-slate-500">No items found.</td></tr>`;
    }
}

function closeModal() {
    modal.classList.add('opacity-0');
    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modal on click outside
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});
</script>

<?php require APPROOT . '/views/admin/layout/footer.php'; ?>
