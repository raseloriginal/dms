<?php require APPROOT . '/views/admin/layout/header.php'; ?>

<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Product Management</h1>
        <p class="text-slate-500 mt-1">Add, edit, and organize your store's inventory.</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openProductModal()" class="bg-brand text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 shadow-lg shadow-brand/10 hover:bg-brand-dark transition-all">
            <i class="fa-solid fa-plus text-sm"></i>
            Add New Product
        </button>
    </div>
</div>

<!-- Product Table Card -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
        <div class="flex items-center gap-4 flex-1">
            <div class="relative w-full max-w-xs">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" placeholder="Search products..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl outline-none focus:border-brand_orange text-sm transition-all">
            </div>
            <select class="bg-white border border-slate-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-brand_orange">
                <option value="">All Categories</option>
                <option value="Fruits">Fruits</option>
                <option value="Vegetables">Vegetables</option>
                <option value="Dairy">Dairy</option>
                <option value="Bakery">Bakery</option>
                <option value="Beverages">Beverages</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Product</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Category</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Selling / Buying</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach($data['products'] as $product): ?>
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 overflow-hidden flex items-center justify-center text-slate-400 text-xl border border-slate-100">
                                <?php if($product->image): ?>
                                    <img src="<?php echo URLROOT; ?>/uploads/<?php echo $product->image; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fa-solid <?php echo $product->icon; ?>"></i>
                                <?php endif; ?>
                            </div>
                            <span class="font-bold text-slate-900"><?php echo $product->name; ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase tracking-wider">
                            <?php echo $product->category; ?>
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="font-extrabold text-slate-900">৳<?php echo number_format($product->price, 2); ?></span>
                            <span class="text-[10px] text-slate-400 font-bold">Cost: ৳<?php echo number_format($product->buying_price, 2); ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2 text-emerald-500 font-bold text-xs uppercase tracking-wide">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                            In Stock
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick='openProductModal(<?php echo json_encode($product); ?>)' class="p-2.5 text-slate-400 hover:text-brand hover:bg-slate-100 rounded-xl transition-all" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="deleteProduct(<?php echo $product->id; ?>)" class="p-2.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Product Modal -->
<div id="productModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-6 bg-brand/20 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
            <h3 id="modalTitle" class="text-xl font-bold text-slate-900">Add New Product</h3>
            <button onclick="closeProductModal()" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <form id="productForm" onsubmit="saveProduct(event)" class="p-8 space-y-6" enctype="multipart/form-data">
            <input type="hidden" id="prod_id" name="id">
            
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Product Name</label>
                    <input type="text" id="prod_name" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-brand_orange transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Selling Price (৳)</label>
                    <input type="number" step="0.01" id="prod_price" name="price" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-brand_orange transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Buying Price (৳)</label>
                    <input type="number" step="0.01" id="prod_buying_price" name="buying_price" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-brand_orange transition-all">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category</label>
                    <select id="prod_category" name="category" required onchange="toggleNewCategory(this.value)" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-brand_orange transition-all">
                        <option value="Fruits">Fruits</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Dairy">Dairy</option>
                        <option value="Bakery">Bakery</option>
                        <option value="Beverages">Beverages</option>
                        <option value="new">+ Add New Category</option>
                    </select>
                    <input type="text" id="new_category_input" name="new_category" class="hidden mt-3 w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-brand_orange transition-all" placeholder="Enter new category name">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Product Image</label>
                    <div class="flex items-center gap-6">
                        <div id="imagePreviewContainer" class="w-24 h-24 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-300 overflow-hidden border-2 border-dashed border-slate-200 relative">
                            <i class="fa-solid fa-image text-3xl"></i>
                            <img id="imagePreview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                        </div>
                        <div class="flex-1">
                            <input type="file" id="prod_image" name="image" accept="image/*" onchange="previewImage(this)" class="hidden">
                            <label for="prod_image" class="inline-block px-5 py-2.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl cursor-pointer hover:bg-slate-200 transition-all mb-2">Choose Image</label>
                            <p class="text-[10px] text-slate-400">JPG, PNG or WEBP. Max 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeProductModal()" class="flex-1 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all">Cancel</button>
                <button type="submit" class="flex-1 py-3 bg-brand text-white font-bold rounded-xl shadow-lg shadow-brand/10 hover:bg-brand-dark transition-all">Save Product</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleNewCategory(value) {
    const input = document.getElementById('new_category_input');
    if(value === 'new') {
        input.classList.remove('hidden');
        input.required = true;
    } else {
        input.classList.add('hidden');
        input.required = false;
    }
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    const icon = container.querySelector('i');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function openProductModal(product = null) {
    const modal = document.getElementById('productModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('productForm');
    const preview = document.getElementById('imagePreview');
    const icon = document.getElementById('imagePreviewContainer').querySelector('i');
    
    if(product) {
        title.innerText = 'Edit Product';
        document.getElementById('prod_id').value = product.id;
        document.getElementById('prod_name').value = product.name;
        document.getElementById('prod_price').value = product.price;
        document.getElementById('prod_buying_price').value = product.buying_price || 0;
        document.getElementById('prod_category').value = product.category;
        
        if(product.image) {
            preview.src = `<?php echo URLROOT; ?>/uploads/${product.image}`;
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
        } else {
            preview.classList.add('hidden');
            icon.classList.remove('hidden');
        }
    } else {
        title.innerText = 'Add New Product';
        form.reset();
        document.getElementById('prod_id').value = '';
        preview.classList.add('hidden');
        icon.classList.remove('hidden');
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function saveProduct(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch('<?php echo URLROOT; ?>/admin/save_product', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function deleteProduct(id) {
    if(confirm('Are you sure you want to delete this product?')) {
        fetch(`<?php echo URLROOT; ?>/admin/delete_product/${id}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}
</script>

<?php require APPROOT . '/views/admin/layout/footer.php'; ?>
