// ============================================
// FRESHMART — CORE FRONTEND LOGIC
// ============================================

const DELIVERY_FEE = 2.00;
let currentCategory = "All";
let selectedOrder = null;

const statusBangla = {
    pending: 'অপেক্ষমান',
    confirmed: 'নিশ্চিত',
    delivered: 'ডেলিভারি হয়েছে',
    cancelled: 'বাতিল',
    ready: 'প্রস্তুত'
};

// ===== STORAGE HELPERS =====
const getCart = () => JSON.parse(localStorage.getItem('fart_cart') || '{}');
const setCart = c => localStorage.setItem('fart_cart', JSON.stringify(c));

const getManifest = () => JSON.parse(localStorage.getItem('manifest_checks') || '[]');
const setManifest = m => localStorage.setItem('manifest_checks', JSON.stringify(m));

// ===== PRODUCT FILTERING =====
const catMapping = {
    'সব': 'All',
    'ফল': 'Fruits',
    'সবজি': 'Vegetables',
    'ডেইরি': 'Dairy',
    'বেকারি': 'Bakery',
    'পানীয়': 'Beverages'
};

function setCategory(cat, el) {
    currentCategory = catMapping[cat] || cat;
    document.querySelectorAll('.cat-chip').forEach(c => {
        c.classList.remove('bg-brand_orange', 'text-white', 'shadow-lg', 'shadow-brand_orange/20');
        c.classList.add('bg-slate-50', 'text-slate-400', 'border', 'border-slate-100');
    });
    
    if (el) {
        el.classList.add('bg-brand_orange', 'text-white', 'shadow-lg', 'shadow-brand_orange/20');
        el.classList.remove('bg-slate-50', 'text-slate-400', 'border', 'border-slate-100');
    }
    
    filterProducts();
}

function filterProducts() {
    const q = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const cards = document.querySelectorAll('.product-card');
    
    cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        const name = card.getAttribute('data-name');
        const matchesCat = currentCategory === 'All' || cat === currentCategory;
        const matchesSearch = name.includes(q) || cat.toLowerCase().includes(q);
        
        if (matchesCat && matchesSearch) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// ===== CART LOGIC =====
function addToCart(product) {
    const cart = getCart();
    if (!cart[product.id]) {
        cart[product.id] = { ...product, qty: 0 };
    }
    cart[product.id].qty += 1;
    setCart(cart);
    updateCartUI();
    showToast(`${product.name} ব্যাগে যোগ করা হয়েছে`);
}

function changeQty(id, delta) {
    const cart = getCart();
    if (cart[id]) {
        cart[id].qty = Math.max(0, cart[id].qty + delta);
        if (cart[id].qty === 0) delete cart[id];
        setCart(cart);
        updateCartUI();
        renderCartPanel();
    }
}

function updateCartUI() {
    const cart = getCart();
    const cartValues = Object.values(cart);
    
    let totalItems = 0;
    let subtotal = 0;
    
    cartValues.forEach(item => {
        const qty = parseInt(item.qty) || 0;
        const price = parseFloat(item.price) || 0;
        totalItems += qty;
        subtotal += price * qty;
    });
    
    const totalWithFee = subtotal > 0 ? (subtotal + DELIVERY_FEE) : 0;
    
    const badge = document.getElementById('cartBadge');
    const totalFloat = document.getElementById('cartTotalFloat');
    const floatBtn = document.getElementById('floatCart');
    
    if (badge) badge.textContent = totalItems;
    if (totalFloat) totalFloat.textContent = `$${totalWithFee.toFixed(2)}`;
    
    if (floatBtn) {
        if (totalItems > 0) {
            floatBtn.classList.remove('hidden');
            // Ensure display: block is set before removing opacity/transform
            setTimeout(() => {
                floatBtn.classList.remove('translate-y-10', 'opacity-0');
            }, 10);
        } else {
            floatBtn.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => {
                if (Object.values(getCart()).length === 0) {
                    floatBtn.classList.add('hidden');
                }
            }, 300);
        }
    }
    
    // Sync shop grid buttons
    document.querySelectorAll('[id^="ctrl-"]').forEach(ctrl => {
        const id = ctrl.id.replace('ctrl-', '');
        const item = cart[id];
        
        if (item) {
            ctrl.innerHTML = `
                <div class="flex items-center justify-between bg-slate-50 border border-slate-100 rounded p-1 h-11">
                    <button onclick="updateShopQty(${id}, -1)" class="w-10 h-full flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors"><i class="fa-solid fa-minus"></i></button>
                    <span class="font-bold text-slate-900 text-sm">${item.qty}</span>
                    <button onclick="updateShopQty(${id}, 1)" class="w-10 h-full flex items-center justify-center text-brand"><i class="fa-solid fa-plus"></i></button>
                </div>
            `;
        } else if (typeof ALL_PRODUCTS !== 'undefined') {
            const p = ALL_PRODUCTS.find(x => x.id == id);
            if (p) {
                const safeName = p.name.replace(/'/g, "\\'");
                ctrl.innerHTML = `
                    <button 
                        onclick="addToCart({id: ${p.id}, name: '${safeName}', price: ${p.price}, icon: '${p.icon}'})"
                        class="w-full h-11 bg-brand hover:bg-brand-dark text-white rounded flex items-center justify-center gap-2 transition-all active:scale-95 shadow-lg shadow-brand/20 font-bold text-[10px]"
                    >
                        <i class="fa-solid fa-plus"></i>
                        ব্যাগে যোগ করুন
                    </button>
                `;
            }
        }
    });
}

function updateShopQty(id, delta) {
    changeQty(id, delta);
    if (!getCart()[id]) {
        const ctrl = document.getElementById(`ctrl-${id}`);
        // Reset to plus button
        const p = ALL_PRODUCTS.find(x => x.id == id);
        if (p && ctrl) {
            ctrl.innerHTML = `
                <button 
                    onclick="addToCart({id: ${p.id}, name: '${p.name}', price: ${p.price}, icon: '${p.icon}'})"
                    class="w-full h-11 bg-brand hover:bg-brand-dark text-white rounded flex items-center justify-center gap-2 transition-all active:scale-95 shadow-lg shadow-brand/20 font-bold text-[10px]"
                >
                    <i class="fa-solid fa-plus"></i>
                    ব্যাগে যোগ করুন
                </button>
            `;
        }
    }
}

// ===== CART PANEL UI =====
function openCart() {
    renderCartPanel();
    const panel = document.getElementById('cartPanel');
    const overlay = document.getElementById('cartOverlay');
    panel.classList.remove('translate-y-full');
    overlay.classList.remove('hidden');
    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
}

function closeCart() {
    const panel = document.getElementById('cartPanel');
    const overlay = document.getElementById('cartOverlay');
    if (!panel) return;
    panel.classList.add('translate-y-full');
    overlay.classList.add('opacity-0');
    setTimeout(() => overlay.classList.add('hidden'), 300);
}

function closeAllPopups() {
    closeOrderPopup();
    closeCart();
    closeConfirm();
}

function renderCartPanel() {
    const cart = getCart();
    const list = document.getElementById('cartItemsList');
    if (!list) return;

    if (Object.keys(cart).length === 0) {
        list.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-2xl text-slate-200 mb-3">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <p class="text-slate-400 font-bold">আপনার ব্যাগ খালি</p>
            </div>
        `;
        updateCartTotals(0);
        return;
    }

    list.innerHTML = Object.values(cart).map(item => `
        <div class="flex items-center gap-4 bg-white p-3 rounded-2xl border border-slate-50 shadow-sm">
            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-xl text-slate-800">
                <i class="fa-solid ${item.icon}"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-bold text-slate-800 truncate">${item.name}</h4>
                <p class="text-brand font-bold text-xs">$${(item.price * item.qty).toFixed(2)}</p>
            </div>
            <div class="flex items-center gap-3 bg-slate-50 rounded-lg px-2 py-1">
                <button onclick="changeQty(${item.id}, -1)" class="text-slate-400 hover:text-rose-500"><i class="fa-solid fa-minus text-[10px]"></i></button>
                <span class="text-xs font-bold text-slate-800">${item.qty}</span>
                <button onclick="changeQty(${item.id}, 1)" class="text-brand"><i class="fa-solid fa-plus text-[10px]"></i></button>
            </div>
        </div>
    `).join('');

    const subtotal = Object.values(cart).reduce((sum, item) => sum + (item.price * item.qty), 0);
    updateCartTotals(subtotal);
}

function updateCartTotals(sub) {
    const subEl = document.getElementById('subtotalVal');
    const totalEl = document.getElementById('totalVal');
    if (subEl) subEl.textContent = `$${sub.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `$${(sub + (sub > 0 ? DELIVERY_FEE : 0)).toFixed(2)}`;
}

// ===== CHECKOUT =====
async function checkout() {
    const cart = getCart();
    const phone = document.getElementById('phoneInput')?.value.trim();
    const address = document.getElementById('addressInput')?.value.trim();
    
    if (Object.keys(cart).length === 0) return showToast("আপনার ব্যাগ খালি!");
    if (!phone) return showToast("দয়া করে ফোন নম্বর দিন");
    if (!address) return showToast("দয়া করে ঠিকানা দিন");

    const subtotal = Object.values(cart).reduce((sum, item) => sum + (item.price * item.qty), 0);
    
    const payload = {
        phone,
        address,
        subtotal,
        total: subtotal + DELIVERY_FEE,
        items: Object.values(cart)
    };

    try {
        const res = await fetch(`${URLROOT}/cart/checkout`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        
        if (data.status === 'success') {
            localStorage.removeItem('fart_cart');
            showToast(`অর্ডার #${data.order_no} সফলভাবে সম্পন্ন হয়েছে!`);
            closeCart();
            updateCartUI();
            setTimeout(() => window.location.href = `${URLROOT}/orders`, 1500);
        } else {
            showToast(data.message || "অর্ডার দিতে সমস্যা হয়েছে");
        }
    } catch (e) {
        showToast("সার্ভার কানেকশন ফেইল্ড");
    }
}

// ===== ORDER POPUP =====
function openOrderPopup(order) {
    selectedOrder = order;
    document.getElementById('popupTitle').textContent = `অর্ডার #${order.order_no}`;
    document.getElementById('popupPhone').textContent = order.phone;
    document.getElementById('popupAddress').textContent = order.address;
    document.getElementById('popupTotal').textContent = `$${parseFloat(order.total).toFixed(2)}`;
    document.getElementById('popupStatus').textContent = order.status;
    
    // Date formatting
    const date = new Date(order.created_at);
    document.getElementById('popupDate').textContent = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    
    const statusColors = {
        pending: 'text-amber-500',
        confirmed: 'text-blue-500',
        ready: 'text-brand',
        delivered: 'text-emerald-500',
        cancelled: 'text-rose-500'
    };
    
    document.getElementById('popupStatus').textContent = statusBangla[order.status] || order.status;
    document.getElementById('popupStatus').className = `text-sm font-bold ${statusColors[order.status] || 'text-slate-400'}`;

    const itemsList = document.getElementById('popupItems');
    itemsList.innerHTML = order.items.map(item => `
        <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-2xl border border-white shadow-sm">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-lg text-slate-800">
                <i class="fa-solid ${item.icon}"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-bold text-slate-800 truncate">${item.name}</h4>
                <p class="text-[10px] text-slate-400 font-bold">পরিমাণ: ${item.qty} × $${parseFloat(item.price).toFixed(2)}</p>
            </div>
            <span class="text-sm font-bold text-slate-900">$${(item.qty * item.price).toFixed(2)}</span>
        </div>
    `).join('');

    // Action buttons based on status
    const btnConfirm = document.getElementById('btnConfirm');
    const btnCancel = document.getElementById('btnCancel');
    
    if (order.status === 'pending') {
        btnConfirm.style.display = 'block';
        btnCancel.style.display = 'block';
        btnConfirm.onclick = () => {
            showConfirm(
                "অর্ডারটি গ্রহণ করুন", 
                "আপনি কি নিশ্চিত যে এই অর্ডারটি গ্রহণ করতে চান?", 
                "fa-check", 
                "text-brand",
                () => updateStatus(order.id, 'confirmed')
            );
        };
        btnCancel.onclick = () => {
            showConfirm(
                "অর্ডারটি বাতিল করুন", 
                "আপনি কি নিশ্চিত যে এই অর্ডারটি বাতিল করতে চান?", 
                "fa-xmark", 
                "text-rose-500",
                () => updateStatus(order.id, 'cancelled')
            );
        };
    } else {
        btnConfirm.style.display = 'none';
        btnCancel.style.display = 'none';
    }

    const popup = document.getElementById('orderPopup');
    const overlay = document.getElementById('popupOverlay');
    popup.classList.remove('translate-y-full');
    overlay.classList.remove('hidden');
    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
}

function closeOrderPopup() {
    const popup = document.getElementById('orderPopup');
    const overlay = document.getElementById('popupOverlay');
    if (!popup) return;
    popup.classList.add('translate-y-full');
    overlay.classList.add('opacity-0');
    setTimeout(() => overlay.classList.add('hidden'), 300);
}

async function updateStatus(id, status) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);

    try {
        const res = await fetch(`${URLROOT}/cart/updateStatus`, {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        if (data.status === 'success') {
            showToast(`অর্ডারের অবস্থা পরিবর্তন করা হয়েছে: ${statusBangla[status] || status}`);
            closeAllPopups();
            setTimeout(() => window.location.reload(), 1000);
        }
    } catch (e) {
        showToast("আপডেট ফেইল্ড");
    }
}

// ===== DELIVERY ACTIONS =====
async function updateDeliveryStatus(id, status) {
    const config = {
        delivered: { title: "অর্ডার সম্পন্ন করুন", desc: "এই অর্ডারটি কি সফলভাবে ডেলিভারি করা হয়েছে?", icon: "fa-check-double", color: "text-brand" },
        cancelled: { title: "অর্ডার বাতিল করুন", desc: "এই ডেলিভারিটি কি বাতিল করতে চান?", icon: "fa-xmark", color: "text-rose-500" }
    };
    
    const cfg = config[status];
    showConfirm(cfg.title, cfg.desc, cfg.icon, cfg.color, async () => {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('status', status);

        try {
            const res = await fetch(`${URLROOT}/cart/updateStatus`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.status === 'success') {
                const card = document.getElementById(`delivery-card-${id}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(100px)';
                    setTimeout(() => card.remove(), 300);
                }
                showToast(`অর্ডার ${statusBangla[status] || status} হিসেবে চিহ্নিত করা হয়েছে`);
                closeAllPopups();
            }
        } catch (e) {
            showToast("অ্যাকশন ফেইল্ড");
        }
    });
}

function callCustomer() {
    if (selectedOrder) window.location.href = `tel:${selectedOrder.phone}`;
}

// ===== TOAST =====
function showToast(msg) {
    const toast = document.getElementById('toast');
    const msgEl = document.getElementById('toastMsg');
    if (!toast) return;
    
    msgEl.textContent = msg;
    toast.classList.remove('opacity-0', 'translate-y-4');
    toast.classList.add('opacity-100', 'translate-y-0');
    
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-4');
        toast.classList.remove('opacity-100', 'translate-y-0');
    }, 2500);
}

// ===== MANIFEST LOGIC =====
function toggleManifestItem(id, el) {
    const manifest = getManifest();
    const index = manifest.indexOf(id);
    const card = el.closest('.manifest-card');
    
    if (index === -1) {
        manifest.push(id);
        card.classList.add('opacity-30');
        el.classList.add('bg-brand', 'text-white');
    } else {
        manifest.splice(index, 1);
        card.classList.remove('opacity-30');
        el.classList.remove('bg-brand', 'text-white');
    }
    
    setManifest(manifest);
    updateManifestProgress();
}

function updateManifestProgress() {
    const manifest = getManifest();
    const cards = document.querySelectorAll('.manifest-card');
    if (!cards.length) return;

    let checkedCount = 0;
    cards.forEach(card => {
        const id = parseInt(card.getAttribute('data-id'));
        if (manifest.includes(id)) {
            checkedCount++;
            card.classList.add('opacity-30');
            const btn = card.querySelector('.check-btn');
            if (btn) btn.classList.add('bg-brand', 'text-white');
        }
    });

    const progressEl = document.getElementById('manifestProgress');
    const completeBtn = document.getElementById('btnCompleteManifest');
    
    if (progressEl) {
        const percent = Math.round((checkedCount / cards.length) * 100);
        progressEl.style.width = `${percent}%`;
        document.getElementById('progressText').textContent = `${checkedCount}/${cards.length} সংগ্রহ হয়েছে`;
    }

    if (completeBtn) {
        if (checkedCount === cards.length && cards.length > 0) {
            if (completeBtn.classList.contains('hidden')) {
                showToast("সব পণ্য সংগ্রহ করা হয়েছে! নিচে ক্লিক করে সম্পন্ন করুন।");
            }
            completeBtn.classList.remove('hidden', 'translate-y-10', 'opacity-0');
        } else {
            completeBtn.classList.add('hidden', 'translate-y-10', 'opacity-0');
        }
    }

    // Update individual order badges
    document.querySelectorAll('.order-queue-card').forEach(card => {
        const productIds = JSON.parse(card.getAttribute('data-products'));
        const isReady = productIds.every(id => manifest.includes(id));
        const dot = card.querySelector('.status-dot');
        const text = card.querySelector('.status-text');
        
        if (isReady) {
            dot.classList.remove('bg-blue-500', 'animate-pulse');
            dot.classList.add('bg-brand');
            text.classList.remove('text-blue-500');
            text.classList.add('text-brand');
            text.textContent = 'প্রস্তুত';
        } else {
            dot.classList.add('bg-blue-500', 'animate-pulse');
            dot.classList.remove('bg-brand');
            text.classList.add('text-blue-500');
            text.classList.remove('text-brand');
            text.textContent = 'সংগ্রহ করা হচ্ছে';
        }
    });
}

async function completeManifest() {
    showConfirm(
        "সংগ্রহ সম্পন্ন করুন", 
        "আপনি কি নিশ্চিত যে এই অর্ডারগুলোর জন্য সব পণ্য সংগ্রহ করা হয়েছে?", 
        "fa-truck-ramp-box", 
        "text-brand",
        async () => {
            try {
                const res = await fetch(`${URLROOT}/collect/complete`, {
                    method: 'POST'
                });
                const data = await res.json();
                if (data.status === 'success') {
                    localStorage.removeItem('manifest_checks');
                    showToast("পণ্য সংগ্রহ করা হয়েছে! অর্ডার ডেলিভারির জন্য প্রস্তুত।");
                    closeAllPopups();
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch (e) {
                showToast("সম্পন্ন করতে সমস্যা হয়েছে");
            }
        }
    );
}

// ===== CUSTOM CONFIRM LOGIC =====
function showConfirm(title, desc, icon, iconColor, onConfirm) {
    const popup = document.getElementById('confirmPopup');
    if (!popup) return;
    
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmDesc').textContent = desc;
    
    const iconEl = document.getElementById('confirmIcon');
    iconEl.innerHTML = `<i class="fa-solid ${icon}"></i>`;
    iconEl.className = `w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl mb-6 border border-slate-100 ${iconColor}`;
    
    const btn = document.getElementById('confirmActionBtn');
    btn.onclick = () => {
        closeConfirm();
        onConfirm();
    };
    
    popup.classList.remove('hidden');
    setTimeout(() => {
        popup.classList.add('opacity-100');
        popup.querySelector('div:last-child').classList.remove('scale-90');
        popup.querySelector('div:last-child').classList.add('scale-100');
    }, 10);
}

function closeConfirm() {
    const popup = document.getElementById('confirmPopup');
    if (!popup) return;
    
    popup.classList.remove('opacity-100');
    popup.querySelector('div:last-child').classList.add('scale-90');
    popup.querySelector('div:last-child').classList.remove('scale-100');
    setTimeout(() => popup.classList.add('hidden'), 300);
}

// ===== PULL TO REFRESH =====
let touchStart = 0;
let pullDist = 0;
const PULL_THRESHOLD = 80;
const mainContent = document.getElementById('mainContent');
const pullIndicator = document.getElementById('pullIndicator');

if (mainContent && pullIndicator) {
    mainContent.addEventListener('touchstart', e => {
        // Prevent pull-to-refresh if interacting with panels
        if (e.target.closest('#orderPopup') || e.target.closest('#cartPanel')) {
            touchStart = 0;
            return;
        }

        if (mainContent.scrollTop === 0) {
            touchStart = e.touches[0].pageY;
            pullIndicator.style.transition = 'none';
        } else {
            touchStart = 0;
        }
    });

    mainContent.addEventListener('touchmove', e => {
        if (touchStart === 0) return;
        const touch = e.touches[0].pageY;
        pullDist = Math.max(0, touch - touchStart);
        
        if (pullDist > 0 && mainContent.scrollTop === 0) {
            // Logarithmic feel for resistance
            const dampedDist = Math.pow(pullDist, 0.85) * 2;
            pullIndicator.style.height = `${Math.min(dampedDist, 100)}px`;
            pullIndicator.style.opacity = Math.min(pullDist / PULL_THRESHOLD, 1);
            
            if (pullDist > PULL_THRESHOLD) {
                pullIndicator.querySelector('i').classList.add('fa-spin');
            } else {
                pullIndicator.querySelector('i').classList.remove('fa-spin');
            }
        }
    });

    mainContent.addEventListener('touchend', () => {
        if (pullDist > PULL_THRESHOLD && mainContent.scrollTop === 0) {
            pullIndicator.style.transition = 'height 0.3s';
            pullIndicator.style.height = '60px';
            setTimeout(() => window.location.reload(), 500);
        } else {
            pullIndicator.style.transition = 'height 0.3s, opacity 0.3s';
            pullIndicator.style.height = '0';
            pullIndicator.style.opacity = '0';
        }
        touchStart = 0;
        pullDist = 0;
    });
}

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();
    if (document.getElementById('manifestProgress')) {
        updateManifestProgress();
    }
});
